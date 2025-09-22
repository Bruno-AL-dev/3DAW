<?php
$arqUsuarios = 'usuarios.txt';
$idParaEditar = $_GET['id'];
$usuario = null;

// Aqui salva a alteração quando o formulário é enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $matricula = $_POST['matricula'];
    $nome = $_POST['nome'];
    $email = $_POST['email'];

    $linhas = file($arqUsuarios, FILE_IGNORE_NEW_LINES);
    for ($i = 0; $i < count($linhas); $i++) {
        $dados = explode(';', $linhas[$i]);
        if ($dados[0] == $id) {
            $linhas[$i] = $id . ';' . $matricula . ';' . $nome . ';' . $email;
            break;
        }
    }
    file_put_contents($arqUsuarios, implode(PHP_EOL, $linhas));
    header('Location: index.php');
    exit;
}

// Aqui encontra o usuário e exibe no formulário pra editar
$linhas = file($arqUsuarios, FILE_IGNORE_NEW_LINES);
foreach ($linhas as $linha) {
    $dados = explode(';', $linha);
    if ($dados[0] == $idParaEditar) {
        $usuario = ['id' => $dados[0], 'matricula' => $dados[1], 'nome' => $dados[2], 'email' => $dados[3]];
        break;
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Editar Usuário</title>
</head>
<body>
    <h2>Editar Usuário</h2>
    <?php if ($usuario): ?>
    <form action="editarUsuario.php" method="POST">
        <input type="hidden" name="id" value="<?= $usuario['id'] ?>">
        <p>
            <label>Matricula:</label><br>
            <input type="text" name="matricula" value="<?= $usuario['matricula'] ?>" required>
        </p>
        <p>
            <label>Nome:</label><br>
            <input type="text" name="nome" value="<?= $usuario['nome'] ?>" required>
        </p>
        <p>
            <label>Email:</label><br>
            <input type="email" name="email" value="<?= $usuario['email'] ?>" required>
        </p>
        <p>
            <button type="submit">Atualizar</button>
        </p>
    </form>
    <?php else: ?>
        <p>Usuário não encontrado.</p>
    <?php endif; ?>
    <a href="index.php">Voltar para a lista</a>
</body>
</html>