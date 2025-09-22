<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Lista de Usuários</title>
</head>
<body>
    <h2>Usuários Cadastrados</h2>
    <p><a href="criarUsuario.php">Adicionar Novo Usuário</a></p>

    <table>
        <thead>
            <tr>
                <th>Matricula</th>
                <th>Nome</th>
                <th>Email</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $arqUsuarios = 'usuarios.txt';

            if (file_exists($arqUsuarios) && filesize($arqUsuarios) > 0) {

                $linhas = file($arqUsuarios, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

                foreach ($linhas as $linha) {

                    list($id, $matricula, $nome, $email) = explode(';', $linha);
                    
                    echo "<tr>";
                    echo "<td>" . $matricula . "</td>";
                    echo "<td>" . $nome . "</td>";
                    echo "<td>" . $email . "</td>";
                    echo "<td>";
                    echo "<a href='editarUsuario.php?id=" . urlencode($id) . "'>Editar</a> ";
                    echo "<a href='excluirUsuario.php?id=" . urlencode($id) . "' onclick='return confirm(\"Tem certeza?\");'>Excluir</a>";
                    echo "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='3'>Nenhum usuário cadastrado.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</body>
</html>