<?php
// API para o CRUD de Alunos.

// Configuração do Banco de Dados
$servidor = "localhost";
$usuario_db = "root";
$senha_db = "";
$banco = "bd_faculdade";

// Criando a conexão com o banco
$conexao = new mysqli($servidor, $usuario_db, $senha_db, $banco);

// Verificando se a conexão falhou
if ($conexao->connect_error) {
    // Se falhar, "morre" e exibe o erro
    die("Falha na conexão: " . $conexao->connect_error);
}

// Configuração dos Headers da API
// Aqui diz pro navegador que a resposta é em JSON
header('Content-Type: application/json');
// Permite que o index.html de qualquer origem acesse a API
header('Access-Control-Allow-Origin: *');
// Libera os métodos que vão ser utilizados
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
// Libera os cabeçalhos específicos que o JS vai enviar
header('Access-Control-Allow-Headers: Content-Type');

// Definindo as rotas (O que fazer)

// 1 - Pega o método da requisição (GET, POST, PUT, DELETE)
$metodo = $_SERVER['REQUEST_METHOD'];

// 2 - Pega a ação que veio da URL
// isset verifica se a ação foi enviada
if (isset($_GET['acao'])) {
    $acao = $_GET['acao'];
} else {
    $acao = ''; // Define um valor padrão se 'acao' não for enviada
}

// 3 - Pega o id que veio da URL
if (isset($_GET['id'])) {
    $id = (int)$_GET['id']; // (int) transforma o valor em um número inteiro
} else {
    $id = 0; // Define 0 como padrão se o id não for enviado
}

// 4 - Pega os dados enviados no corpo (body) da requisição (para POST e PUT)
// file_get_contents("php://input") pega os dados crus em JSON
// json_decode transforma o JSON em um array PHP
$dadosRecebidos = json_decode(file_get_contents("php://input"), true);


// Lógica Principal do CRUD

// Switch para decidir o que fazer com base no método
switch ($metodo) {
    case 'POST':
        // Operação de Criação (CREATE)
        if ($acao == 'incluir') {
            // Pega os dados do array $dadosRecebidos
            $nome = $dadosRecebidos['nome'];
            $matricula = $dadosRecebidos['matricula'];
            $email = $dadosRecebidos['email'];

            $sql = "INSERT INTO alunos (nome, matricula, email) VALUES ('$nome', '$matricula', '$email')";
            
            if ($conexao->query($sql) === TRUE) {
                // Se deu certo, retorna o ID do novo aluno
                $novo_id = $conexao->insert_id;
                echo json_encode(['status' => 'sucesso', 'id' => $novo_id, 'nome' => $nome]);
            } else {
                // Se deu erro, retorna o erro do MySQL
                echo json_encode(['status' => 'erro', 'mensagem' => 'Erro ao incluir: ' . $conexao->error]);
            }
        }
        break;

    /*  
        O método OPTIONS é uma pré-requisição que o navegador faz 
        pra checar se os métodos PUT e DELETE são permitidos
        O código 200 responde com "OK"
    */
    case 'OPTIONS':
        http_response_code(200);
        break;

    default:
        // Se o método não for GET, POST, PUT, DELETE ou OPTIONS
        echo json_encode(['erro' => 'Método não permitido']);
        break;
}

// Fecha a conexão
$conexao->close();
?>