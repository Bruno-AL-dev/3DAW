<?php
// API que busca os dados de uma pergunta no banco de dados e devolve em JSON

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

$questaoEncontrada = null;

// Pega o ID e busca no banco
if (isset($_GET['id'])) {
    $idParaBuscar = $_GET['id'];
    
    // Comando SQL pra selecionar a pergunta com o ID
    $sql = "SELECT * FROM Perguntas WHERE id = '" . $idParaBuscar . "'";
    
    $resultadoQuery = $conn->query($sql);

    // Verifica se achou
    if ($resultadoQuery->num_rows > 0) {
        $linha = $resultadoQuery->fetch_assoc();
        
        // Achou, então guarda os dados da pergunta num array
        $questaoEncontrada = [
            'id' => $linha['id'],
            'tipo' => $linha['tipo'],
            'texto' => $linha['pergunta'],
            'respostas' => [],
            'correta' => null
        ];
        
        if ($linha['tipo'] == 'ME') {
            $questaoEncontrada['respostas'] = explode('|', $linha['respostas']);
            $questaoEncontrada['correta'] = (int)$linha['resposta_correta_idx'];
        }
    }
}

// Devolve o JSON
if ($questaoEncontrada) {
    echo json_encode(['resultado' => 'sucesso', 'dados' => $questaoEncontrada]);
} else {
    echo json_encode(['resultado' => 'erro', 'mensagem' => 'Questão não encontrada']);
}

$conn->close();
?>