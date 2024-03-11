<?php if(!isset($conn)){ include 'db_connect.php'; } ?>

<div class="col-lg-12">
	<div class="card card-outline ">
		<div class="card-body">
			<form action="" id="manage-project">

        <input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
					<label for="" class="control-label">Nome</label>
					<input type="text" class="form-control form-control-sm" name="name" required value="<?php echo isset($name) ? $name : '' ?>">
				</div>
			</div>
            
            <div class="col-md-6">
				<div class="form-group">
					<label for="" class="control-label">Descrição</label>
                    <input type="text" class="form-control form-control-sm" name="description" required value="<?php echo isset($description	) ? $description: '' ?>">
				</div>
			</div>
          	
		</div>
		
		
        <div class="row">
        	<?php if($_SESSION['login_type'] == 1 ): ?>
            <div class="col-md-6">
            <div class="form-group">
              <label for="" class="control-label">Gestor de projeto</label>
              <select class="form-control form-control-sm select2" name="manager_id">
              	<option></option>
              	<?php 
              	$managers = $conn->query("SELECT *,concat(firstname,' ',lastname) as name FROM users where type = 2 order by concat(firstname,' ',lastname) asc ");
              	while($row= $managers->fetch_assoc()):
              	?>
              	<option value="<?php echo $row['id'] ?>" <?php echo isset($manager_id) && $manager_id == $row['id'] ? "selected" : '' ?>><?php echo ucwords($row['name']) ?></option>
              	<?php endwhile; ?>
              </select>
            </div>
            </div>
            <?php else: ?>
      	    <input type="hidden" name="manager_id" value="<?php echo $_SESSION['login_id'] ?>">
            <?php endif; ?>
            <div class="col-md-6">
            <div class="form-group">
              <label for="" class="control-label">Equipa do projeto</label>
              <select class="form-control form-control-sm select2" multiple="multiple" name="user_ids[]">
              	<option></option>
              	<?php 
              	$employees = $conn->query("SELECT *,concat(firstname,' ',lastname) as name FROM users where type = 3 order by concat(firstname,' ',lastname) asc ");
              	while($row= $employees->fetch_assoc()):
              	?>
              	<option value="<?php echo $row['id'] ?>" <?php echo isset($user_ids) && in_array($row['id'],explode(',',$user_ids)) ? "selected" : '' ?>><?php echo ucwords($row['name']) ?></option>
              	<?php endwhile; ?>
              </select>
            </div>
          </div>
        </div> 
        <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="" class="control-label">Stakeholders</label>
                <input type="text" class="form-control form-control-sm" name="stakeholders" required value="<?php echo isset($stakeholders) ? $stakeholders : '' ?>">
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="" class="control-label">Riscos</label>
                <input type="text" class="form-control form-control-sm" name="risks" required value="<?php echo isset($risks) ? $risks : '' ?>">
            </div>
        </div>
        <div class="col-md-6">
                <div class="form-group">
                    <label for="" class="control-label">Data de Inicio</label>
                    <input type="date" class="form-control form-control-sm" autocomplete="off" name="end_date" required value="<?php echo isset($start_date) ? date("Y-m-d",strtotime($start_date)) : '' ?>">

                </div>
            </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="" class="control-label">Orçamento</label>
                <input type="number" class="form-control form-control-sm" name="budget" required value="<?php echo isset($budget) ? $budget : '' ?>">
            </div>
        </div>
        
    </div>
    <div class="row">
    	<div class="col-md-6">
            <div class="form-group">
                <label for="" class="control-label">Data de Pervista de término</label>
                <input type="date" class="form-control form-control-sm" autocomplete="off" name="end_date" required value="<?php echo isset($end_date) ? date("Y-m-d",strtotime($end_date)) : '' ?>">
            </div>
        </div>
	</div>
    <div class="row">
    <div class="col-md-6">
				<div class="form-group">
					<label for="">Estado</label>
					<select name="status" id="status" class="custom-select custom-select-sm">
						<option value="0" <?php echo isset($status) && $status == 0 ? 'selected' : '' ?>>Pendente</option>
						<option value="3" <?php echo isset($status) && $status == 3 ? 'selected' : '' ?>>Em espera</option>
						<option value="5" <?php echo isset($status) && $status == 5 ? 'selected' : '' ?>>Concluído</option>
					</select>
				</div>
			</div>
    </div>
    </div>

    
    
    
    

    <div class="row">
       
        </div>

        </form>
        </div>
    	    <div class="card-footer border-top border-info">
    		    <div class="d-flex w-100 justify-content-center align-items-center">
    			    <button class="btn btn-primary" form="manage-project">Salvar</button>
    			    <button class="btn bg-gradient-secondary mx-2" type="button" onclick="location.href='index.php?page=project_list'">Cancelar</button>
    		    </div>
    	    </div>
	    </div>
    </div>
<script>
    $('#manage-project').submit(function(e) {
    e.preventDefault();

    start_load();

    $.ajax({
        url: 'ajax.php?action=save_project',
        data: new FormData($(this)[0]),
        cache: false,
        contentType: false,
        processData: false,
        method: 'POST',
        type: 'POST',
        success: function(resp) {
            if (resp == 1) {
                alert_toast('Data successfully saved', "success");
                setTimeout(function() {
                    location.href = 'index.php?page=project_list'
                }, 2000);
            }
        }
    });
});
</script>