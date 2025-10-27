<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Criar Pergunta de Múltipla Escolha</title>
    <script>
        // Função chamada pelo botão Salvar Pergunta
        function salvarPerguntaME() {
            // Pega os valores dos campos do formulário
            let pergunta = document.getElementById("pergunta").value;
            let respostas = document.getElementsByName("respostas[]");
            let radios = document.getElementsByName('resposta_correta');
            let resposta_correta = null;

            // Monta a string de que vai ser enviada na URL para a API
            let params = "pergunta=" + encodeURIComponent(pergunta) + "&tipo=ME";
            
            // Loop para adicionar todas as respostas ao final da string
            for (let i = 0; i < respostas.length; i++) {
                params += "&respostas[]=" + encodeURIComponent(respostas[i].value);
            }
            // Loop para procurar qual botão de rádio foi marcado e adicionar seu valor
            for (let i = 0; i < radios.length; i++) {
                if (radios[i].checked) {
                    resposta_correta = radios[i].value;
                    params += "&resposta_correta=" + resposta_correta;
                    break;
                }
            }

            // Inicia o processo de chamada
            let xmlhttp = new XMLHttpRequest();

            // Define o que fazer quando a resposta do servidor chegar
            xmlhttp.onreadystatechange = function() {
                // Se a chamada foi concluída (readyState 4) e bem-sucedida (status 200)
                if (this.readyState == 4 && this.status == 200) {
                    // Pega a mensagem de resposta do PHP e mostra na página
                    document.getElementById("msg").innerHTML = this.responseText;
                    
                    // Se a resposta do PHP contém a palavra "sucesso", limpa o formulário
                    if (this.responseText.includes("sucesso")) {
                        document.getElementById("formPergunta").reset();
                    }
                }
            }

            // Prepara a chamada: método GET, para a API, passando os parâmetros
            xmlhttp.open("GET", "api_salvar_questao.php?" + params, true);
            
            // Envia a requisição para o servidor
            xmlhttp.send();
        }
    </script>
</head>
<body>
<header>
    <h3>Sistema de Jogo Corporativo</h3>
    <nav>
        <a href="index.php">Gerenciar Usuários</a> | 
        <a href="criarQuestaoME.php">Criar Pergunta (M.E.)</a> | 
        <a href="criarQuestaoTX.php">Criar Pergunta (Texto)</a> |
        <a href="listarQuestoes.php">Listar Perguntas</a>
    </nav>
    <hr>
</header>
<h1>Criar Nova Pergunta - Múltipla Escolha</h1>

<p id="msg" style="font-weight: bold;"></p>

<form id="formPergunta">
    <p>
        <label for="pergunta">Texto da Pergunta:</label><br>
        <textarea id="pergunta" name="pergunta" rows="4" cols="50"></textarea>
    </p>
    <p>
        <label>Opções de Resposta (marque a opção correta):</label><br>
        <input type="radio" name="resposta_correta" value="0"> <input type="text" name="respostas[]" size="45"><br>
        <input type="radio" name="resposta_correta" value="1"> <input type="text" name="respostas[]" size="45"><br>
        <input type="radio" name="resposta_correta" value="2"> <input type="text" name="respostas[]" size="45"><br>
        <input type="radio" name="resposta_correta" value="3"> <input type="text" name="respostas[]" size="45"><br>
        <input type="radio" name="resposta_correta" value="4"> <input type="text" name="respostas[]" size="45"><br>
    </p>
    <p>
        <input type="button" value="Salvar Pergunta" onclick="salvarPerguntaME();">
    </p>
</form>
</body>
</html>