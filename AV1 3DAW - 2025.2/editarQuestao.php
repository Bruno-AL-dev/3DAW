<?php
// Carrega os dados de uma pergunta pra edição e salva as alterações
$nomeArquivo = 'questoes.txt';
$erro = "";
$questaoEncontrada = null;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $tipo = $_POST['tipo'];
    $pergunta = $_POST['pergunta'];

    // Verifica e prepara a linha dependendo do tipo da questão
    if ($tipo == 'ME') {
        // Se for de múltipla escolha pega também os dados das respostas
        $respostas = $_POST['respostas'];
        $resposta_correta = isset($_POST['resposta_correta']) ? $_POST['resposta_correta'] : null;
        
        $respostasPreenchidas = array_filter($respostas);

        if (!empty($pergunta) && count($respostasPreenchidas) >= 2 && $resposta_correta !== null && !empty($respostas[$resposta_correta])) {
            $respostasString = implode("|", $respostasPreenchidas);
            $linhaAtualizada = $id . ";ME;" . $pergunta . ";" . $respostasString . ";" . $resposta_correta . "\n";
        } else {
            $erro = "Para perguntas de Múltipla Escolha, preencha a pergunta, pelo menos duas respostas e marque a correta.";
            // Guarda os dados digitados pra mostrar no formulário novamente
            $questaoEncontrada = ['id' => $id, 'tipo' => $tipo, 'texto' => $pergunta, 'respostas' => $respostas, 'correta' => $resposta_correta];
        }
    } 
    else if ($tipo == 'TX') {
        if (!empty($pergunta)) {
            $linhaAtualizada = $id . ";TX;" . $pergunta . ";;\n";
        } else {
            $erro = "O campo da pergunta não pode estar vazio.";
            $questaoEncontrada = ['id' => $id, 'tipo' => $tipo, 'texto' => $pergunta, 'respostas' => [], 'correta' => null];
        }
    }

    // Se não teve nenhum erro salva no arquivo
    if (empty($erro)) {
        // Cria um arquivo temporário pra editar a linha correta
        $tempArquivoNome = 'questoes_temp.txt';
        $arquivoOriginal = fopen($nomeArquivo, 'r');
        $arquivoTemp = fopen($tempArquivoNome, 'w');

        if ($arquivoOriginal && $arquivoTemp) {
            // Lê o arquivo original e copia para o temporário
            while (($linha = fgets($arquivoOriginal)) !== false) {
                $dados = explode(';', $linha);
                // Quando achar a linha que vai ser editada, escreve a nova versão
                if ($dados[0] == $id) {
                    fwrite($arquivoTemp, $linhaAtualizada);
                } else {
                    // Em todas as outras só copia a linha antiga
                    fwrite($arquivoTemp, $linha);
                }
            }
            fclose($arquivoOriginal);
            fclose($arquivoTemp);

            // Substitui o arquivo antigo pelo novo
            unlink($nomeArquivo);
            rename($tempArquivoNome, $nomeArquivo);

            // Redireciona pra lista para mostrar que a pergunta foi salva
            header("Location: listarQuestoes.php");
            exit();
        }
    }
} else {
    // Se não for POST entra no modo de carregar os dados pro formulário
    $idParaEditar = $_GET['id'];
    if (file_exists($nomeArquivo) && !empty($idParaEditar)) {
        $arquivo = fopen($nomeArquivo, 'r');
        while (($linha = fgets($arquivo)) !== false) {
            $dados = explode(';', $linha);
            // Procura pela linha com o ID para editar
            if ($dados[0] == $idParaEditar) {
                // Achou, então guarda os dados da pergunta
                $questaoEncontrada = [
                    'id' => $dados[0],
                    'tipo' => $dados[1],
                    'texto' => $dados[2]
                ];
                // Se for de múltipla escolha processa também as respostas
                if ($dados[1] == 'ME') {
                    $questaoEncontrada['respostas'] = explode('|', $dados[3]);
                    $questaoEncontrada['correta'] = (int)$dados[4];
                }

                // Para o loop porque já encontrou
                break;
            }
        }
        fclose($arquivo);
    }
}

// Se depois de procurar no arquivo todo a variável continuar nula o ID não existe
if ($questaoEncontrada == null) {
    die("Erro: Questão não encontrada ou ID inválido.");
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Editar Pergunta</title>
</head>
<body>

<header>
    <h3>Sistema de Jogo Corporativo</h3>
    <nav>
        <strong>Usuários:</strong>
        <a href="index.php">Listar</a> | 
        <a href="criarUsuario.php">Criar Novo</a>
        
        <br> <strong>Perguntas:</strong>
        <a href="listarQuestoes.php">Listar</a> | 
        <a href="criarQuestaoME.php">Criar (M.E.)</a> | 
        <a href="criarQuestaoTX.php">Criar (Texto)</a>
    </nav>
    <hr>
</header>

<h1>Editar Pergunta</h1>

<?php
if (!empty($erro)) {
    echo '<p style="color: red;">' . $erro . '</p>';
}
?>

<form action="editarQuestao.php" method="POST">
    <input type="hidden" name="id" value="<?php echo $questaoEncontrada['id']; ?>">
    <input type="hidden" name="tipo" value="<?php echo $questaoEncontrada['tipo']; ?>">

    <p>
        <label for="pergunta">Texto da Pergunta:</label><br>
        <textarea id="pergunta" name="pergunta" rows="4" cols="50"><?php echo $questaoEncontrada['texto']; ?></textarea>
    </p>

    <?php if ($questaoEncontrada['tipo'] == 'ME'): ?>
    <p>
        <label>Opções de Resposta (marque a opção correta):</label><br>
        <?php 
        // Cria 5 campos de resposta e preenche com os dados que já existem
        for ($i = 0; $i < 5; $i++):
            // Por padrão a caixa fica vazia.
            $resposta = '';
            if (isset($questaoEncontrada['respostas'][$i])) {
                // Se o vetor de respostas tiver algum dado no índice $i, usa ele
                $resposta = $questaoEncontrada['respostas'][$i];
            }

            // Por padrão o botão de rádio não vem marcado
            $checked = ''; 
            if (isset($questaoEncontrada['correta']) && $questaoEncontrada['correta'] == $i) {
                // Se nesse índice $i do vetor estiver a resposta certa a variável fica marcada
                $checked = 'checked';
            }
        ?>
            <input type="radio" name="resposta_correta" value="<?php echo $i; ?>" <?php echo $checked; ?>> 
            <input type="text" name="respostas[]" value="<?php echo $resposta; ?>"><br>
        <?php endfor; ?>
    </p>
    <?php endif; ?>

    <p>
        <button type="submit">Salvar Alterações</button>
    </p>
</form>

</body>
</html>