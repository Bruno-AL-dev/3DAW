<?php
// Página pra criar uma pergunta de resposta de texto

// Inicia as variáveis pra manter os dados no formulário em caso de erro
$pergunta = "";
$erro = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $pergunta = $_POST['pergunta'];
    
    // O campo não pode estar vazio
    if (!empty($pergunta)) {
        
        $id = uniqid();
        $nomeArquivo = 'questoes.txt';

        // Monta a linha no formato: id;tipo;pergunta;;
        // Os dois últimos campos (respostas e resposta_correta) ficam vazios para o tipo TX
        $linha = $id . ";TX;" . $pergunta . ";;\n";

        $arquivo = fopen($nomeArquivo, 'a');
        fwrite($arquivo, $linha);
        fclose($arquivo);

        // Redireciona pra lista para mostrar que a pergunta foi salva
        header("Location: listarQuestoes.php");
        exit();

    } else {
        $erro = "Erro: O campo da pergunta não pode estar vazio.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Criar Pergunta de Resposta de Texto</title>
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

<h1>Criar Nova Pergunta - Resposta de Texto</h1>

<?php
// Se a variável $erro tiver algum texto, mostra na tela
if (!empty($erro)) {
    echo '<p style="color: red;">' . $erro . '</p>';
}
?>

<form action="criarQuestaoTX.php" method="POST">
    <p>
        <label for="pergunta">Texto da Pergunta:</label><br>
        <textarea id="pergunta" name="pergunta" rows="4" cols="50"><?php echo $pergunta; ?></textarea>
    </p>
    <p>
        <button type="submit">Salvar Pergunta</button>
    </p>
</form>

</body>
</html>