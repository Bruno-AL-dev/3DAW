<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Gerenciamento de Usuários</title>
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

<h1>Lista de Usuários</h1>

<table>
    <thead>
        <tr>
            <th>Matrícula</th>
            <th>Nome</th>
            <th>Email</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
    <?php
    // Exibe a lista de usuários cadastrados

    $nomeArquivo = 'usuarios.txt';

    // Verifica se o arquivo de usuários existe antes de tentar abrir
    if (file_exists($nomeArquivo)) {
        
        // Abre o arquivo pra leitura
        $arquivo = fopen($nomeArquivo, 'r');
        
        // Lê o arquivo uma linha de cada vez
        while (($linha = fgets($arquivo)) !== false) {
            
            // Remove os espaços e quebras de linha do começo e do final
            $linhaLimpa = trim($linha);
            
            // Se a linha não estiver vazia processa os dados
            if ($linhaLimpa != '') {
                // Separa a linha em um vetor e atribui cada parte a uma variável
                list($id, $matricula, $nome, $email) = explode(';', $linhaLimpa);

                // Monta a linha da tabela com os dados do usuário
                echo "<tr>";
                echo "<td>" . $matricula . "</td>";
                echo "<td>" . $nome . "</td>";
                echo "<td>" . $email . "</td>";
                // Adiciona os links de Editar e Excluir passando o ID do usuário na URL
                echo "<td><a href='editarUsuario.php?id=$id'>Editar</a> | <a href='excluirUsuario.php?id=$id'>Excluir</a></td>";
                echo "</tr>";
            }
        }
        
        fclose($arquivo);

    } else {
        // Se o arquivo não existir exibe uma mensagem
        echo "<tr><td colspan='4'>Nenhum usuário cadastrado.</td></tr>";
    }
    ?>
    </tbody>
</table>

</body>
</html>