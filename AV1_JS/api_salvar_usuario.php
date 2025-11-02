<?php
// API que recebe dados do formulário e salva um novo usuário no arquivo de texto

$nomeArquivo = 'usuarios.txt';
$erro = "";

// Verifica se os dados foram enviados
if (isset($_GET['matricula'])) {
    $matricula = $_GET['matricula'];
    $nome = $_GET['nome'];
    $email = $_GET['email'];
    $matriculaJaExiste = false;
    $emailJaExiste = false;

    // Validação pra campos vazios
    if (!empty($matricula) && !empty($nome) && !empty($email)) {
        
        // 1. Validação de formato de email
        if (strpos($email, '@') === false || strpos($email, '.') === false) {
            $erro = "Erro: O formato do email parece inválido (falta '@' ou '.')";
        } else {
            // Se o formato estiver ok, vê se tem duplicados
            
            // Validação de Matrícula e Email duplicados
            if (file_exists($nomeArquivo)) {
                $arquivoLeitura = fopen($nomeArquivo, 'r');
                while (($linha = fgets($arquivoLeitura)) !== false) {
                    $dados = explode(';', $linha);
                    
                    $matriculaExistente = trim($dados[1]);
                    $emailExistente = trim($dados[3]); // Pega o email da linha

                    if ($matriculaExistente == $matricula) {
                        $matriculaJaExiste = true;
                    }
                    if ($emailExistente == $email) { // Checa se o email é igual
                        $emailJaExiste = true;
                    }

                    // Se já achou qualquer um dos dois, pode parar o loop
                    if ($matriculaJaExiste || $emailJaExiste) {
                        break;
                    }
                }
                fclose($arquivoLeitura);
            }

            // Checagem de erros
            if ($matriculaJaExiste) {
                $erro = "Erro: A matrícula '$matricula' já está cadastrada!";
            } else if ($emailJaExiste) {
                $erro = "Erro: O email '$email' já está cadastrado!";
            } else {
                // Se não teve nenhum erro, salva o usuário
                $id = uniqid();
                $linha = $id . ";" . $matricula . ";" . $nome . ";" . $email . "\n";

                $arquivoEscrita = fopen($nomeArquivo, 'a');
                fwrite($arquivoEscrita, $linha);
                fclose($arquivoEscrita);
                
                echo "Usuário cadastrado com sucesso!";
                exit(); // Para a execução com a mensagem de sucesso
            }
        }
    } else {
        $erro = "Todos os campos são obrigatórios!";
    }

    // Se chegou aqui, é porque deu algum erro de validação
    echo $erro;

} else {
    echo "Erro: Dados insuficientes.";
}
?>