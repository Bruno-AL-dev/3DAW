<?php
// API que recebe um ID e remove o usuário do arquivo usuarios.txt

$nomeArquivo = 'usuarios.txt';
$mensagem = "Erro: ID do usuário não foi fornecido";

// Verifica se o JavaScript mandou um ID na URL
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $idParaExcluir = $_GET['id'];

    if (file_exists($nomeArquivo)) {
        // Usa um arquivo temporário pra remover a linha
        $tempArquivoNome = 'usuarios_temp.txt';
        $arquivoOriginal = fopen($nomeArquivo, 'r');
        $arquivoTemp = fopen($tempArquivoNome, 'w');

        if ($arquivoOriginal && $arquivoTemp) {
            // Lê o arquivo linha por linha
            while (($linha = fgets($arquivoOriginal)) !== false) {
                $dados = explode(';', $linha);
                // Se o ID da linha for diferente do que é pra excluir,
                // copia a linha para o arquivo temporário
                if (trim($dados[0]) != $idParaExcluir) {
                    fwrite($arquivoTemp, $linha);
                }
                // Se for igual, a linha não é copiada
            }

            fclose($arquivoOriginal);
            fclose($arquivoTemp);

            // Apaga o arquivo antigo e renomeia o novo
            unlink($nomeArquivo);
            rename($tempArquivoNome, $nomeArquivo);
            
            $mensagem = "Usuário (ID: $idParaExcluir) foi excluído com sucesso!";
        } else {
            $mensagem = "Erro ao manipular os arquivos no servidor";
        }
    } else {
        $mensagem = "Erro: Arquivo de usuários não encontrado";
    }
}

// Devolve a mensagem final como resposta de texto para o JavaScript
echo $mensagem;
exit();
?>