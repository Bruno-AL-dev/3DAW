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

// Event Listeners (Gatilhos de Ação)
// Quando o botão "Salvar" for clicado chama a função "salvarAluno"
btnSalvar.addEventListener('click', salvarAluno);

// Quando o botão "Limpar" for clicado chama a função "limparFormulario"
btnLimpar.addEventListener('click', limparFormulario);

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