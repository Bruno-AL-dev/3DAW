// Constantes e Variáveis Globais

// Define a URL base da API (O local onde o "api_crudAlunos.php" está)
const URL_API = 'http://localhost/3daw/CrudAlunos_BD/api_crudAlunos.php';

// Pega os elementos do formulário
const formAluno = document.getElementById('formAluno');
const nome = document.getElementById('nome');
const matricula = document.getElementById('matricula');
const email = document.getElementById('email');

// Pega os botões
const btnSalvar = document.getElementById('btnSalvar');
const btnLimpar = document.getElementById('btnLimpar');
const btnListarTodos = document.getElementById('btnListarTodos');

// Pega a área onde a lista de alunos será mostrada (o corpo da tabela)
const listaAlunos = document.getElementById('listaAlunos');

// Event Listeners (Gatilhos de Ação)
// 'DOMContentLoaded' é o evento que dispara quando o HTML foi
// completamente carregado. É o melhor momento pra carregar a lista
document.addEventListener('DOMContentLoaded', () => {
    listarTodosAlunos();
});

// Quando o botão "Salvar" for clicado chama a função "salvarAluno"
btnSalvar.addEventListener('click', salvarAluno);

// Quando o botão "Limpar" for clicado chama a função "limparFormulario"
btnLimpar.addEventListener('click', limparFormulario);

// Quando o botão "Atualizar Lista" for clicado chama a função "listarTodosAlunos"
btnListarTodos.addEventListener('click', listarTodosAlunos);

// Funções Principais
// Função 1: Salvar Aluno
function salvarAluno() {
    // Pega os valores dos campos
    const nomeVal = nome.value;
    const matriculaVal = matricula.value;
    const emailVal = email.value;

    // Verifica se os campos obrigatórios estão preenchidos
    if (!nomeVal || !matriculaVal || !emailVal) {
        alert('Por favor, preencha todos os campos.');
        return; // Para a execução
    }

    // Cria o objeto "aluno" com os dados
    const aluno = {
        nome: nomeVal,
        matricula: matriculaVal,
        email: emailVal
    };

    incluirAluno(aluno);
}

/*
    Função 1a: Incluir Aluno (CREATE)
    Envia o novo aluno para a API usando o método POST
*/
function incluirAluno(aluno) {
    fetch(URL_API + '?acao=incluir', {
        method: 'POST', // Método HTTP para criar dados
        headers: {
            'Content-Type': 'application/json', // Diz para a API que está enviando JSON
        },
        // JSON.stringify traduz o objeto JS para o formato de texto JSON
        body: JSON.stringify(aluno) 
    })
    .then(response => response.json()) // Traduz a resposta da API
    .then(resultado => {
        if (resultado.status === 'sucesso') {
            alert('Aluno incluído com sucesso!');
            limparFormulario(); // Limpa os campos
            listarTodosAlunos(); // Atualiza a tabela
        } else {
            alert('Erro ao incluir aluno: ' + resultado.mensagem);
        }
    })
    .catch(erro => {
        console.error('Erro no POST:', erro);
        alert('Erro de comunicação com a API.');
    });
}

/*
    Função 2: Limpar formulário
    Limpa todos os campos do formulário
 */
function limparFormulario() {
    nome.value = '';
    matricula.value = '';
    email.value = '';
}

/*
    Função 3: Listar todos os alunos (READ)
    Busca os alunos na API e exibe na tabela.
*/
function listarTodosAlunos() {
    // Limpa a tabela antes de carregar novos dados
    listaAlunos.innerHTML = '<tr><td colspan="5">Carregando...</td></tr>';

    // Por padrão o "fetch" faz um GET
    fetch(URL_API + '?acao=listarTodos')
        .then(response => {
            // "response.json()"" traduz a resposta JSON da API pra um array
            return response.json();
        })
        .then(dados => {
            // Se "dados", que é o array de alunos estiver vazio
            if (dados.length === 0) {
                listaAlunos.innerHTML = '<tr><td colspan="5">Nenhum aluno cadastrado.</td></tr>';
            } else {
                // Se tiver alunos, limpa a tabela
                listaAlunos.innerHTML = '';
                
                // "forEach" é um loop pra passar por cada item do array "dados"
                dados.forEach(aluno => {
                    // Cria uma nova linha (tr) na tabela
                    const linha = document.createElement('tr');
                    
                    // Adiciona as colunas (td) com os dados do aluno
                    linha.innerHTML = `
                        <td>${aluno.id}</td>
                        <td>${aluno.nome}</td>
                        <td>${aluno.matricula}</td>
                        <td>${aluno.email}</td>
                        <td>
                            <button onclick="prepararEdicao(${aluno.id})">Editar</button>
                            <button onclick="excluirAluno(${aluno.id})">Excluir</button>
                        </td>
                    `;
                    // Adiciona a linha pronta no corpo da tabela (tbody)
                    listaAlunos.appendChild(linha);
                });
            }
        })
        .catch(erro => {
            // Se der erro na comunicação com a API
            listaAlunos.innerHTML = '<tr><td colspan="5">Erro ao carregar a lista.</td></tr>';
            console.error('Erro ao listar alunos:', erro);
        });
}