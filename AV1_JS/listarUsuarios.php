<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Listar Usuários</title>
    <script>
        // Função pra excluir o usuário
        function excluirUsuario(id) {
            // Pede a confirmação antes de apagar
            if (confirm("Tem certeza que deseja excluir este usuário?")) {
                
                // Prepara a chamada
                let requisicao = new XMLHttpRequest();

                // Define o que fazer quando a resposta do PHP chegar
                requisicao.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                        // Mostra a mensagem do PHP
                        document.getElementById("msg").innerHTML = this.responseText;
                        
                        // Se a exclusão deu certo, remove a linha da tabela na tela
                        if (this.responseText.includes("sucesso")) {
                            document.getElementById("usuario-" + id).remove();
                        }
                    }
                };

                // Prepara e envia a requisição para a API
                requisicao.open("GET", "api_excluir_usuario.php?id=" + id, true);
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

<h1>Lista de Usuários Cadastrados</h1>

<p id="msg" style="color: green; font-weight: bold;"></p>

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
    $nomeArquivo = 'usuarios.txt';

    if (file_exists($nomeArquivo)) {
        $arquivo = fopen($nomeArquivo, 'r');
        if ($arquivo) {
            while (($linha = fgets($arquivo)) !== false) {
                if (trim($linha) == '') continue;

                $dados = explode(';', $linha);
                $id = trim($dados[0]);
                $matricula = trim($dados[1]);
                $nome = trim($dados[2]);
                $email = trim($dados[3]);

                // Adiciona um ID único na linha da tabela pra poder apagar com o javascript
                echo "<tr id='usuario-$id'>";
                echo "<td>$matricula</td>";
                echo "<td>$nome</td>";
                echo "<td>$email</td>";
                echo "<td>";
                echo "<a href='editarUsuario.php?id=$id'>Editar</a> | "; 
                echo "<a href='#' onclick='excluirUsuario(\"$id\"); return false;'>Excluir</a>";
                echo "</td>";
                echo "</tr>";
            }
            fclose($arquivo);
        }
    } else {
        echo "<tr><td colspan='4'>Nenhum usuário cadastrado.</td></tr>";
    }
    ?>
    </tbody>
</table>

</body>
</html>