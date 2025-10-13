<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Alterar Pergunta</title>
    <script>
        // Função pra buscar a pergunta quando o usuário clica no botão
        function buscarPergunta() {
            // Pega o ID que o usuário digitou no campo de busca
            let idDaBusca = document.getElementById("id_busca").value;
            if (!idDaBusca) {
                alert("Por favor, digite um ID");
                return;
            }

            // Limpa qualquer mensagem antiga e esconde o formulário de edição
            document.getElementById("msg").innerHTML = "";
            document.getElementById("divEdicao").style.display = 'none';

            // Prepara a chamada
            let requisicao = new XMLHttpRequest();

            // Define o que fazer quando a resposta do PHP chegar
            requisicao.onreadystatechange = function() {
                // Verifica se a chamada deu certo (status 200) e se já terminou (readyState 4)
                if (this.readyState == 4 && this.status == 200) {
                    
                    // Transforma a resposta do PHP em um objeto
                    let resposta = JSON.parse(this.responseText);

                    // Vê se o PHP disse que achou a pergunta
                    if (resposta.resultado === 'sucesso') {
                        let questao = resposta.dados;

                        // Pega os dados que vieram do PHP e coloca nos campos do formulário
                        document.getElementById("id_edit").value = questao.id;
                        document.getElementById("tipo_edit").value = questao.tipo;
                        document.getElementById("pergunta_edit").value = questao.texto;

                        let camposME = document.getElementById("camposME_edit");
                        if (questao.tipo === 'ME') {
                            let radios = document.getElementsByName('resposta_correta_edit');
                            let caixasDeTexto = document.getElementsByName("respostas_edit[]");
                            
                            // Limpa os campos antes de colocar os dados novos
                            caixasDeTexto.forEach(caixa => caixa.value = "");
                            radios.forEach(radio => radio.checked = false);

                            // Preenche as caixas de texto com as respostas
                            for (let i = 0; i < questao.respostas.length; i++) {
                                if (caixasDeTexto[i]) caixasDeTexto[i].value = questao.respostas[i];
                            }
                            // Marca o botão de rádio da resposta correta
                            if (radios[questao.correta]) radios[questao.correta].checked = true;
                            
                            // Mostra os campos de múltipla escolha
                            camposME.style.display = 'block';
                        } else {
                            // Se não for ME, esconde esses campos
                            camposME.style.display = 'none';
                        }
                        
                        // Mostra o formulário de edição que estava escondido
                        document.getElementById("divEdicao").style.display = 'block';
                    } else {
                        // Se o PHP disse que deu erro, mostra a mensagem de erro
                        document.getElementById("msg").innerHTML = '<p style="color: red;">' + resposta.mensagem + '</p>';
                    }
                }
            };
            // Prepara e envia a requisição pra API de busca
            requisicao.open("GET", "api_buscar_questao.php?id=" + idDaBusca, true);
            requisicao.send();
        }

        // Função pra salvar as alterações depois que o usuário editar
        function salvarAlteracoes() {
            // Pega os dados do formulário de edição
            let id = document.getElementById("id_edit").value;
            let tipo = document.getElementById("tipo_edit").value;
            let pergunta = document.getElementById("pergunta_edit").value;
            let parametros = "id=" + id + "&tipo=" + tipo + "&pergunta=" + encodeURIComponent(pergunta);

            // Se for múltipla escolha, adiciona os dados das respostas na URL
            if (tipo === 'ME') {
                let respostas = document.getElementsByName("respostas_edit[]");
                for (let i = 0; i < respostas.length; i++) {
                    parametros += "&respostas[]=" + encodeURIComponent(respostas[i].value);
                }
                let radios = document.getElementsByName('resposta_correta_edit');
                for (let i = 0; i < radios.length; i++) {
                    if (radios[i].checked) {
                        parametros += "&resposta_correta=" + radios[i].value; break;
                    }
                }
            }

            // Prepara outra chamada, agora pra API de edição
            let requisicao = new XMLHttpRequest();
            requisicao.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    // Mostra a mensagem de sucesso do PHP
                    document.getElementById("msg").innerHTML = '<p style="color: green;">' + this.responseText + '</p>';
                    // Esconde o formulário de edição de novo
                    document.getElementById("divEdicao").style.display = 'none';
                    // Limpa o campo de busca
                    document.getElementById("id_busca").value = "";
                }
            };
            requisicao.open("GET", "api_editar_questao.php?" + parametros, true);
            requisicao.send();
        }
    </script>
</head>
<body>
<header>
    <a href="alterarQuestao.php">Alterar Pergunta</a>
</header>

<h1>Alterar Pergunta</h1>

<div id="divBusca">
    <p>Digite o ID da pergunta para alterar:</p>
    <input type="text" id="id_busca" size="30">
    <input type="button" value="Buscar Pergunta" onclick="buscarPergunta();">
</div>

<hr>
<div id="msg"></div>

<div id="divEdicao" style="display: none;">
    <h3>Editando Pergunta...</h3>
    <form>
        <input type="hidden" id="id_edit">
        <input type="hidden" id="tipo_edit">
        <p>
            <label for="pergunta_edit">Texto da Pergunta:</label><br>
            <textarea id="pergunta_edit" rows="4" cols="50"></textarea>
        </p>
        <div id="camposME_edit" style="display: none;">
            <p>
                <label>Opções de Resposta:</label><br>
                <input type="radio" name="resposta_correta_edit" value="0"> <input type="text" name="respostas_edit[]" size="45"><br>
                <input type="radio" name="resposta_correta_edit" value="1"> <input type="text" name="respostas_edit[]" size="45"><br>
                <input type="radio" name="resposta_correta_edit" value="2"> <input type="text" name="respostas_edit[]" size="45"><br>
                <input type="radio" name="resposta_correta_edit" value="3"> <input type="text" name="respostas_edit[]" size="45"><br>
                <input type="radio" name="resposta_correta_edit" value="4"> <input type="text" name="respostas_edit[]" size="45"><br>
            </p>
        </div>
        <p>
            <input type="button" value="Salvar Alterações" onclick="salvarAlteracoes();">
        </p>
    </form>
</div>

</body>
</html>