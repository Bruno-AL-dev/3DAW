<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Criar Novo Usuário</title>
    <script>
        // Função chamada pelo botão "Salvar"
        function salvarUsuario() {
            // Pega os valores dos campos do formulário
            let matricula = document.getElementById("matricula").value;
            let nome = document.getElementById("nome").value;
            let email = document.getElementById("email").value;
            
            // Verifica se o email possui um '@' e um '.'
            if (email.indexOf('@') == -1 || email.indexOf('.') == -1) {
                document.getElementById("msg").innerHTML = "Erro: O formato do email parece inválido (falta '@' ou '.')";
                return; // Para a função aqui
            }

            // Monta a string de parâmetros para a URL
            let parametros = "matricula=" + encodeURIComponent(matricula) + 
                             "&nome=" + encodeURIComponent(nome) + 
                             "&email=" + encodeURIComponent(email);

            // Prepara a chamada
            let requisicao = new XMLHttpRequest();

            // Define o que fazer quando a resposta do PHP chegar
            requisicao.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    // Pega a resposta do PHP e mostra na tela
                    document.getElementById("msg").innerHTML = this.responseText;
                    
                    // Se a resposta foi de sucesso, limpa o formulário
                    if (this.responseText.includes("sucesso")) {
                        document.getElementById("formUsuario").reset();
                    }
                }
            };

            // Prepara e envia a requisição para a API
            requisicao.open("GET", "api_salvar_usuario.php?" + parametros, true);
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

<h1>Criar Novo Usuário</h1>

<p id="msg" style="font-weight: bold;"></p>

<form id="formUsuario">
    <p>
        <label for="matricula">Matrícula:</label><br>
        <input type="text" id="matricula" name="matricula">
    </p>
    <p>
        <label for="nome">Nome:</label><br>
        <input type="text" id="nome" name="nome">
    </p>
    <p>
        <label for="email">Email:</label><br>
        <input type="text" id="email" name="email">
    </p>
    <p>
        <input type="button" value="Salvar Usuário" onclick="salvarUsuario();">
    </p>
</form>

</body>
</html>