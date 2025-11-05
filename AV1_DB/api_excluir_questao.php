<?php
// API que recebe um ID e remove a pergunta do banco de dados

// Conecta ao Banco de Dados
$servidor = "localhost";
$username = "root";
$senha = "";
$database = "faeterj3dawmanha";
$conn = new mysqli($servidor, $username, $senha, $database);

if ($conn->connect_error) {
    echo "Erro: Falha na conexão com o banco (" . $conn->connect_error . ")";
    exit();
}

$mensagem = "Erro: ID não fornecido ou inválido.";

// Pega o ID e prepara o comando SQL
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $idParaExcluir = $_GET['id'];

    // Monta o comando SQL de exclusão
    $comandoSQL = "DELETE FROM Perguntas WHERE id = '" . $idParaExcluir . "'";

    // Executa o comando
    $resultado = $conn->query($comandoSQL);

    if ($resultado === TRUE) {
        // Verifica se alguma linha foi realmente afetada
        if ($conn->affected_rows > 0) {
            $mensagem = "Pergunta (ID: $idParaExcluir) foi excluída com sucesso!";
        } else {
            $mensagem = "Aviso: Nenhuma pergunta encontrada com o ID $idParaExcluir.";
        }
    } else {
        // Mostra o erro do banco de dados se a query falhar
        $mensagem = "Erro ao excluir do banco: " . $conn->error; 
    }
}

// Fecha a conexão e devolve a mensagem
$conn->close();
echo $mensagem;
?>