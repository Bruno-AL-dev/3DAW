<?php
// Exibe o formulário pra criar um novo usuário

// Inicia as variáveis para os campos do formulário
$matricula = "";
$nome = "";
$email = "";
$erro = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Pega os dados que o usuário digitou no formulário
    $matricula = $_POST['matricula'];
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $nomeArquivo = 'usuarios.txt';
    $matriculaJaExiste = false;

    // Verifica se todos os campos foram preenchidos
    if (!empty($matricula) && !empty($nome) && !empty($email)) {
        
        // Lógica pra verificar se a matrícula já existe no arquivo
        if (file_exists($nomeArquivo)) {
            $arquivoLeitura = fopen($nomeArquivo, 'r');
            
            while (($linha = fgets($arquivoLeitura)) !== false) {
                $dados = explode(';', $linha);
                
                $matriculaExistente = $dados[1];

                // Compara a matrícula do arquivo com a que foi digitada
                if ($matriculaExistente == $matricula) {
                    $matriculaJaExiste = true;

                    // Se achou não precisa procurar mais
                    break;
                }
            }
            fclose($arquivoLeitura);
        }

        // Se a matrícula foi encontrada define uma mensagem de erro
        if ($matriculaJaExiste) {
            $erro = "Erro: A matrícula '$matricula' já está cadastrada!";
        } else {
            // Se não encontrou matrícula duplicada salva o novo usuário
            $id = uniqid();

            // Monta a string que vai ser salva no arquivo: id;matricula;nome;email
            $linha = $id . ";" . $matricula . ";" . $nome . ";" . $email . "\n";

            // Abre, escreve a nova linha no final e fecha o arquivo
            $arquivoEscrita = fopen($nomeArquivo, 'a');
            fwrite($arquivoEscrita, $linha);
            fclose($arquivoEscrita);

            // Redireciona para a página inicial pra mostrar o usuário na lista
            header("Location: index.php");
            exit();
        }

    } else {
        $erro = "Todos os campos são obrigatórios!";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Criar Novo Usuário</title>
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

<h1>Criar Novo Usuário</h1>

<?php
// Se a variável $erro não estiver vazia exibe a mensagem na tela
if (!empty($erro)) {
    echo '<p style="color: red;">' . $erro . '</p>';
}
?>

<form action="criarUsuario.php" method="POST">
    <p>
        <label for="matricula">Matrícula:</label><br>
        <input type="text" id="matricula" name="matricula" value="<?php echo $matricula; ?>">
    </p>
    <p>
        <label for="nome">Nome:</label><br>
        <input type="text" id="nome" name="nome" value="<?php echo $nome; ?>">
    </p>
    <p>
        <label for="email">Email:</label><br>
        <input type="text" id="email" name="email" value="<?php echo $email; ?>">
    </p>
    <p>
        <button type="submit">Salvar Usuário</button>
    </p>
</form>

</body>
</html>