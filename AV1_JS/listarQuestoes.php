<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Listar Perguntas</title>
    <script>
        // Função pra excluir a pergunta
        function excluirQuestao(id) {
            // Pede a confirmação do usuário
            if (confirm("Tem certeza que deseja excluir essa pergunta?")) {
                
                let requisicao = new XMLHttpRequest();

                // Define o que fazer quando a resposta do PHP chegar
                requisicao.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                        // 1. Mostra a mensagem do PHP na tela
                        document.getElementById("msg").innerHTML = this.responseText;
                        
                        // 2. Se a resposta foi de sucesso, remove a linha da tabela
                        if (this.responseText.includes("sucesso")) {
                            document.getElementById("questao-" + id).remove();
                        }
                    }
                };

                // Prepara e envia a requisição para a API
                requisicao.open("GET", "api_excluir_questao.php?id=" + id, true);
                requisicao.send();
            }
        }
    </script>
</head>
<body>
<header>
    <h3>Sistema de Jogo Corporativo</h3>
    <nav>
        <strong>Usuários:</strong>
        <a href="listarUsuarios.php">Listar</a> | 
        <a href="criarUsuario.php">Criar Novo</a>
        <br>
        <strong>Perguntas:</strong>
        <a href="listarQuestoes.php">Listar</a> | 
        <a href="criarQuestaoME.php">Criar (M.E.)</a> | 
        <a href="criarQuestaoTX.php">Criar (Texto)</a> |
    </nav>
    <hr>
</header>

<h1>Lista de Perguntas Cadastradas</h1>

<p id="msg" style="color: green; font-weight: bold;"></p>

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
    $nomeArquivo = 'questoes.txt';

    if (file_exists($nomeArquivo)) {
        $arquivo = fopen($nomeArquivo, 'r');
        if ($arquivo) {
            while (($linha = fgets($arquivo)) !== false) {
                if (trim($linha) == '') continue;

                $dados = explode(';', $linha);
                $id = trim($dados[0]);
                $tipo = trim($dados[1]);
                $textoPergunta = trim($dados[2]);

                echo "<tr id='questao-$id'>";
                echo "<td>$id</td>";
                echo "<td>$tipo</td>";
                echo "<td>$textoPergunta</td>";
                echo "<td>";
                echo "<a href='editarQuestao.php?id=$id'>Editar</a> | "; 
                echo "<a href='#' onclick='excluirQuestao(\"$id\"); return false;'>Excluir</a>";
                echo "</td>";
                echo "</tr>";
            }
            fclose($arquivo);
        }
    } else {
        echo "<tr><td colspan='4'>Nenhuma pergunta cadastrada.</td></tr>";
    }
    ?>
    </tbody>
</table>

</body>
</html>