<?php
// Carrega os dados do usuário com PHP

// Carregamento dos dados com PHP
$nomeArquivo = 'usuarios.txt';
$usuarioEncontrado = null;

// Pega o ID que veio da URL
$idParaEditar = isset($_GET['id']) ? $_GET['id'] : null;

if ($idParaEditar && file_exists($nomeArquivo)) {
    $arquivo = fopen($nomeArquivo, 'r');
    while (($linha = fgets($arquivo)) !== false) {
        $dados = explode(';', $linha);
        if (trim($dados[0]) == $idParaEditar) {
            // Achou, então guarda os dados pra usar no formulário
            $usuarioEncontrado = [
                'id' => trim($dados[0]), 
                'matricula' => trim($dados[1]), 
                'nome' => trim($dados[2]),
                'email' => trim($dados[3])
            ];
            break;
        }
    }
    fclose($arquivo);
}

// Se não encontrou o usuário, para a execução
if ($usuarioEncontrado == null) {
    die("Erro: Usuário não encontrado ou ID inválido. <a href='listarUsuarios.php'>Voltar</a>");
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Editar Usuário</title>
    <script>
        // Função pra salvar com javascript
        function salvarAlteracoes() {
            // Pega os dados dos campos do formulário
            let id = document.getElementById("id_edit").value;
            let matricula = document.getElementById("matricula").value;
            let nome = document.getElementById("nome").value;
            let email = document.getElementById("email").value;

            // Verifica se o email possui um '@' e um '.'
            if (email.indexOf('@') == -1 || email.indexOf('.') == -1) {
                document.getElementById("msg").innerHTML = "Erro: O formato do email parece inválido (falta '@' ou '.')";
                return; // Para a função aqui
            }

            // Monta a string de parâmetros para a URL
            let parametros = "id=" + id + 
                             "&matricula=" + encodeURIComponent(matricula) +
                             "&nome=" + encodeURIComponent(nome) +
                             "&email=" + encodeURIComponent(email);

            // Faz a chamada para a API de edição
            let requisicao = new XMLHttpRequest();
            requisicao.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    // Mostra a mensagem de sucesso ou erro do PHP
                    document.getElementById("msg").innerHTML = this.responseText;
                } else if (this.readyState == 4) {
                    // Mostra um erro se a chamada falhar
                    document.getElementById("msg").innerHTML = "Erro ao salvar: " + this.status;
                }
            };
            requisicao.open("GET", "api_editar_usuario.php?" + parametros, true);
            requisicao.send();
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

<h1>Editando Usuário (ID: <?php echo $usuarioEncontrado['id']; ?>)</h1>

<div id="msg" style="font-weight: bold;"></div>

<form id="formEdicao">
    <input type="hidden" id="id_edit" value="<?php echo $usuarioEncontrado['id']; ?>">
    <p>
        <label for="matricula">Matrícula:</label><br>
        <input type="text" id="matricula" value="<?php echo $usuarioEncontrado['matricula']; ?>">
    </p>
    <p>
        <label for="nome">Nome:</label><br>
        <input type="text" id="nome" value="<?php echo $usuarioEncontrado['nome']; ?>">
    </p>
    <p>
        <label for="email">Email:</label><br>
        <input type="text" id="email" value="<?php echo $usuarioEncontrado['email']; ?>">
    </p>
    <p>
        <input type="button" value="Salvar Alterações" onclick="salvarAlteracoes();">
    </p>
</form>
<br>
<a href="listarUsuarios.php">Voltar para a lista</a>

</body>
</html>