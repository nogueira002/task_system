<?php
include 'db_connect.php';



$stat = array("Pendente","Iniciado","Em Processo","Em espera","Atrasado","Concluído");
$qry = $conn->query("SELECT * FROM project_list where id = ".$_GET['id'])->fetch_array();
foreach($qry as $k => $v){
	$$k = $v;
}
$tprog = $conn->query("SELECT * FROM task_list where project_id = {$id}")->num_rows;
$cprog = $conn->query("SELECT * FROM task_list where project_id = {$id} and status = 3")->num_rows;
$prog = $tprog > 0 ? ($cprog/$tprog) * 100 : 0;
$prog = $prog > 0 ?  number_format($prog,2) : $prog;
$prod = $conn->query("SELECT * FROM user_productivity where project_id = {$id}")->num_rows;
if($status == 0 && strtotime(date('Y-m-d')) >= strtotime($start_date)):
if($prod  > 0  || $cprog > 0)
  $status = 2;
else
  $status = 1;
elseif($status == 0 && strtotime(date('Y-m-d')) > strtotime($end_date)):
$status = 4;
endif;
$manager = $conn->query("SELECT *,concat(firstname,' ',lastname) as name FROM users where id = $manager_id");
$manager = $manager->num_rows > 0 ? $manager->fetch_array() : array();




// Adicionar documentos ao projeto
$project_documents = isset($_FILES['project_documents']) ? $_FILES['project_documents'] : array();
if (!empty($project_documents['name'])) {
    $project_documents_path = 'caminho/para/armazenar/documentos/do/projeto/';
    $project_documents_filename = $project_documents_path . basename($project_documents['name']);
    move_uploaded_file($project_documents['tmp_name'], $project_documents_filename);

    // Salvar o caminho do documento na coluna 'documents' da tabela 'project_list'
    $conn->query("UPDATE project_list SET documents = '$project_documents_filename' WHERE id = $id");
}

// Adicionar documentos às tarefas
$task_documents = isset($_FILES['task_documents']) ? $_FILES['task_documents'] : array();
if (!empty($task_documents['name'])) {
    $task_documents_path = 'caminho/para/armazenar/documentos/da/tarefa/';
    $task_documents_filename = $task_documents_path . basename($task_documents['name']);
    move_uploaded_file($task_documents['tmp_name'], $task_documents_filename);

    // Salvar o caminho do documento na coluna 'documents' da tabela 'task_list'
    $task_id = $_POST['task_id']; // Certifique-se de ter o valor do ID da tarefa
    $conn->query("UPDATE task_list SET documents = '$task_documents_filename' WHERE id = $task_id");
}

?>
<div class="col-lg-12">
	<div class="row">
		<div class="col-md-12">
			<div class="callout ">

				<div class="col-md-12">
					<div class="row">
						<div class="col-sm-6">
							<dl>
								<dt><b class="border-bottom border-primary">Nome Projeto</b></dt>
								<dd><?php echo ucwords($name) ?></dd>
								<dt><b class="border-bottom border-primary"></b></dt>
								<dd><?php echo html_entity_decode($description) ?></dd>
								
							</dl>
						</div>
						<div class="col-md-6">
							<dl>
								<dt><b class="border-bottom border-primary">Data de Inicio</b></dt>
								<dd><?php echo date("F d, Y",strtotime($start_date)) ?></dd>
							</dl>
							<dl>
								<dt><b class="border-bottom border-primary">Data de Fim</b></dt>
								<dd><?php echo date("F d, Y",strtotime($end_date)) ?></dd>
							</dl>
							<dl>
								<dt><b class="border-bottom border-primary">Estado</b></dt>
								<dd>
									<?php
									  if($stat[$status] =='Pendente'){
									  	echo "<span class='badge badge-secondary'>{$stat[$status]}</span>";
									  }elseif($stat[$status] =='Iniciado'){
									  	echo "<span class='badge badge-primary'>{$stat[$status]}</span>";
									  }elseif($stat[$status] =='Em Processo'){
									  	echo "<span class='badge badge-info'>{$stat[$status]}</span>";
									  }elseif($stat[$status] =='Em espera'){
									  	echo "<span class='badge badge-warning'>{$stat[$status]}</span>";
									  }elseif($stat[$status] =='Atrasado'){
									  	echo "<span class='badge badge-danger'>{$stat[$status]}</span>";
									  }elseif($stat[$status] =='Concluído'){
									  	echo "<span class='badge badge-success'>{$stat[$status]}</span>";
									  }
									?>
								</dd>
							</dl>
							<dl>
								<dd>
									<?php if(isset($manager['id'])) : ?>
									<div class="d-flex align-items-center mt-1">
										<img class="img-circle img-thumbnail p-0 shadow-sm border-info img-sm mr-3" src="assets/uploads/<?php echo $manager['avatar'] ?>" alt="Avatar">
										<b><?php echo ucwords($manager['name']) ?></b>
									</div>
									<?php else: ?>
										<small><i>Gerente excluído do banco de dados</i></small>
									<?php endif; ?>
								</dd>

							</dl>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-4">
			<div class="card card-outline ">
				<div class="card-header">
					<span><b>Membros</b></span>
					<div class="card-tools">
						
					</div>
				</div>
				<div class="card-body">
					<ul class="users-list clearfix">
						<?php 
						if(!empty($user_ids)):
							$members = $conn->query("SELECT *,concat(firstname,' ',lastname) as name FROM users where id in ($user_ids) order by concat(firstname,' ',lastname) asc");
							while($row=$members->fetch_assoc()):
						?>
								<li>
			                        <img src="assets/uploads/<?php echo $row['avatar'] ?>" alt="User Image">
			                        <a class="users-list-name" href="javascript:void(0)"><?php echo ucwords($row['name']) ?></a>
			                        <!-- <span class="users-list-date">Today</span> -->
		                    	</li>
						<?php 
							endwhile;
						endif;
						?>
					</ul>
				</div>
			</div>
		</div>
		<div class="col-md-8">
    		<div class="card card-outline ">
		        <div class="card-header">
            		<span><b>Lista de Tarefas:</b></span>
            	<?php if($_SESSION['login_type'] != 3): ?>
            	<div class="card-tools">
                	<button class="btn btn-primary  btn-sm" type="button" id="new_task"><i class="fa fa-plus"></i> Nova Tarefa</button>
            	</div>
            	<?php endif; ?>
        	</div>
        	<div class="card-body p-0">
            	<div class="table-responsive">
                	<table class="table table-condensed m-0 table-hover">
                    	<colgroup>
                        	<col width="5%">
                        	<col width="25%">
                        	<col width="30%">
                        	<col width="15%">
                        	<col width="15%">
                    	</colgroup>
                    	<thead>
                        	<th>#</th>
                        	<th>Tarefa</th>
                        	<th>Descrição</th>
                        	<th>Estado</th>
                        	<?php if($_SESSION['login_type'] != 3): ?> <!--aqui caso o login seja igual diferente de 3 aparece ....-->
                        		<th>Atribuída a</th>
                        	<?php endif; ?>
                        	<th>Ação</th>
					
                    	</thead>
                    	<tbody>
                        	<?php 
                        	$i = 1;
                        	if ($_SESSION['login_type'] == 1 || $_SESSION['login_type'] == 2) {
                            	// Se o tipo de login for 1 ou 2, mostrar todas as tarefas
                            	$tasks_query = "SELECT * FROM task_list WHERE project_id = {$id} ORDER BY task ASC";
                        	} else {
                            	// Caso contrário, mostrar apenas as tarefas atribuídas ao usuário logado
                            	$user_id = $_SESSION['login_id'];
                            	$tasks_query = "SELECT * FROM task_list WHERE project_id = {$id} AND employee_id = $user_id ORDER BY task ASC";
                        	}
                        	$tasks = $conn->query($tasks_query);
                        	while($row=$tasks->fetch_assoc()):
                            	$trans = get_html_translation_table(HTML_ENTITIES,ENT_QUOTES);
                            	unset($trans["\""], $trans["<"], $trans[">"], $trans["<h2"]);
                            	$desc = strtr(html_entity_decode($row['description']),$trans);
                            	$desc=str_replace(array("<li>","</li>"), array("",", "), $desc);
                        	?>
                        	<tr>
                            	<td class="text-center"><?php echo $i++ ?></td>
                            	<td class=""><b><?php echo ucwords($row['task']) ?></b></td>
                            	<td class=""><p class="truncate"><?php echo strip_tags($desc) ?></p></td>
                            	<td>
                                	<?php 
                                	if($row['status'] == 1){
                                	    echo "<span class='badge badge-secondary'>Pendente</span>";
                                	}elseif($row['status'] == 2){
                                	    echo "<span class='badge badge-primary'>Em Progresso</span>";
                                	}elseif($row['status'] == 3){
                                	    echo "<span class='badge badge-success'>Concluído</span>";
                                	}
                                	?>
                            	</td>
                            	<?php if($_SESSION['login_type'] != 3): ?>
                            	<td class="text-center">
                                	<?php if(!empty($row['employee_id'])): ?>
                                	    <!-- Mostra apenas se o campo employee_id não estiver vazio -->
                                	    <?php echo $row['employee_id']; ?>
                                	<?php else: ?>
                                	    <!-- Se o campo employee_id estiver vazio -->
                                	    Empregado não atribuído
                                	<?php endif; ?>
                            	</td>
                            	<?php endif; ?>

                            	<td class="text-center">
                                	<button type="button" class="btn btn-default btn-sm btn-flat border-info wave-effect text-info dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
                                	    Ação
                                	</button>
                                	<div class="dropdown-menu" style="">
                                	    <a class="dropdown-item view_task" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>"  data-task="<?php echo $row['task'] ?>">Ver</a>
                                	    <div class="dropdown-divider"></div>
                                	    <a class="dropdown-item edit_task" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>"  data-task="<?php echo $row['task'] ?>">Editar</a>

                                    	<?php if($_SESSION['login_type'] != 3): ?>
                                    	<div class="dropdown-divider"></div>
                                    	<a class="dropdown-item delete_task" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>">Apagar</a>
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
    </div>
</div>


	<div class="row">
		<div class="col-md-12">
			<div class="card">
				<div class="card-header">
					<b>Atividade</b>
					<div class="card-tools">
						<button class="btn btn-primary  btn-sm" type="button" id="new_productivity"><i class="fa fa-plus"></i> Progresso</button>
					</div>
				</div>
				<div class="card-body">
				<?php 
				$progress = $conn->query("SELECT p.*, concat(u.firstname,' ',u.lastname) as uname, u.avatar FROM user_productivity p inner join users u on u.id = p.user_id where p.project_id = $id order by unix_timestamp(p.date_created) desc ");
				while($row = $progress->fetch_assoc()):
					?>
						<div class="post">

		                      <div class="user-block">
		                      	<?php if($_SESSION['login_id'] == $row['user_id']): ?>
		                      	<span class="btn-group dropleft float-right">
								  <span class="btndropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="cursor: pointer;">
								    <i class="fa fa-ellipsis-v"></i>
								  </span>
								  <div class="dropdown-menu">
								  	<a class="dropdown-item manage_progress" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>">Editar</a>
			                      	<div class="dropdown-divider"></div>
				                     <a class="dropdown-item delete_progress" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>">Apagar</a>
								  </div>
								</span>
								<?php endif; ?>
		                        <img class="img-circle img-bordered-sm" src="assets/uploads/<?php echo $row['avatar'] ?>" alt="user image">
		                        <span class="username">
		                          <a href="#"><?php echo ucwords($row['uname']) ?></a>
		                        </span>
		                        <span class="description">
		                        	<span class="fa fa-calendar-day"></span>
		                        	<span><b><?php echo date('M d, Y',strtotime($row['date'])) ?></b></span>
		                        	<span class="fa fa-user-clock"></span>
                      				<span>Start: <b><?php echo date('h:i A',strtotime($row['date'].' '.$row['start_time'])) ?></b></span>
		                        	<span> | </span>
                      				
	                        	</span>

	                        	

		                      </div>
		                      <!-- /.user-block -->
		                      <div>
		                       <?php echo html_entity_decode($row['comment']) ?>
							   
							   <?php if (!empty($row['documents'])): ?><!-- !empty verifica se uma variável está vazia, ou seja serve para vereficar se o campo da base de dadso esta vazio -->
    							<div class="text-right"> <!-- Adiciona classe para alinhar à direita -->
        							<a href="<?php echo html_entity_decode($row['documents']) ?>" class="btn btn-secondary" target="_blank">Ver Documento</a>
    							</div>
								<?php endif; ?>

		                      </div>

		                      <p>
		                        
		                      </p>
	                    </div>
	                    <div class="post clearfix"></div>
                    <?php endwhile; ?>
				</div>
			</div>
		</div>
	</div>
</div>
<style>
	.users-list>li img {
	    border-radius: 50%;
	    height: 67px;
	    width: 67px;
	    object-fit: cover;
	}
	.users-list>li {
		width: 33.33% !important
	}
	.truncate {
		-webkit-line-clamp:1 !important;
	}
</style>
<script>
	//script para as funções
	$('#new_task').click(function(){
		uni_modal("Nova Tarefa:  <?php echo ucwords($name) ?>","manage_task.php?pid=<?php echo $id ?>","mid-large")
	})
	$('.edit_task').click(function(){
		uni_modal("Editar Tarefa: "+$(this).attr('data-task'),"manage_task.php?pid=<?php echo $id ?>&id="+$(this).attr('data-id'),"mid-large")
	})
	$('.delete_task').click(function(){
		_conf("Tem certeza de que deseja excluir esta tarefa?","delete_task",[$(this).attr('data-id')])
	})
	$('.view_task').click(function(){
		uni_modal("Detalhes da Tarefa","view_task.php?id="+$(this).attr('data-id'),"mid-large")
	})
	$('#new_productivity').click(function(){
		uni_modal("<i class='fa fa-plus'></i> Novo Progresso","manage_progress.php?pid=<?php echo $id ?>",'large')
	})
	$('.manage_progress').click(function(){
		uni_modal("<i class='fa fa-edit'></i> Editar Progresso","manage_progress.php?pid=<?php echo $id ?>&id="+$(this).attr('data-id'),'large')
	})
	$('.delete_progress').click(function(){
	_conf("Tem certeza de que deseja excluir este progresso?","delete_progress",[$(this).attr('data-id')])
	})
	function delete_progress($id){
		start_load()
		$.ajax({
			url:'ajax.php?action=delete_progress',
			method:'POST',
			data:{id:$id},
			success:function(resp){
				if(resp==1){
					alert_toast("Dados excluídos com sucesso ",'sucesso')
					setTimeout(function(){
						location.reload()
					},1500)

				}
			}
		})
	}
	function delete_task($id){
		start_load()
		$.ajax({
			url:'ajax.php?action=delete_task',
			method:'POST',
			data:{id:$id},
			success:function(resp){
				if(resp==1){
					alert_toast("Tarefa apagada com sucesso ",'sucesso')
					setTimeout(function(){
						location.reload()
					},1500)

				}
			}
		})
	}
	

	



</script>