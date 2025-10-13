<?php
// API pra buscar os dados de uma pergunta e devolver em JSON pro JavaScript

// Avisa o navegador que a resposta vai ser em formato JSON
header('Content-Type: application/json');

$nomeArquivo = 'questoes.txt';
$questaoEncontrada = null; // Começa como nulo, se achar a pergunta muda

// verifica se o JavaScript mandou um ID na URL
if (isset($_GET['id'])) {
    $idParaBuscar = $_GET['id'];
    if (file_exists($nomeArquivo)) {
        $arquivo = fopen($nomeArquivo, 'r');
        // Lê o arquivo linha por linha
        while (($linha = fgets($arquivo)) !== false) {
            $dados = explode(';', $linha);
            // Vê se o ID da linha é o mesmo que está sendo procurado
            if (trim($dados[0]) == $idParaBuscar) {
                // Achou, então guarda os dados da pergunta num array
                $questaoEncontrada = [
                    'id' => trim($dados[0]),
                    'tipo' => trim($dados[1]),
                    'texto' => trim($dados[2])
                ];
                // Se for de múltipla escolha, pega também as respostas e a correta
                if (trim($dados[1]) == 'ME') {
                    $questaoEncontrada['respostas'] = explode('|', trim($dados[3]));
                    $questaoEncontrada['correta'] = (int)trim($dados[4]);
                }
                // Para o loop porque já achei o que eu queria
                break;
            }
        }
        fclose($arquivo);
    }
}

// Se a variável $questaoEncontrada não for mais nula, é porque foi encontrada
if ($questaoEncontrada) {
    // Manda de volta um JSON dizendo que deu 'sucesso' e os 'dados' da pergunta
    echo json_encode(['resultado' => 'sucesso', 'dados' => $questaoEncontrada]);
} else {
    // Se não achou, manda um JSON de 'erro' com uma mensagem
    echo json_encode(['resultado' => 'erro', 'mensagem' => 'Questão não encontrada']);
}
?>