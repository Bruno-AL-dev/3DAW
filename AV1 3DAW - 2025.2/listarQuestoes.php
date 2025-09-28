<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Listar Perguntas</title>
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

<h1>Lista de Perguntas Cadastradas</h1>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Tipo</th>
            <th>Texto da Pergunta</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
    <?php
    // Mostra a tabela com todas as perguntas cadastradas

    $nomeArquivo = 'questoes.txt';

    // Verifica se o arquivo de questões já existe
    if (file_exists($nomeArquivo)) {
        $arquivo = fopen($nomeArquivo, 'r');
        
        // Se o arquivo foi aberto com sucesso continua
        if ($arquivo) {
            // Lê o arquivo uma linha de cada vez até o final
            while (($linha = fgets($arquivo)) !== false) {
                
                // Pula a linha se ela estiver em branco pra evitar erros
                if ($linha == '') continue;

                // Separa a linha em um vetor usando o ponto e vírgula
                $dados = explode(';', $linha);
                
                // Pega os dados principais pra exibir na tabela
                $id = $dados[0];
                $tipo = $dados[1];
                $textoPergunta = $dados[2];

                // Monta e exibe a linha da tabela com os dados da pergunta
                echo "<tr>";
                echo "<td>$id</td>";
                echo "<td>$tipo</td>";
                echo "<td>$textoPergunta</td>";
                // Cria a célula com os links das ações: Ver, Editar, Excluir
                echo "<td>";
                echo "<a href='listarUmaPergunta.php?id=$id'>Ver Detalhes</a> | ";
                echo "<a href='editarQuestao.php?id=$id'>Editar</a> | "; 
                echo "<a href='excluirQuestao.php?id=$id' onclick='return confirm(\"Tem certeza que deseja excluir esta pergunta e suas respostas?\");'>Excluir</a>";
                echo "</td>";
                echo "</tr>";
            }
            fclose($arquivo);
        }
    } else {
        // Se o arquivo não existe mostra uma mensagem na tabela
        echo "<tr><td colspan='4'>Nenhuma pergunta cadastrada.</td></tr>";
    }
    ?>
    </tbody>
</table>

</body>
</html>