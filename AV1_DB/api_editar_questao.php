<?php
// API que recebe os dados da edição e salva as alterações no banco de dados

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

// Pega os dados que o JavaScript enviou
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $tipo = $_GET['tipo'];
    $pergunta = $_GET['pergunta'];
    $erro = "";
    $comandoSQL = ""; 

    // Lógica pra Múltipla Escolha
    if ($tipo == 'ME') {
        $respostas = [];
        if (isset($_GET['respostas'])) { $respostas = $_GET['respostas']; }
        $resposta_correta = null;
        if (isset($_GET['resposta_correta'])) { $resposta_correta = $_GET['resposta_correta']; }
        
        if (!empty($pergunta)) {
            $respostasPreenchidas = [];
            if(is_array($respostas)){
                foreach($respostas as $r){ if(!empty($r)){ $respostasPreenchidas[] = $r; } }
            }
            if (count($respostasPreenchidas) >= 2) {
                if ($resposta_correta !== null && !empty($respostas[$resposta_correta])) {
                    $respostasString = implode("|", $respostasPreenchidas);
                    
                    // Comando SQL de UPDATE
                    $comandoSQL = "UPDATE Perguntas SET " .
                                  "pergunta = '" . $pergunta . "', " .
                                  "respostas = '" . $respostasString . "', " .
                                  "resposta_correta_idx = " . $resposta_correta . " " .
                                  "WHERE id = '" . $id . "'";
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
            // Comando SQL de UPDATE
            $comandoSQL = "UPDATE Perguntas SET " .
                          "pergunta = '" . $pergunta . "', " .
                          "respostas = NULL, " .
                          "resposta_correta_idx = NULL " .
                          "WHERE id = '" . $id . "'";
        } else {
            $erro = "O campo da pergunta não pode estar vazio.";
        }
    }

    // Se não teve nenhum erro nas validações, continua pra salvar
    if (empty($erro)) {
        if (!empty($comandoSQL)) {
            $resultado = $conn->query($comandoSQL);
            if ($resultado === TRUE) {
                // Manda a mensagem de sucesso de volta pro JavaScript
                echo "Alterações salvas com sucesso!";
            } else {
                echo "Erro ao salvar no banco: " . $conn->error; 
            }
        }
    } else {
        // Se teve algum erro, manda a mensagem de erro pro JavaScript
        echo "Erro: " . $erro;
    }

} else {
    echo "Erro: Dados insuficientes.";
}

$conn->close();
?>