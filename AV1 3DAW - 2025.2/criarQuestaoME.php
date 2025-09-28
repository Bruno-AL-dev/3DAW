<?php
// Página pra exibir o formulário e salvar uma nova pergunta de múltipla escolha

// Inicia as variáveis pra manter os dados no formulário em caso de erro
$pergunta = "";
$respostas = ["", "", "", "", ""];
$resposta_correta = null;
$erro = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $pergunta = $_POST['pergunta'];
    $respostas = $_POST['respostas'];
    
    // Verifica se um botão de rádio (resposta correta) foi selecionado
    if (isset($_POST['resposta_correta'])) {
        $resposta_correta = $_POST['resposta_correta'];
    }
    
    // A pergunta não pode estar vazia
    if (!empty($pergunta)) {
        
        // Remove os campos de resposta que tenham ficado em branco
        $respostasPreenchidas = array_filter($respostas);
        
        // Precisa ter pelo menos 2 respostas preenchidas
        if (count($respostasPreenchidas) >= 2) {
            
            // Precisa ter marcado uma resposta correta e essa resposta não pode ser um campo vazio
            if ($resposta_correta !== null && !empty($respostas[$resposta_correta])) {
                
                // Junta as respostas em uma string
                $respostasString = implode("|", $respostasPreenchidas);
                $id = uniqid();
                $nomeArquivo = 'questoes.txt';
                
                // Monta a linha no formato: id;tipo;pergunta;respostas;resposta_correta
                $linha = $id . ";ME;" . $pergunta . ";" . $respostasString . ";" . $resposta_correta . "\n";

                $arquivo = fopen($nomeArquivo, 'a');
                fwrite($arquivo, $linha);
                fclose($arquivo);

                // Redireciona pra lista para mostrar que a pergunta foi salva
                header("Location: listarQuestoes.php");
                exit();
            } else {
                $erro = "Erro: Você deve escolher uma resposta correta entre as opções preenchidas.";
            }
        } else {
            $erro = "Erro: É necessário fornecer pelo menos duas opções de resposta.";
        }
    } else {
        $erro = "Erro: O campo da pergunta não pode estar vazio.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Criar Pergunta de Múltipla Escolha</title>
</head>
<body>

<header>
    <h3>Sistema de Jogo Corporativo</h3>
    <nav>
        <strong>Usuários:</strong>
        <a href="index.php">Listar</a> | 
        <a href="criarUsuario.php">Criar Novo</a>
        
        <br> <strong>Perguntas:</strong>
        <a href="listarQuestoes.php">Listar</a> | 
        <a href="criarQuestaoME.php">Criar (M.E.)</a> | 
        <a href="criarQuestaoTX.php">Criar (Texto)</a>
    </nav>
    <hr>
</header>

<h1>Criar Nova Pergunta - Múltipla Escolha</h1>

<?php
// Se a variável $erro tiver algum texto, mostra na tela
if (!empty($erro)) {
    echo '<p style="color: red;">' . $erro . '</p>';
}
?>

<form action="criarQuestaoME.php" method="POST">
    <p>
        <label for="pergunta">Texto da Pergunta:</label><br>
        <textarea id="pergunta" name="pergunta" rows="4" cols="50"><?php echo $pergunta; ?></textarea>
    </p>
    <p>
        <label>Opções de Resposta (marque a opção correta):</label><br>
        <input type="radio" name="resposta_correta" value="0"> <input type="text" name="respostas[]" size="45" value="<?php echo $respostas[0]; ?>"><br>
        <input type="radio" name="resposta_correta" value="1"> <input type="text" name="respostas[]" size="45" value="<?php echo $respostas[1]; ?>"><br>
        <input type="radio" name="resposta_correta" value="2"> <input type="text" name="respostas[]" size="45" value="<?php echo $respostas[2]; ?>"><br>
        <input type="radio" name="resposta_correta" value="3"> <input type="text" name="respostas[]" size="45" value="<?php echo $respostas[3]; ?>"><br>
        <input type="radio" name="resposta_correta" value="4"> <input type="text" name="respostas[]" size="45" value="<?php echo $respostas[4]; ?>"><br>
    </p>
    <p>
        <button type="submit">Salvar Pergunta</button>
    </p>
</form>

</body>
</html>