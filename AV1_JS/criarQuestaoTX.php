<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Criar Pergunta de Resposta de Texto</title>
    <script>
        // Função chamada pelo botão 'Salvar Pergunta'
        function salvarPerguntaTX() {
            // Pega o texto da pergunta e monta a string
            let pergunta = document.getElementById("pergunta").value;
            let params = "pergunta=" + encodeURIComponent(pergunta) + "&tipo=TX";

            // Inicia a chamada
            let xmlhttp = new XMLHttpRequest();
            
            // Define o que fazer quando a resposta do servidor chegar
            xmlhttp.onreadystatechange = function() {
                // Se a chamada foi concluída e bem-sucedida
                if (this.readyState == 4 && this.status == 200) {
                    // Mostra a resposta do PHP na página
                    document.getElementById("msg").innerHTML = this.responseText;
                    
                    // Se deu certo, limpa o formulário
                    if (this.responseText.includes("sucesso")) {
                        document.getElementById("formPergunta").reset();
                    }
                }
            }

            // Prepara e envia a requisição para a API
            xmlhttp.open("GET", "api_salvar_questao.php?" + params, true);
            xmlhttp.send();
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

<h1>Criar Nova Pergunta - Resposta de Texto</h1>

<p id="msg" style="font-weight: bold;"></p>

<form id="formPergunta">
    <p>
        <label for="pergunta">Texto da Pergunta:</label><br>
        <textarea id="pergunta" name="pergunta" rows="4" cols="50"></textarea>
    </p>
    <p>
        <input type="button" value="Salvar Pergunta" onclick="salvarPerguntaTX();">
    </p>
</form>
</body>
</html>