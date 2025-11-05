<?php
// API que lê todas as perguntas do banco de dados e devolve em JSON pro JavaScript

// Avisa o navegador que a resposta é JSON
header('Content-Type: application/json'); 

// Conecta ao Banco de Dados
$servidor = "localhost";
$username = "root";
$senha = "";
$database = "faeterj3dawmanha";
$conn = new mysqli($servidor, $username, $senha, $database);

if ($conn->connect_error) {
    // Se a conexão falhar, devolve um JSON de erro
    echo json_encode(['resultado' => 'erro', 'mensagem' => 'Conexão falhou: ' . $conn->connect_error]);
    exit();
}

// Prepara e executa o comando SQL pra buscar as perguntas
$sql = "SELECT id, tipo, pergunta FROM Perguntas ORDER BY id";
$resultadoQuery = $conn->query($sql);

$perguntas = []; // Array vazio pra guardar as perguntas

// Verifica se o SELECT achou alguma linha
if ($resultadoQuery->num_rows > 0) {
    // Loop pra ler cada linha que o banco retornou
    while($linha = $resultadoQuery->fetch_assoc()) {
        // Adiciona a linha (que já é um array) no array de perguntas
        $perguntas[] = $linha; 
    }
}

// Fecha a conexão com o banco
$conn->close();

// Devolve o array completo de perguntas em formato JSON
echo json_encode(['resultado' => 'sucesso', 'dados' => $perguntas]);
?>