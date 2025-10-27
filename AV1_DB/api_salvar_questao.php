<?php
// API para salvar as perguntas no Banco de Dados

// --- 1. Adiciona a conexão com o Banco ---
$servidor = "localhost";
$username = "root";
$senha = "";
$database = "faeterj3dawmanha";
$conn = new mysqli($servidor, $username, $senha, $database);

// Verifica a conexão
if ($conn->connect_error) {
    die ("Conexao falhou, avise o administrador do sistema");
}

// Verifica se os dados pergunta e tipo foram enviados
if (isset($_GET['pergunta']) && isset($_GET['tipo'])) {
    
    // Pega os dados que vieram da URL
    $tipo = $_GET['tipo'];
    $pergunta = $_GET['pergunta'];
    $erro = "";

    // Lógica pra Múltipla Escolha
    if ($tipo == 'ME') {
        $respostas = [];
        if (isset($_GET['respostas'])) {
            $respostas = $_GET['respostas'];
        }
        $resposta_correta = null;
        if (isset($_GET['resposta_correta'])) {
            $resposta_correta = $_GET['resposta_correta'];
        }

        if (!empty($pergunta)) {
            $respostasPreenchidas = [];
            if(is_array($respostas)){
                foreach($respostas as $r){ if(!empty($r)){ $respostasPreenchidas[] = $r; } }
            }
            if (count($respostasPreenchidas) >= 2) {
                if ($resposta_correta !== null && !empty($respostas[$resposta_correta])) {

                    // Se passou nas validações, prepara pra salvar no BD
                    $respostasString = implode("|", $respostasPreenchidas);

                    $comandoSQL = "INSERT INTO Perguntas (pergunta, tipo, respostas, resposta_correta_idx) VALUES ('" . 
                    $pergunta . "', '" . $tipo . "', '" . $respostasString . "', " . $resposta_correta . ")";                    
                } else {
                    $erro = "É necessário marcar uma alternativa correta que esteja preenchida.";
                }
            } else {
                $erro = "É necessário preencher pelo menos duas alternativas.";
            }
        } else {
            $erro = "A pergunta não foi preenchida.";
        }
    } 
    // Lógica pra Texto
    else if ($tipo == 'TX') {
        if (!empty($pergunta)) {

            $comandoSQL = "INSERT INTO Perguntas (pergunta, tipo, respostas, resposta_correta_idx) VALUES ('" . 
                          $pergunta . "', '" . $tipo . "', NULL, NULL)"; 
        } else {
            $erro = "O campo da pergunta não pode estar vazio.";
        }
    }

    // Após as validações, verifica se pode salvar no BD
    if (empty($erro)) {
        if (!empty($comandoSQL)) { // Pra garantir que o comando foi montado
            $resultado = $conn->query($comandoSQL);
            if ($resultado === TRUE) { // query() retorna TRUE em caso de sucesso no INSERT
                echo "Pergunta incluída com sucesso!";
            } else {
                // Mostra o erro do banco de dados se a query falhar
                echo "Erro ao incluir no banco: " . $conn->error; 
            }
        } else {
             echo "Erro: Comando SQL não foi gerado."; // Por segurança caso algo falhe antes
        }
    } else {
        echo "Erro: " . $erro;
    }

} else {
    echo "Erro: Dados insuficientes para processar a requisição.";
}

// Fecha a conexão com o banco
$conn->close();
?>