<?php
// update_task_status.php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $taskId = $_POST['taskId'];
    $status = $_POST['status'];

    // Adicione logs para verificar os valores
    error_log("Task ID: $taskId, Status: $status");

    // Restante do seu código...
} else {
    echo 'Método de requisição inválido';
}
?>