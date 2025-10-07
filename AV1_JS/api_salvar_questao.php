<?php

// Verifica se os dados pergunta e tipo foram enviados
if (isset($_GET['pergunta']) && isset($_GET['tipo'])) {
    
    // Pega os dados que vieram da URL
    $nomeArquivo = 'questoes.txt';
    $tipo = $_GET['tipo'];
    $pergunta = $_GET['pergunta'];
    $erro = "";

    // Lógica pra salvar uma pergunta de Múltipla Escolha
    if ($tipo == 'ME') {
        
        // Pega as respostas
        $respostas = [];
        if (isset($_GET['respostas'])) {
            $respostas = $_GET['respostas'];
        }
        
        // Pega o índice da resposta certa
        $resposta_correta = null;
        if (isset($_GET['resposta_correta'])) {
            $resposta_correta = $_GET['resposta_correta'];
        }

        // A pergunta não pode estar vazia
        if (!empty($pergunta)) {
            // Remove respostas que o usuário deixou em branco
            $respostasPreenchidas = [];
            if(is_array($respostas)){
                foreach($respostas as $r){ if(!empty($r)){ $respostasPreenchidas[] = $r; } }
            }

            // Precisa ter pelo menos 2 respostas preenchidas
            if (count($respostasPreenchidas) >= 2) {
                // O usuário precisa ter marcado uma resposta certa que não esteja vazia
                if ($resposta_correta !== null && !empty($respostas[$resposta_correta])) {
                    
                    // Se passou em todas as validações, prepara pra salvar
                    $respostasString = implode("|", $respostasPreenchidas);
                    $id = uniqid();
                    $linha = $id . ";ME;" . $pergunta . ";" . $respostasString . ";" . $resposta_correta . "\n";
                    
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
    // Lógica pra salvar uma pergunta de Texto
    else if ($tipo == 'TX') {
        // A pergunta não pode estar vazia
        if (!empty($pergunta)) {
            $id = uniqid();
            // Monta a linha
            $linha = $id . ";TX;" . $pergunta . ";;\n";
        } else {
            $erro = "O campo da pergunta não pode estar vazio.";
        }
    }

    // Após as validações, verifica se pode salvar
    if (empty($erro)) {
        // Salva a linha no arquivo e devolve a mensagem de sucesso
        $arquivo = fopen($nomeArquivo, 'a');
        fwrite($arquivo, $linha);
        fclose($arquivo);
        echo "Pergunta incluída com sucesso!";
    } else {
        // Se alguma validação falhou, devolve a mensagem de erro para o JavaScript
        echo "Erro: " . $erro;
    }

} else {
    // Se a chamada foi feita sem os dados necessários
    echo "Erro: Dados insuficientes para processar a requisição.";
}
?>