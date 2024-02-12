  <aside class="main-sidebar  elevation-4" style="background-color: WHITE;">
    <div class="dropdown">
      <!--aqui e feito uma configuração para que se for o admin logado aparece admin , casso contrario aparece user -->
   	<a href="./" class="brand-link">
        <?php if($_SESSION['login_type'] == 1): ?>
          <h3 class="text-center p-0 m-0"><i><img src="assets/img/user.png" style="padding-left:17px;"></i><b style="Color:#435ebe;padding-left:17px;padding-top:15px">Admin</b></h3>
        <?php else: ?>
        <h3 class="text-center p-0 m-0"><img src="assets/img/user.png" style="padding-left:17px;"></i><b style="Color:#435ebe;padding-left:15px;padding-top:15px">USER</b></h3>
        <?php endif; ?>

    </a>
      
    </div>
    
    <div class="sidebar pb-4 mb-4" >
    <hr>
      <nav class="mt-2" >
        <ul class="nav nav-pills nav-sidebar flex-column nav-flat" style="margin: 0px;" data-widget="treeview" role="menu" data-accordion="false">
          <li class="nav-item dropdown" style="border-radius: 10px;margin-bottom: 0px;">
            <a href="./" class="nav-link nav-home">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p style=" font-weight: bold;">
                Dashboard
              </p>
            </a>
          </li>
          <li class="nav-item" style="border-radius: 10px;margin-bottom: 5px;">
            <a href="./index.php?page=project_list" class="nav-link nav-project_list item">
              <i class="nav-icon fas fa-layer-group"></i>
              <p style=" font-weight: bold;">
                Projetos
                
              </p>
            </a>
           
          </li> 
          <?php if($_SESSION['login_type'] != 3): ?>
              <li class="nav-item" style="border-radius: 10px;margin-bottom: 5px; padding-left: 30px;">
                <a href="./index.php?page=new_project" class="nav-link nav-new_project item">
                <i><img src="assets/img/right-arrow.png"></i>
                  <p style="font-weight: bold;">Adicionar</p>
                </a>
              </li>
            <?php endif; ?>
           
          <li class="nav-item" style="border-radius: 10px;margin-bottom: 5px;">
                <a href="./index.php?page=task_list" class="nav-link nav-task_list">
                  <i class="fas fa-tasks nav-icon"></i>
                  <p style=" font-weight: bold;">
                  Tarefas</p>
                </a>
          </li>
          
          <?php if($_SESSION['login_type'] == 1): ?>
          <li class="nav-item" style="border-radius: 10px;margin-bottom: 5px;">
            <a href="./index.php?page=user_list" class="nav-link nav-edit_user item">
              <i class="nav-icon fas fa-users"></i>
              <p style=" font-weight: bold;">
                Utilizadores
              </p>
            </a>
           
          </li>
        <?php endif; ?>
        <?php if($_SESSION['login_type'] == 1): ?>
        <li class="nav-item" style="border-radius: 10px;margin-bottom: 5px; padding-left: 30px;">
              <a href="./index.php?page=new_user" class="nav-link nav-new_user item">
              <i><img src="assets/img/right-arrow.png"></i>
                <p style=" font-weight: bold;">Adicionar</p>
              </a>
        </li>  
        <?php endif; ?>
        </ul>
      </nav>
    </div>
  </aside>
  <script>
  	$(document).ready(function(){
      var page = '<?php echo isset($_GET['page']) ? $_GET['page'] : 'home' ?>';
  		var s = '<?php echo isset($_GET['s']) ? $_GET['s'] : '' ?>';
      if(s!='')
        page = page+'_'+s;
  		if($('.nav-link.nav-'+page).length > 0){
             $('.nav-link.nav-'+page).addClass('active')
  			if($('.nav-link.nav-'+page).hasClass('tree-item') == true){
            $('.nav-link.nav-'+page).closest('.nav-treeview').siblings('a').addClass('active')
  				$('.nav-link.nav-'+page).closest('.nav-treeview').parent().addClass('menu-open')
  			}
        if($('.nav-link.nav-'+page).hasClass('nav-is-tree') == true){
          $('.nav-link.nav-'+page).parent().addClass('menu-open')
        }

  		}
     
  	})
  </script>