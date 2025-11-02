<?php
// API que recebe os dados de edição do usuário e salva no arquivo de texto

$nomeArquivo = 'usuarios.txt';
$mensagem = "";

// Verifica se os dados foram enviados
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $matricula = $_GET['matricula'];
    $nome = $_GET['nome'];
    $email = $_GET['email'];
    $matriculaJaExiste = false;
    $emailJaExiste = false;

    // Validação pra campos vazios
    if (!empty($matricula) && !empty($nome) && !empty($email)) {
        
        // Validação de formato de email
        if (strpos($email, '@') === false || strpos($email, '.') === false) {
            $mensagem = "Erro: O formato do email parece inválido (falta '@' ou '.')";
        } else {
            // Se o formato estiver ok, vê se tem duplicados       
            // Validação de Matrícula e Email duplicados
            if (file_exists($nomeArquivo)) {
                $arquivoLeitura = fopen($nomeArquivo, 'r');
                if ($arquivoLeitura) {
                    while (($linha = fgets($arquivoLeitura)) !== false) {
                        $dados = explode(';', $linha);
                        $idExistente = trim($dados[0]);
                        $matriculaExistente = trim($dados[1]);
                        $emailExistente = trim($dados[3]); // Pega o email da linha

                        // Vê se a matrícula já existe em um ID diferente
                        if ($matriculaExistente == $matricula && $idExistente != $id) {
                            $matriculaJaExiste = true;
                        }
                        // Vê se o email já existe em um ID diferente
                        if ($emailExistente == $email && $idExistente != $id) {
                            $emailJaExiste = true;
                        }

                        // Se já achou qualquer um dos dois, pode parar o loop
                        if ($matriculaJaExiste || $emailJaExiste) {
                            break;
                        }
                    }
                    fclose($arquivoLeitura);
                }
            }

            // Checagem de erros
            if ($matriculaJaExiste) {
                $mensagem = "Erro: A matrícula '$matricula' já pertence a outro usuário!";
            } else if ($emailJaExiste) {
                $mensagem = "Erro: O email '$email' já pertence a outro usuário!";
            } else {
                // Se não teve nenhum erro, salva o usuário
                $tempArquivoNome = 'usuarios_temp.txt';
                $arquivoOriginal = fopen($nomeArquivo, 'r');
                $arquivoTemp = fopen($tempArquivoNome, 'w');

                if ($arquivoOriginal && $arquivoTemp) {
                    $linhaAtualizada = $id . ";" . $matricula . ";" . $nome . ";" . $email . "\n";
                    while (($linha = fgets($arquivoOriginal)) !== false) {
                        $dados = explode(';', $linha);
                        if (trim($dados[0]) == $id) {
                            fwrite($arquivoTemp, $linhaAtualizada); // Escreve a linha nova
                        } else {
                            fwrite($arquivoTemp, $linha); // Copia a linha antiga
                        }
                    }
                    fclose($arquivoOriginal);
                    fclose($arquivoTemp);
                    unlink($nomeArquivo);
                    rename($tempArquivoNome, $nomeArquivo);
                    
                    $mensagem = "Alterações salvas com sucesso!";
                } else {
                    $mensagem = "Erro ao manipular arquivos no servidor.";
                }
            }
        }
    } else {
        $mensagem = "Erro: Todos os campos são obrigatórios!";
    }
} else {
    $mensagem = "Erro: Dados insuficientes.";
}

// Devolve a mensagem de sucesso ou erro para o JavaScript
echo $mensagem;
?>