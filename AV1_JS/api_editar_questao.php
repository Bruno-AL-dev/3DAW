<?php
// API que recebe os dados da edição e salva as alterações no arquivo

// Vê se o JavaScript mandou os dados
if (isset($_GET['id'])) {
    $nomeArquivo = 'questoes.txt';
    // Pega os dados que vieram da URL
    $id = $_GET['id'];
    $tipo = $_GET['tipo'];
    $pergunta = $_GET['pergunta'];
    $erro = "";
    $linhaAtualizada = "";

    // Lógica de validação pra Múltipla Escolha
    if ($tipo == 'ME') {
        // Pega as respostas e a resposta correta
        $respostas = [];
        if (isset($_GET['respostas'])) {
            $respostas = $_GET['respostas'];
        }
        $resposta_correta = null;
        if (isset($_GET['resposta_correta'])) {
            $resposta_correta = $_GET['resposta_correta'];
        }
        
        // 1. A pergunta não pode estar vazia
        if (!empty($pergunta)) {
            // Tira as respostas que o usuário deixou em branco
            $respostasPreenchidas = [];
            if(is_array($respostas)){
                foreach($respostas as $r) {
                    if(!empty($r)) {
                        $respostasPreenchidas[] = $r;
                    }
                }
            }
            // 2. Precisa ter pelo menos duas respostas
            if (count($respostasPreenchidas) >= 2) {
                // 3. O usuário tem que ter marcado uma resposta correta
                if ($resposta_correta !== null && !empty($respostas[$resposta_correta])) {
                    // Se passou em tudo, monta a nova linha pra salvar
                    $respostasString = implode("|", $respostasPreenchidas);
                    $linhaAtualizada = $id . ";ME;" . $pergunta . ";" . $respostasString . ";" . $resposta_correta . "\n";
                } else {
                    $erro = "É necessário marcar uma alternativa correta que esteja preenchida";
                }
            } else {
                $erro = "É necessário preencher pelo menos duas alternativas";
            }
        } else {
            $erro = "A pergunta não foi preenchida";
        }
    } 
    // Lógica pra salvar pergunta de texto
    else if ($tipo == 'TX') {
        // A pergunta não pode estar vazia
        if (!empty($pergunta)) {
            $linhaAtualizada = $id . ";TX;" . $pergunta . ";;\n";
        } else {
            $erro = "O campo da pergunta não pode estar vazio";
        }
    }

    // Se não teve nenhum erro nas validações, continua pra salvar
    if (empty($erro)) {
        $tempArquivoNome = 'questoes_temp.txt';
        $arquivoOriginal = fopen($nomeArquivo, 'r');
        $arquivoTemp = fopen($tempArquivoNome, 'w');
        if ($arquivoOriginal && $arquivoTemp) {
            // Lê o arquivo original linha por linha
            while (($linha = fgets($arquivoOriginal)) !== false) {
                $dados = explode(';', $linha);
                // Se for a linha que vai ser editada, escreve a linha nova
                if (trim($dados[0]) == $id) {
                    fwrite($arquivoTemp, $linhaAtualizada);
                } else {
                    // Se não for, só copia a linha antiga pro arquivo novo
                    fwrite($arquivoTemp, $linha);
                }
            }
            fclose($arquivoOriginal);
            fclose($arquivoTemp);

            // No final, apaga o arquivo velho e renomeia o novo
            unlink($nomeArquivo);
            rename($tempArquivoNome, $nomeArquivo);

            // Manda a mensagem de sucesso de volta pro JavaScript
            echo "Alterações salvas com sucesso";
        } else {
            echo "Erro ao manipular os arquivos no servidor";
        }
    } else {
        // Se teve algum erro, manda a mensagem de erro pro JavaScript
        echo "Erro: " . $erro;
    }
} else {
    echo "Erro: Dados insuficientes";
}
?>