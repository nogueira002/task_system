<?php 
// Inclui o arquivo de conexão com o banco de dados
include 'db_connect.php';

// Verifica se foi passado um ID via GET
if(isset($_GET['id'])){
    // Executa uma consulta SQL para obter os detalhes de uma entrada na tabela 'user_productivity' com base no ID fornecido via GET
    $qry = $conn->query("SELECT * FROM user_productivity where id = ".$_GET['id'])->fetch_array();
    // Itera sobre os resultados da consulta
    foreach($qry as $k => $v){
        // Cria variáveis dinamicamente com os nomes das colunas da tabela e seus valores
        $$k = $v;
    }
}

// Adicionar documentos ao projeto
$project_documents = isset($_FILES['project_documents']) ? $_FILES['project_documents'] : array();
if (!empty($project_documents['name'])) {
    // Define o diretório de destino para os documentos do projeto
    $project_documents_path = 'assets/documentos_tarefa';
    // Define o caminho completo para o documento
    $project_documents_filename = $project_documents_path . basename($project_documents['name']);
    // Move o arquivo carregado para o diretório de destino
    move_uploaded_file($project_documents['tmp_name'], $project_documents_filename);

    // Salva o caminho do documento na coluna 'documents' da tabela 'project_list'
    $conn->query("UPDATE project_list SET documents = '$project_documents_filename' WHERE id = $id");
}

// Adicionar documentos às tarefas
$task_documents = isset($_FILES['task_documents']) ? $_FILES['task_documents'] : array();
if (!empty($task_documents['name'])) {
    // Define o diretório de destino para os documentos da tarefa
    $task_documents_path = 'assets/documentos_tarefa';
    // Define o caminho completo para o documento
    $task_documents_filename = $task_documents_path . basename($task_documents['name']);
    // Move o arquivo carregado para o diretório de destino
    move_uploaded_file($task_documents['tmp_name'], $task_documents_filename);

    // Obtém o ID da tarefa a partir dos dados do formulário
    $task_id = $_POST['task_id']; // Certifique-se de ter o valor do ID da tarefa
    // Salva o caminho do documento na coluna 'document_path' da tabela 'task_list'
    $conn->query("UPDATE task_list SET document_path = '$task_documents_filename' WHERE id = $task_id");
}
?>


<div class="container-fluid">
    <form action="" id="manage-progress" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
        <input type="hidden" name="project_id" value="<?php echo isset($_GET['pid']) ? $_GET['pid'] : '' ?>">
        <div class="col-lg-12">
            <div class="row">
                <div class="col-md-5">
                    

                    <div class="form-group">
                        <label for="">Assunto</label>
                        <input type="text" class="form-control form-control-sm" name="subject" value="<?php echo isset($subject) ? $subject : '' ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="">Data</label>
                        <input type="date" class="form-control form-control-sm" name="date" value="<?php echo isset($date) ? date("Y-m-d",strtotime($date)) : '' ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="">Data de Inicio</label>
                        <input type="time" class="form-control form-control-sm" name="start_time" value="<?php echo isset($start_time) ? date("H:i",strtotime("2020-01-01 ".$start_time)) : '' ?>" required>
                    </div>
                   
                </div>
                <div class="col-md-7">
                    <div class="form-group">
                        <label for="">Comentarios/descrição do Progresso</label>
                        <textarea name="comment" id="" cols="30" rows="10" class="summernote form-control" required="">
                            <?php echo isset($comment) ? $comment : '' ?>
                        </textarea>
                    </div>
                </div>
            </div>
        </div>
        
       
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
     $('.select2').select2({
        placeholder:"Please select here",
        width: "100%"
      });
     })
    $('#manage-progress').submit(function(e){
        e.preventDefault()
        start_load()
        $.ajax({
            url:'ajax.php?action=save_progress',
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
                    },1000)
                }
            }
        })
    })
</script>
