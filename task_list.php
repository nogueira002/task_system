<?php include 'db_connect.php'; ?>
<div class="col-lg-12" style="padding-left:970px">

    <select class="form-control filter-select">
        <option value="0">Todas as Tarefas</option>
        <option value="1">Tarefas Pendentes</option>
        <option value="2">Tarefas em Progresso</option>
        <option value="3">Tarefas Concluídas</option>
    </select>
</div>
<br>
<table class="table table-hover table-condensed" id="list">
    <thead>
        <tr>
            <th class="text-center">#</th>
            <th>Projeto</th>
            <th>Nome-Tarefa</th>
            <th>Inicio do Projeto</th>
            <th>Data Pervista de Termino</th>
            <th>Estado das Tarefas</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <?php
        $i = 1;
        $where = "";
        $user_id = $_SESSION['login_id'];

        if ($_SESSION['login_type'] == 1) {
			$tasks_query = $conn->query("SELECT t.*, p.name as pname, p.start_date, p.status as pstatus, p.end_date, p.id as pid FROM task_list t inner join project_list p on p.id = t.project_id order by p.name asc;");
        }elseif ($_SESSION['login_type'] == 2) {
			$tasks_query = $conn->query("SELECT t.*, p.name as pname, p.start_date, p.status as pstatus, p.end_date, p.id as pid FROM task_list t inner join project_list p on p.id = t.project_id where p.manager_id = $user_id ORDER BY p.end_date ASC;");
        }
		elseif ($_SESSION['login_type'] == 3) {
            $tasks_query = $conn->query("SELECT t.*, p.name as pname, p.start_date, p.status as pstatus, p.end_date, p.id as pid FROM task_list t inner join project_list p on p.id = t.project_id where t.employee_id = $user_id ORDER BY p.end_date ASC");
        }

        while ($row = $tasks_query->fetch_assoc()) :
            $trans = get_html_translation_table(HTML_ENTITIES, ENT_QUOTES);
            unset($trans["\""], $trans["<"], $trans[">"], $trans["<h2"]);
            $desc = strtr(html_entity_decode($row['description']), $trans);
            $desc = str_replace(array("<li>", "</li>"), array("", ", "), $desc);
            $tprog = $conn->query("SELECT * FROM task_list where project_id = {$row['pid']}")->num_rows;
            $cprog = $conn->query("SELECT * FROM task_list where project_id = {$row['pid']} and status = 3")->num_rows;
            $prog = $tprog > 0 ? ($cprog / $tprog) * 100 : 0;

            // Verifica se a data de término já passou
            $end_date = strtotime($row['end_date']);
            $current_date = time();
            $days_until_due = floor(($end_date - $current_date) / (60 * 60 * 24));

            // Adiciona classes de alerta com base na proximidade da data de término
            if ($days_until_due < 0) {
                $alert_class = 'alert-danger'; // vermelho para data de término passada
                $alert_message = 'Atrasada!';
            } elseif ($days_until_due < 3) {
                $alert_class = 'alert-warning'; // amarelo para tarefas prestes a acabar
                $alert_message = 'Prestes a acabar!';
            } else {
                $alert_class = ''; // sem alerta se a data de término estiver distante
                $alert_message = '';
            }
        ?>
            <tr class="task-row" data-status="<?php echo $row['status']; ?>">
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

<style>
    table p {
        margin: unset !important;
    }

    table td {
        vertical-align: middle !important
    }
</style>

<script>
    // Script para filtrar as tarefas com base no status selecionado na combobox
    $('.filter-select').change(function() {
        var status = $(this).val();
        if (status == 0) {
            $('.task-row').show();
        } else {
            $('.task-row').hide();
            $('.task-row[data-status="' + status + '"]').show();
        }
    });
</script>

