<?php

include 'db_connect.php';

// Executa uma consulta SQL para obter os detalhes de um usuário com base no ID fornecido via GET
$qry = $conn->query("SELECT * FROM users where id = ".$_GET['id'])->fetch_array();

// Itera sobre os resultados da consulta
foreach($qry as $k => $v){
    // Cria variáveis dinamicamente com os nomes das colunas da tabela e seus valores
    $$k = $v;
}

// Inclui o arquivo new_user.php
include 'new_user.php';
?>
