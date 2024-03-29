<?php include'db_connect.php' ?>

	<div class="card card-outline ">
		<div class="card-header">
            <?php if($_SESSION['login_type'] != 3): ?>
			<div class="card-tools">
				<a class="btn btn-primary  btn-sm" href="./index.php?page=new_project"><i class="fa fa-plus"></i> Adicionar Novo Projeto</a>
			</div>
			
            <?php endif; ?>
		</div>
		<div class="card-body">
			<table class="table tabe-hover table-condensed" id="list">
				<colgroup>
					<col width="5%">
					<col width="35%">
					<col width="15%">
					<col width="15%">
					<col width="20%">
					<col width="10%">
				</colgroup>
				<thead>
					<tr>
						<th class="text-center">#</th>
						<th>Projeto</th>
						<th>Data de Inicio</th>
						<th>Data Prevista de Termino</th>
						<th  class="text-center">Estado</th>
						<th  class="text-center">Ação</th>
					</tr>
				</thead>
				<tbody>
					<?php
					$i = 1;
					$stat = array("Pendente","Iniciado","Em Processo","Em espera","Atrasado","Concluído");
					$where = "";
					if($_SESSION['login_type'] == 2){
						$where = " where manager_id = '{$_SESSION['login_id']}' ";
					}elseif($_SESSION['login_type'] == 3){
						$where = " where concat('[',REPLACE(user_ids,',','],['),']') LIKE '%[{$_SESSION['login_id']}]%' ";
					}
					$qry = $conn->query("SELECT * FROM project_list $where order by name asc");
					while($row= $qry->fetch_assoc()):
						$trans = get_html_translation_table(HTML_ENTITIES,ENT_QUOTES);
						unset($trans["\""], $trans["<"], $trans[">"], $trans["<h2"]);
						$desc = strtr(html_entity_decode($row['description']),$trans);
						$desc=str_replace(array("<li>","</li>"), array("",", "), $desc);

					 	$tprog = $conn->query("SELECT * FROM task_list where project_id = {$row['id']}")->num_rows;
		                $cprog = $conn->query("SELECT * FROM task_list where project_id = {$row['id']} and status = 3")->num_rows;
						$prog = $tprog > 0 ? ($cprog/$tprog) * 100 : 0;
		                $prog = $prog > 0 ?  number_format($prog,2) : $prog;
		                $prod = $conn->query("SELECT * FROM user_productivity where project_id = {$row['id']}")->num_rows;
						if($row['status'] == 0 && strtotime(date('Y-m-d')) >= strtotime($row['date_created'])):
						if($prod  > 0  || $cprog > 0)
		                  $row['status'] = 2;
		                else
		                  $row['status'] = 1;
						elseif($row['status'] == 0 && strtotime(date('Y-m-d')) > strtotime($row['end_date'])):
						$row['status'] = 4;
						endif;
					?>
					<tr>
						<th class="text-center"><?php echo $i++ ?></th>
						<td>
							<p><b><?php echo ucwords($row['name']) ?></b></p>
							<div class="progress progress-sm">
                              <div class="progress-bar bg-green" role="progressbar" aria-valuenow="57" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $prog ?>%">
                              </div>
                          	</div>
                          	<small>
                              <?php echo $prog ?>% Completo
                          	</small>
						</td>
						<td><b><?php echo date("M d, Y",strtotime($row['date_created'])) ?></b></td>
						<td><b><?php echo date("M d, Y",strtotime($row['end_date'])) ?></b></td>
						<td class="text-center">
							<?php
							  if($stat[$row['status']] =='Pendente'){
							  	echo "<span class='badge badge-secondary'>{$stat[$row['status']]}</span>";
							  }elseif($stat[$row['status']] =='Iniciado'){
							  	echo "<span class='badge badge-primary'>{$stat[$row['status']]}</span>";
							  }elseif($stat[$row['status']] =='Em Processo'){
							  	echo "<span class='badge badge-info'>{$stat[$row['status']]}</span>";
							  }elseif($stat[$row['status']] =='Em Espera'){
							  	echo "<span class='badge badge-warning'>{$stat[$row['status']]}</span>";
							  }elseif($stat[$row['status']] =='Atrasado'){
							  	echo "<span class='badge badge-danger'>{$stat[$row['status']]}</span>";
							  }elseif($stat[$row['status']] =='Concluído'){
							  	echo "<span class='badge badge-success'>{$stat[$row['status']]}</span>";
							  }
							?>
						</td>
						<td class="text-center">
							<button type="button" class="btn btn-default btn-sm btn-flat border-info wave-effect text-info dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
		                      Ação
		                    </button>
		                    <div class="dropdown-menu" style="">
		                      <a class="dropdown-item view_project" href="./index.php?page=view_project&id=<?php echo $row['id'] ?>" data-id="<?php echo $row['id'] ?>">ver</a>
							  
							  <div class="dropdown-divider"></div>
		                      <?php if($_SESSION['login_type'] != 3): ?>
		                      <a class="dropdown-item" href="./index.php?page=edit_project&id=<?php echo $row['id'] ?>">Editar</a>
		                      <div class="dropdown-divider"></div>
		                      <a class="dropdown-item delete_project" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>">Apagar</a>
		                  <?php endif; ?>
						  
		                    </div>
						</td>
					</tr>	
				<?php endwhile; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
<style>
	table p{
		margin: unset !important;
	}
	table td{
		vertical-align: middle !important
	}
</style>
<script>
	$(document).ready(function() {
    $('#list').dataTable();

    $('.delete_project').click(function() {
        var projectId = $(this).data('id'); // Captura o ID do projeto a partir do atributo 'data-id'
        _conf("Deseja Eleminiar o projeto", "delete_project", [projectId]); // Chama a função _conf passando o ID do projeto
    });

    fetchDataForCharts();
});

// Função para chamar a exclusão do projeto via AJAX
function delete_project(projectId) {
    start_load();
    $.ajax({
        url: 'ajax.php?action=delete_project',
        method: 'POST',
        data: { id: projectId }, // Passa o ID do projeto para o PHP
        success: function(resp) {
            if (resp == 1) {
                alert_toast("Projeto Apagado com sucesso", 'success');
                setTimeout(function() {
                    location.reload(); // Recarrega a página após a exclusão bem-sucedida
                }, 1500);
            }
        }
    });
}

	function fetchDataForCharts() {
        // Fazer uma solicitação AJAX para recuperar dados do PHP
        $.ajax({
            url: 'fetch_chart_data.php',
            method: 'GET',
            success: function(data) {
                // Manipule os dados aqui e crie os gráficos
                createCharts(data);
            },
            error: function(error) {
                console.error('Erro ao recuperar dados:', error);
            }
        });
    }

    function createCharts(data) {
        // Exemplo: Gráfico de andamento de tarefas
        var ctxTaskProgress = document.getElementById('taskProgressChart').getContext('2d');
        var taskProgressChart = new Chart(ctxTaskProgress, {
            type: 'bar',
            data: {
                labels: data.projects.map(project => project.name),
                datasets: [{
                    label: 'Andamento de Tarefas',
                    data: [/* Dados reais de andamento de tarefas */],
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                // Opções de configuração do gráfico
            }
        });

        // Repita o processo para os outros gráficos
    }
    







	
</script>