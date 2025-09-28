<?php
// Mostra os detalhes de uma única pergunta.

$nomeArquivo = 'questoes.txt';
$questaoEncontrada = null;

// Verifica se um ID de pergunta foi passado pela URL
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $idParaVer = $_GET['id'];

    // Procura a pergunta no arquivo se ele existir
    if (file_exists($nomeArquivo)) {
        $arquivo = fopen($nomeArquivo, 'r');
        if ($arquivo) {
            // Lê o arquivo linha por linha
            while (($linha = fgets($arquivo)) !== false) {
                $dados = explode(';', $linha);

                // Compara o ID da linha com o ID que foi passado
                if ($dados[0] == $idParaVer) {

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
    <title>Detalhes da Pergunta</title>
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

<h1>Detalhes da Pergunta</h1>

<p><strong>ID:</strong> <?php echo $questaoEncontrada['id']; ?></p>
<p><strong>Tipo:</strong> <?php echo $questaoEncontrada['tipo']; ?></p>

<h3>Pergunta:</h3>
<p><?php echo $questaoEncontrada['texto']; ?></p>

<?php if ($questaoEncontrada['tipo'] == 'ME'): ?>
    <h4>Opções de Resposta:</h4>
    <ul>
        <?php foreach ($questaoEncontrada['respostas'] as $indice => $resposta): ?>
            <li>
                <?php 
                echo $resposta;
                if ($indice == $questaoEncontrada['correta']) {
                    echo " <strong>(Correta)</strong>";
                }
                ?>
            </li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <p><em>(Esta é uma pergunta de resposta de texto, não possui opções pré-definidas.)</em></p>
<?php endif; ?>

<br>
<p><a href="listarQuestoes.php">Voltar para a lista de perguntas</a></p>

</body>
</html>