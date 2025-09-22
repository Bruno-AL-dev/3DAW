<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $arqUsuarios = 'usuarios.txt';
    
    $matricula = $_POST['matricula'];
    $nome = $_POST['nome'];
    $email = $_POST['email'];

    $id = uniqid();
    
    $linha = $id . ';'. $matricula . ';' . $nome . ';' . $email . PHP_EOL;

    file_put_contents($arqUsuarios, $linha, FILE_APPEND);
    
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Adicionar Usuário</title>
</head>
<body>
    <h2>Adicionar Novo Usuário</h2>
    <form action="criarUsuario.php" method="POST">
        <p>
            <label>Matricula:</label><br>
            <input type="text" name="matricula" required>
        </p>
        <p>
            <label>Nome:</label><br>
            <input type="text" name="nome" required>
        </p>
        <p>
            <label>Email:</label><br>
            <input type="email" name="email" required>
        </p>
        <p>
            <button type="submit">Salvar</button>
        </p>
    </form>
    <a href="index.php">Voltar para a lista</a>
</body>
</html>