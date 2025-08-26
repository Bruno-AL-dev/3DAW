<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Excluir Disciplina</title>
</head>
<body>
    <?php
    $arquivoNome = "disciplinas.txt";
    
    $linhaParaExcluir = $_POST['linha'];
    
    $linhas = file($arquivoNome);
    
    unset($linhas[$linhaParaExcluir]);
    
    file_put_contents($arquivoNome, implode('', $linhas));
    
    echo "Disciplina excluída com sucesso!";
    ?>
    <br>
    <a href="ex04_listarTodasDisciplinas.php">Voltar para a Listagem</a>
</body>
</html>