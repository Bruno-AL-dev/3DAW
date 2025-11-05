// FUNÇÕES GLOBAIS

// Roda essa função assim que qualquer página terminar de carregar
window.onload = function() {
    
    // Verifica se está na página de listar perguntas
    // (procurando pelo id do corpo da tabela)
    let corpoTabelaPerguntas = document.getElementById("corpo-tabela-perguntas");
    if (corpoTabelaPerguntas) {
        // Se achou a tabela de perguntas, chama a função pra carregar os dados
        carregarPerguntas();
    }

    // Verifica se está na página de editar pergunta
    let formEdicao = document.getElementById("formEdicao");
    if (formEdicao) {
        carregarDadosEdicaoPergunta();
    }
};

// FUNÇÕES DO MÓDULO DE PERGUNTAS

// Função 1: Carrega a tabela de perguntas que foi chamada pelo window.onload
function carregarPerguntas() {
    let requisicao = new XMLHttpRequest();
    requisicao.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            let resposta = JSON.parse(this.responseText);
            let corpoTabela = document.getElementById("corpo-tabela-perguntas");
            corpoTabela.innerHTML = ""; // Limpa o "Carregando..."

            if (resposta.resultado === 'sucesso') {                
                for (let i = 0; i < resposta.dados.length; i++) {
                    let pergunta = resposta.dados[i];
                    
                    let id = pergunta.id;
                    let tipo = pergunta.tipo;
                    let textoPergunta = pergunta.pergunta;

                    // Monta o HTML da linha
                    let linhaHTML = "<tr id='questao-" + id + "'>" +
                                      "<td>" + id + "</td>" +
                                      "<td>" + tipo + "</td>" +
                                      "<td>" + textoPergunta + "</td>" +
                                      "<td>" +
                                        "<a href='editarQuestao.html?id=" + id + "'>Editar</a> | " +
                                        "<a href='#' onclick='excluirQuestao(\"" + id + "\"); return false;'>Excluir</a>" +
                                      "</td>" +
                                    "</tr>";

                    // Adiciona a linha na tabela
                    corpoTabela.innerHTML += linhaHTML;
                }
            } else {
                corpoTabela.innerHTML = "<tr><td colspan='4'>Erro ao carregar perguntas.</td></tr>";
            }
        }
    };
    requisicao.open("GET", "api_listar_questoes.php", true);
    requisicao.send();
}

// Função 2: Exclui a pergunta (chamada pelo link "Excluir")
function excluirQuestao(id) {
    if (confirm("Tem certeza que deseja excluir esta pergunta?")) {
        let requisicao = new XMLHttpRequest();
        requisicao.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                // Mostra a resposta do PHP (sucesso ou erro)
                document.getElementById("msg").innerHTML = this.responseText;
                
                // Se o PHP respondeu com "sucesso"
                if (this.responseText.includes("sucesso")) {
                    // Remove a linha da tabela da tela
                    let linhaParaRemover = document.getElementById("questao-" + id);
                    linhaParaRemover.remove();
                }
            }
        };
        // Chama a API de exclusão
        requisicao.open("GET", "api_excluir_questao.php?id=" + id, true);
        requisicao.send();
    }
}

// FUNÇÕES DO MÓDULO DE CRIAR PERGUNTAS

// Função chamada pelo botão em criarQuestaoME.html
function salvarPerguntaME() {
    // Pega os valores dos campos do formulário
    let pergunta = document.getElementById("pergunta").value;
    let respostas = document.getElementsByName("respostas[]");
    let radios = document.getElementsByName('resposta_correta');
    let resposta_correta = null;

    // Monta a string que vai ser enviada na URL para a API
    let params = "pergunta=" + encodeURIComponent(pergunta) + "&tipo=ME";
    
    // Loop pra adicionar todas as respostas ao final da string
    for (let i = 0; i < respostas.length; i++) {
        params += "&respostas[]=" + encodeURIComponent(respostas[i].value);
    }
    // Loop pra procurar qual botão de rádio foi marcado e adicionar o valor
    for (let i = 0; i < radios.length; i++) {
        if (radios[i].checked) {
            resposta_correta = radios[i].value;
            params += "&resposta_correta=" + resposta_correta;
            break;
        }
    }

    // Inicia o processo de chamada
    let xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
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
    xmlhttp.send();
}

// Função chamada pelo botão em criarQuestaoTX.html
function salvarPerguntaTX() {
    // Pega o texto da pergunta e monta a string
    let pergunta = document.getElementById("pergunta").value;
    let params = "pergunta=" + encodeURIComponent(pergunta) + "&tipo=TX";

    // Inicia a chamada
    let xmlhttp = new XMLHttpRequest();
    
    // Define o que fazer quando a resposta do servidor chegar
    xmlhttp.onreadystatechange = function() {
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

// FUNÇÕES DO MÓDULO DE EDITAR PERGUNTAS

// Essa função vai rodar na página editarQuestao.html
// Ela pega o ID da URL e busca os dados da pergunta
function carregarDadosEdicaoPergunta() {
    // Cria um objeto pra analisar a URL
    let parametrosUrl = new URLSearchParams(window.location.search);
    
    // Verifica se existe um id na URL
    if (parametrosUrl.has('id')) {
        let idDaUrl = parametrosUrl.get('id');
        
        // Prepara a chamada para a API de busca
        let requisicao = new XMLHttpRequest();
        requisicao.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                let resposta = JSON.parse(this.responseText);
                
                if (resposta.resultado === 'sucesso') {
                    // Se o PHP achou, preenche o formulário
                    preencherFormularioEdicao(resposta.dados);
                } else {
                    document.getElementById("msg").innerHTML = resposta.mensagem;
                }
            }
        };
        requisicao.open("GET", "api_buscar_questao.php?id=" + idDaUrl, true);
        requisicao.send();
    }
}

// Função que preenche o formulário
function preencherFormularioEdicao(questao) {
    document.getElementById("id_edit").value = questao.id;
    document.getElementById("tipo_edit").value = questao.tipo;
    document.getElementById("pergunta_edit").value = questao.texto;

    let camposME = document.getElementById("camposME_edit");
    if (questao.tipo === 'ME') {
        let radios = document.getElementsByName('resposta_correta_edit');
        let caixasDeTexto = document.getElementsByName("respostas_edit[]");
        
        // Limpa os campos
        caixasDeTexto.forEach(caixa => caixa.value = "");
        radios.forEach(radio => radio.checked = false);

        for (let i = 0; i < questao.respostas.length; i++) {
            if (caixasDeTexto[i]) caixasDeTexto[i].value = questao.respostas[i];
        }
        if (radios[questao.correta]) radios[questao.correta].checked = true;
        
        camposME.style.display = 'block';
    } else {
        camposME.style.display = 'none';
    }
}

// Função pra salvar as alterações
function salvarAlteracoes() {
    let id = document.getElementById("id_edit").value;
    let tipo = document.getElementById("tipo_edit").value;
    let pergunta = document.getElementById("pergunta_edit").value;
    let parametros = "id=" + id + "&tipo=" + tipo + "&pergunta=" + encodeURIComponent(pergunta);

    if (tipo === 'ME') {
        let respostas = document.getElementsByName("respostas_edit[]");
        for (let i = 0; i < respostas.length; i++) {
            parametros += "&respostas[]=" + encodeURIComponent(respostas[i].value);
        }
        let radios = document.getElementsByName('resposta_correta_edit');
        for (let i = 0; i < radios.length; i++) {
            if (radios[i].checked) { parametros += "&resposta_correta=" + radios[i].value; break; }
        }
    }

    let requisicao = new XMLHttpRequest();
    requisicao.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById("msg").innerHTML = this.responseText;
        }
    };
    requisicao.open("GET", "api_editar_questao.php?" + parametros, true);
    requisicao.send();
}