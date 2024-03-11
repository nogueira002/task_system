<?php include('db_connect.php') ?>
<?php
$twhere ="";
if($_SESSION['login_type'] != 1)
  $twhere = "  ";
?>
<!-- Info boxes -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!--<div class="col-12" style="border-radius:10px">
  <div class="card" style="border-radius:10px">
    <div class="card-body text-Black " style="background-color: #435ebe; border-radius: 10px;font-size:25px">
    <strong> Bem Vindo <?php echo $_SESSION['login_name'] ?>!</strong>
    </div>
  </div>
</div>
<hr>-->


<?php 
$where = "";
if($_SESSION['login_type'] == 2){
  $where = " where manager_id = '{$_SESSION['login_id']}' ";
}elseif($_SESSION['login_type'] == 3){
  $where = " where concat('[',REPLACE(user_ids,',','],['),']') LIKE '%[{$_SESSION['login_id']}]%' ";
}
$where2 = "";
if($_SESSION['login_type'] == 2){
  $where2 = " where p.manager_id = '{$_SESSION['login_id']}' ";
}elseif($_SESSION['login_type'] == 3){
  $where2 = " where concat('[',REPLACE(p.user_ids,',','],['),']') LIKE '%[{$_SESSION['login_id']}]%' ";
}
?>

<div class="row" style="border-radius: 10px;">
  <div class="col-md-8" >
    <div class="card card-outline"  style="border-radius: 10px;">
      <div class="card-header  text-primary" style="background-color: #435ebe;border-radius: 10px;">
        <b style="color:white;">Projetos Em Andamento:</b>
      </div>  
      <div class="card-body p-0" >
        <div class="table-responsive">
          <table class="table m-0 table-hover">
            <colgroup>
              <col width="5%">
              <col width="30%">
              <col width="35%">
              <col width="15%">
              <col width="15%">
            </colgroup>
            <thead>
              <th>#</th>
              <th>Projeto</th>
              <th>Progresso</th>
              <th>Estado</th>
              <th></th>
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
              $prog= 0;
            $tprog = $conn->query("SELECT * FROM task_list where project_id = {$row['id']}")->num_rows;
            $cprog = $conn->query("SELECT * FROM task_list where project_id = {$row['id']} and status = 3")->num_rows;
            $prog = $tprog > 0 ? ($cprog/$tprog) * 100 : 0;
            $prog = $prog > 0 ?  number_format($prog,2) : $prog;
            $prod = $conn->query("SELECT * FROM user_productivity where project_id = {$row['id']}")->num_rows;
            if($row['status'] == 0 && strtotime(date('Y-m-d')) >= strtotime($row['start_date'])):
            if($prod  > 0  || $cprog > 0)
              $row['status'] = 2;
            else
              $row['status'] = 1;
            elseif($row['status'] == 0 && strtotime(date('Y-m-d')) > strtotime($row['end_date'])):
            $row['status'] = 4;
            endif;
              ?>
              <tr>
                  <td>
                     <?php echo $i++ ?>
                  </td>
                  <td>
                      <a>
                          <?php echo ucwords($row['name']) ?>
                      </a>
                      <br>
                      <small>
                          Até: <?php echo date("Y-m-d",strtotime($row['end_date'])) ?>
                      </small>
                  </td>
                  <td class="project_progress">
                      <div class="progress progress-sm">
                          <div class="progress-bar bg-success" role="progressbar" aria-valuenow="<?php echo $prog ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $prog ?>%">
                          </div>
                      </div>
                      <small>
                          <?php echo $prog ?>% Completo
                      </small>
                  </td>
                  <td class="project-state">
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
                  <td>
                    <a class="btn btn-primary btn-sm" href="./index.php?page=view_project&id=<?php echo $row['id'] ?>">
                          <i class="fas fa-folder">
                          </i>
                          Ver Mais
                    </a>
                  </td>
              </tr>
            <?php endwhile; ?>
            </tbody>  
          </table>
        </div>
      </div>
    </div>
  </div>
 


  <div class="col-md-4">
    <div class="row">
      <div class="col-12 col-sm-6 col-md-12">
        <div class="small-box bg-light shadow-sm border">
          <div class="inner">
            <h3><?php echo $conn->query("SELECT * FROM project_list $where")->num_rows; ?></h3>

            <p>Total Projetos</p>
          </div>
          <div class="icon">
            <i class="fa fa-layer-group"></i>
          </div>
        </div>
      </div>
      <div class="col-12 col-sm-6 col-md-12">
        <div class="small-box bg-light shadow-sm border">
          <div class="inner">
            <h3><?php echo $conn->query("SELECT t.*,p.name as pname,p.start_date,p.status as pstatus, p.end_date,p.id as pid FROM task_list t inner join project_list p on p.id = t.project_id $where2")->num_rows; ?></h3>
            <p>Total Tarefas</p>
          </div>
          <div class="icon">
            <i class="fa fa-tasks"></i>
          </div>
        </div>
      </div>
    </div>
  </div>




  <?php if($_SESSION['login_type'] != 3): ?>
    <div class="container">
    <div class="row">
      <div class="col-md-6">
        <div class="card">
          <div class="card-body">
            <canvas id="projectsChart" width="400" height="400"></canvas>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="card">
          <div class="card-body">
            <canvas id="tasksChart" width="400" height="400"></canvas>
          </div>
        </div>
      </div>
    </div>
  </div>
<?php endif; ?>



  




</div>

</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.0/chart.min.js"></script>

<script>
  // Obtenha os dados do PHP para o número total de projetos e tarefas
  <?php
  if ($_SESSION['login_type'] == 1) {
    $totalProjectsQuery = "SELECT COUNT(*) FROM project_list";
    $totalTasksQuery = "SELECT COUNT(*) FROM task_list";
  } else {
    $totalProjectsQuery = "SELECT COUNT(*) FROM project_list $where";
    $totalTasksQuery = "SELECT COUNT(*) FROM task_list t INNER JOIN project_list p ON p.id = t.project_id $where2";
  }

  $totalProjectsResult = $conn->query($totalProjectsQuery);
  $totalProjects = $totalProjectsResult->fetch_assoc()['COUNT(*)'];

  $totalTasksResult = $conn->query($totalTasksQuery);
  $totalTasks = $totalTasksResult->fetch_assoc()['COUNT(*)'];
  ?>

  // Consulta SQL para contar o número total de tarefas do usuário logado com status igual a 3
  <?php
  $userTasksQuery = "";
  if ($_SESSION['login_type'] == 2) {
    $userTasksQuery .= "p.manager_id = '{$_SESSION['login_id']}'";
  } elseif ($_SESSION['login_type'] == 3) {
    $userTasksQuery .= "concat('[',REPLACE(p.user_ids,',','],['),']') LIKE '%[{$_SESSION['login_id']}]%'";
  }

  if ($_SESSION['login_type'] != 1) {
    $userTasksQuery = " AND t.status = 3 AND " . $userTasksQuery;
  } else {
    $userTasksQuery = " WHERE t.status = 3";
  }

  $userTasksResult = $conn->query("SELECT COUNT(*) FROM task_list t INNER JOIN project_list p ON p.id = t.project_id $userTasksQuery");
  $totalUserTasks = $userTasksResult->fetch_assoc()['COUNT(*)'];
  ?>

  // Crie o gráfico de barra para o número total de projetos
  var projectsCtx = document.getElementById('projectsChart').getContext('2d');
  var projectsChart = new Chart(projectsCtx, {
    type: 'bar',
    data: {
      labels: ['Total Projetos'],
      datasets: [{
        label: 'Total Projetos',
        data: [<?php echo $totalProjects; ?>],
        backgroundColor: 'rgba(67, 94, 190, 0.6)',
        borderColor: 'rgba(67, 94, 190, 1)',
        borderWidth: 2
      }]
    },
    options: {
      plugins: {
        title: {
          display: true,
          text: 'Número Total de Projetos',
          font: {
            size: 18
          }
        },
        legend: {
          display: false
        }
      }
    }
  });

  // Crie o gráfico de pizza para o número total de tarefas
  var tasksCtx = document.getElementById('tasksChart').getContext('2d');
  var tasksChart = new Chart(tasksCtx, {
    type: 'pie',
    data: {
      labels: ['Tarefas Pendentes', 'Tarefas Concluídas'],
      datasets: [{
        label: 'Total Tarefas',
        data: [<?php echo $totalTasks - $totalUserTasks; ?>, <?php echo $totalUserTasks; ?>],
        backgroundColor: ['rgba(255, 99, 132, 0.6)', 'rgba(75, 192, 192, 0.6)'],
        borderColor: ['rgba(255, 99, 132, 1)', 'rgba(75, 192, 192, 1)'],
        borderWidth: 1
      }]
    },
    options: {
      plugins: {
        title: {
          display: true,
          text: 'Tarefas Pendentes vs Concluídas',
          font: {
            size: 18
          }
        },
        legend: {
          position: 'right'
        }
      }
    }
  });
</script>


