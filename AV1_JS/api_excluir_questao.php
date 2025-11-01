<?php
// API que recebe um ID e remove a pergunta do arquivo de texto

$nomeArquivo = 'questoes.txt';
$mensagem = "Erro: ID não fornecido ou inválido"; // Mensagem padrão de erro

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $idParaExcluir = $_GET['id'];

    if (file_exists($nomeArquivo)) {
        $tempArquivoNome = 'questoes_temp.txt';
        $arquivoOriginal = fopen($nomeArquivo, 'r');
        $arquivoTemp = fopen($tempArquivoNome, 'w');

        if ($arquivoOriginal && $arquivoTemp) {
            // Lê o arquivo original linha por linha
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

            // Substitui o arquivo antigo pelo novo
            unlink($nomeArquivo);
            rename($tempArquivoNome, $nomeArquivo);
            
            $mensagem = "Pergunta (ID: $idParaExcluir) foi excluída com sucesso!";
        } else {
            $mensagem = "Erro: Não foi possível manipular os arquivos no servidor";
        }
    } else {
        $mensagem = "Erro: Arquivo de questões não encontrado";
    }
}

// Devolve a mensagem final como resposta de texto para o JavaScript
echo $mensagem;
exit();
?>