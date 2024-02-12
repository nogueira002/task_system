<?php 
include 'db_connect.php';
if(isset($_GET['id'])){
	$qry = $conn->query("SELECT * FROM task_list where id = ".$_GET['id'])->fetch_array();
	foreach($qry as $k => $v){
		$$k = $v;
	}
}
?>
<div class="container-fluid">
	<dl>
		<dt><b class="border-bottom border-primary">Nome-Tarefa</b></dt>
		<dd><?php echo ucwords($task) ?></dd>
	</dl>
	<dl>
		<dt><b class="border-bottom border-primary">Estado</b></dt>
		<dd>
			<?php 
        	if($status == 1){
		  		echo "<span class='badge badge-secondary'>Pendente</span>";
        	}elseif($status == 2){
		  		echo "<span class='badge badge-primary'>Em Progresso</span>";
        	}elseif($status == 3){
		  		echo "<span class='badge badge-success'>Concluído</span>";
        	}
        	?>
		</dd>
	</dl>
	<dl>
		<dt><b class="border-bottom border-primary">Descrição</b></dt>
		<dd><?php echo html_entity_decode($description) ?></dd>
	</dl>
</div>