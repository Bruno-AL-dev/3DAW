<?php
// Responsável por excluir uma pergunta do arquivo questoes.txt

$nomeArquivo = 'questoes.txt';

// O script só executa a lógica de exclusão se um ID for passado pela URL
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $idParaExcluir = $_GET['id'];

    // Verifica se o arquivo existe pra não dar erro
    if (file_exists($nomeArquivo)) {
        
        // Cria um arquivo temporário pra remover a linha da pergunta
        $tempArquivoNome = 'questoes_temp.txt';

        // Abre o arquivo original pra leitura e o temporário pra escrita
        $arquivoOriginal = fopen($nomeArquivo, 'r');
        $arquivoTemp = fopen($tempArquivoNome, 'w');

        if ($arquivoOriginal && $arquivoTemp) {
            
            // Lê o arquivo original linha por linha
            while (($linha = fgets($arquivoOriginal)) !== false) {
                $dados = explode(';', $linha);
                
                // Se o ID da linha for diferente do ID a ser excluído copia a linha pro arquivo temporário
                if ($dados[0] != $idParaExcluir) {
                    fwrite($arquivoTemp, $linha);
                }
                // Se o ID for igual, a linha é ignorada
            }
            
            // Fecha os arquivos pra salvar as alterações
            fclose($arquivoOriginal);
            fclose($arquivoTemp);

            // Substitui o arquivo antigo pelo novo, já sem a linha da pergunta
            unlink($nomeArquivo);
            rename($tempArquivoNome, $nomeArquivo);
        }
    }
}

// Redireciona para a lista de questões pra mostrar o resultado.
header("Location: listarQuestoes.php");
exit();

?>