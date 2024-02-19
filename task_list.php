<?php include 'db_connect.php'; ?>
<div class="col-lg-12">
	<div class="card card-outline ">
		<div class="card-header">
			<span><b>Lista de Tarefas:</b></span>
		</div>
		<div class="card-body">
			<table class="table table-hover table-condensed" id="list">
				<thead>
					<tr>
						<th class="text-center">#</th>
						<th>Projeto</th>
						<th>Nome-Tarefa</th>
						<th>Inicio do Projeto</th>
						<th>Data Pervista de Termino</th>
						<th>Estado das Tarefas</th>
					</tr>
				</thead>
				<tbody>
					<?php
					$i = 1;
					$where = "";
					if ($_SESSION['login_type'] == 2 || $_SESSION['login_type'] == 1) {
						// Se o tipo de login for 2 ou 1, seleciona todas as tarefas e os projetos associados
						$tasks_query = $conn->query("SELECT t.*, p.name as pname, p.start_date, p.status as pstatus, p.end_date, p.id as pid FROM task_list t inner join project_list p on p.id = t.project_id order by p.name asc");
					} elseif ($_SESSION['login_type'] == 3) {
						// Caso contrário, seleciona apenas as tarefas associadas ao usuário logado
						$where = " where concat('[',REPLACE(p.user_ids,',','],['),']') LIKE '%[{$_SESSION['login_id']}]%' ";
						$tasks_query = $conn->query("SELECT t.*, p.name as pname, p.start_date, p.status as pstatus, p.end_date, p.id as pid FROM task_list t inner join project_list p on p.id = t.project_id $where order by p.name asc");
					}

					while ($row = $tasks_query->fetch_assoc()) :
						$trans = get_html_translation_table(HTML_ENTITIES, ENT_QUOTES);
						unset($trans["\""], $trans["<"], $trans[">"], $trans["<h2"]);
						$desc = strtr(html_entity_decode($row['description']), $trans);
						$desc = str_replace(array("<li>", "</li>"), array("", ", "), $desc);
						$tprog = $conn->query("SELECT * FROM task_list where project_id = {$row['pid']}")->num_rows;
						$cprog = $conn->query("SELECT * FROM task_list where project_id = {$row['pid']} and status = 3")->num_rows;
						$prog = $tprog > 0 ? ($cprog / $tprog) * 100 : 0;
					?>
						<tr>
							<td class="text-center"><?php echo $i++ ?></td>
							<td>
								<p><b><?php echo ucwords($row['pname']) ?></b></p>
							</td>
							<td>
								<p><b><?php echo ucwords($row['task']) ?></b></p>
								<p class="truncate"><?php echo strip_tags($desc) ?></p>
							</td>
							<td><b><?php echo date("M d, Y", strtotime($row['start_date'])) ?></b></td>
							<td><b><?php echo date("M d, Y", strtotime($row['end_date'])) ?></b></td>
							<td>
								<?php
								if ($row['status'] == 1) {
									echo "<span class='badge badge-secondary'>Pendente</span>";
								} elseif ($row['status'] == 2) {
									echo "<span class='badge badge-primary'>Em Progresso</span>";
								} elseif ($row['status'] == 3) {
									echo "<span class='badge badge-success'>Concluído</span>";
								}
								?>
							</td>
						</tr>
					<?php endwhile; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
<style>
	table p {
		margin: unset !important;
	}

	table td {
		vertical-align: middle !important
	}
</style>
<script>
	//script para as funções
	$('#new_task').click(function() {
		uni_modal("New Task For <?php echo ucwords($name) ?>", "manage_task.php?pid=<?php echo $id ?>", "mid-large")
	})
	$('.edit_task').click(function() {
		uni_modal("Edit Task: " + $(this).attr('data-task'), "manage_task.php?pid=<?php echo $id ?>&id=" + $(this).attr('data-id'), "mid-large")
	})
	$('.view_task').click(function() {
		uni_modal("Task Details", "view_task.php?id=" + $(this).attr('data-id'), "mid-large")
	})
	$('#new_productivity').click(function() {
		uni_modal("<i class='fa fa-plus'></i> New Progress", "manage_progress.php?pid=<?php echo $id ?>", 'large')
	})
	$('.manage_progress').click(function() {
		uni_modal("<i class='fa fa-edit'></i> Edit Progress", "manage_progress.php?pid=<?php echo $id ?>&id=" + $(this).attr('data-id'), 'large')
	})
	$('.delete_progress').click(function() {
		_conf("Are you sure to delete this progress?", "delete_progress", [$(this).attr('data-id')])
	})
	$(document).ready(function() {
		$('#list').dataTable()
		$('.new_productivity').click(function() {
			uni_modal("<i class='fa fa-plus'></i> New Progress for: " + $(this).attr('data-task'), "manage_progress.php?pid=" + $(this).attr('data-pid') + "&tid=" + $(this).attr('data-tid'), 'large')
		})
	})

	function delete_project($id) {
		start_load()
		$.ajax({
			url: 'ajax.php?action=delete_project',
			method: 'POST',
			data: {
				id: $id
			},
			success: function(resp) {
				if (resp == 1) {
					alert_toast("Data successfully deleted", 'success')
					setTimeout(function() {
						location.reload()
					}, 1500)

				}
			}
		})
	}
</script>
