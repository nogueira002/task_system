<?php 
include 'db_connect.php';
if(isset($_GET['id'])){
	$qry = $conn->query("SELECT * FROM task_list where id = ".$_GET['id'])->fetch_array();
	foreach($qry as $k => $v){
		$$k = $v;
	}
}
session_start()
?>
<div class="container-fluid">
	<form action="" id="manage-task">
		<input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
		<input type="hidden" name="project_id" value="<?php echo isset($_GET['pid']) ? $_GET['pid'] : '' ?>">
		<div class="form-group">
			<label for="">Tarefa</label>
			<input type="text" class="form-control form-control-sm" name="task" value="<?php echo isset($task) ? $task : '' ?>" required>
		</div>
		<div class="form-group">
			<label for="">Descrição</label>
			<textarea name="description" id="" cols="30" rows="10" class="summernote form-control">
				<?php echo isset($description) ? $description : '' ?>
			</textarea>
		</div>
		<div class="form-group">
			<label for="">Status</label>
			<select name="status" id="status" class="custom-select custom-select-sm">
				<option value="1" <?php echo isset($status) && $status == 1 ? 'selected' : '' ?>>Pendente</option>
				<option value="2" <?php echo isset($status) && $status == 2 ? 'selected' : '' ?>>Em Progresso</option>
				<option value="3" <?php echo isset($status) && $status == 3 ? 'selected' : '' ?>>Comcluido</option>
			</select>
		</div>
		<div class="form-group">
    	<label for="employee_id">Empregado :</label>
		<?php if($_SESSION['login_type'] == 3): ?> <!--aqui caso o login seja igual diferente de 3 aparece ....-->
			<span>Seleciona o teu nome</span>
        <?php endif; ?>
		
		
    		<select name="employee_id" id="employee_id" class="form-control form-control-sm">
        	<?php 
        	include 'db_connect.php';
        
			// Verifica se há um ID de projeto na URL
        	if(isset($_GET['pid'])){
            	$project_id = $_GET['pid'];
            	// Consulta SQL para obter os IDs dos usuários associados ao projeto específico
            	$query = "SELECT user_ids FROM project_list WHERE id = $project_id";
            	$result = $conn->query($query);
            	// Verifica se há resultados na consulta
            	if ($result->num_rows > 0) {
            	    $row = $result->fetch_assoc();
            	    $user_ids = explode(',', $row['user_ids']); // Convertendo a string de IDs em um array

            	    // Loop através dos IDs de usuários e busca os nomes correspondentes
            	    foreach ($user_ids as $user_id) {
            	        // Consulta para obter o nome do usuário com base no ID
            	        $user_query = $conn->query("SELECT CONCAT(firstname, ' ', lastname) AS name FROM users WHERE id = $user_id");
            	        if ($user_query && $user_query->num_rows > 0) {
            	            $user_data = $user_query->fetch_assoc();
            	            // Exibe o nome do usuário como opção na dropdown
            	            echo '<option value="' . $user_id . '">' . ucwords($user_data['name']) . '</option>';
            	        }
            	    }
            	} else {
            	    // Caso não haja resultados na consulta
            	    echo '<option value="">Nenhum usuário associado a este projeto</option>';
            	}
        	} else {
            	// Caso não haja um ID de projeto na URL
            	echo '<option value="">Nenhum projeto selecionado</option>';
        	}

        	// Fechar conexão com o banco de dados
        	$conn->close();
		
        	?>
			
    	</select>
 
	

	</form>
</div>

<script>
	$(document).ready(function(){


	$('.summernote').summernote({
        height: 200,
        toolbar: [
            [ 'style', [ 'style' ] ],
            [ 'font', [ 'bold', 'italic', 'underline', 'strikethrough', 'superscript', 'subscript', 'clear'] ],
            [ 'fontname', [ 'fontname' ] ],
            [ 'fontsize', [ 'fontsize' ] ],
            [ 'color', [ 'color' ] ],
            [ 'para', [ 'ol', 'ul', 'paragraph', 'height' ] ],
            [ 'table', [ 'table' ] ],
            [ 'view', [ 'undo', 'redo', 'fullscreen', 'codeview', 'help' ] ]
        ]
    })
     })
    
    $('#manage-task').submit(function(e){
    	e.preventDefault()
    	start_load()
    	$.ajax({
    		url:'ajax.php?action=save_task',
			data: new FormData($(this)[0]),
		    cache: false,
		    contentType: false,
		    processData: false,
		    method: 'POST',
		    type: 'POST',
			success:function(resp){
				if(resp == 1){
					alert_toast('Data successfully saved',"success");
					setTimeout(function(){
						location.reload()
					},1500)
				}
			}
    	})
    })
</script>