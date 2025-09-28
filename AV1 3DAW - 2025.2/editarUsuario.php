<?php
// Carrega os dados de um usuário pra edição (GET) e salva as alterações (POST)

$nomeArquivo = 'usuarios.txt';
$erro = "";
$usuarioEncontrado = null;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Pega os dados que o usuário alterou e enviou pelo formulário
    $id = $_POST['id'];
    $matricula = $_POST['matricula'];
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $matriculaJaExiste = false;

    // Verifica se todos os campos estão preenchidos
    if (!empty($matricula) && !empty($nome) && !empty($email)) {

        // Lógica pra verificar se a matrícula já existe no arquivo
        if (file_exists($nomeArquivo)) {
            $arquivoLeitura = fopen($nomeArquivo, 'r');

            if ($arquivoLeitura) {
                while (($linha = fgets($arquivoLeitura)) !== false) {
                    $dados = explode(';', $linha);
                    $idExistente = $dados[0];
                    $matriculaExistente = $dados[1];

                    // Compara a matrícula do arquivo com a que foi digitada
                    if ($matriculaExistente == $matricula && $idExistente != $id) {
                        $matriculaJaExiste = true;

                        // Se achou não precisa procurar mais
                        break;
                    }
                }
                fclose($arquivoLeitura);
            }
        }

        // Se a matrícula foi encontrada define uma mensagem de erro
        if ($matriculaJaExiste) {
            $erro = "Erro: A matrícula '$matricula' já pertence a outro usuário!";
            // Guarda os dados que o usuário digitou pra exibir no formulário novamente
            $usuarioEncontrado = [
                'id' => $id,
                'matricula' => $matricula,
                'nome' => $nome,
                'email' => $email
            ];
        } else {
            // Se passou em todas as validações salva no arquivo
            $tempArquivoNome = 'usuarios_temp.txt';
            $arquivoOriginal = fopen($nomeArquivo, 'r');
            $arquivoTemp = fopen($tempArquivoNome, 'w');

            if ($arquivoOriginal && $arquivoTemp) {
                // Monta a linha com os novos dados
                $linhaAtualizada = $id . ";" . $matricula . ";" . $nome . ";" . $email . "\n";
                // Lê o arquivo original linha por linha
                while (($linha = fgets($arquivoOriginal)) !== false) {
                    $dados = explode(';', $linha);
                    // Se for a linha do usuário que está sendo editado, escreve a linha nova
                    if ($dados[0] == $id) {
                        fwrite($arquivoTemp, $linhaAtualizada);
                    } else {
                        // Senão só copia a linha antiga
                        fwrite($arquivoTemp, $linha);
                    }
                }
                fclose($arquivoOriginal);
                fclose($arquivoTemp);

                // Substitui o arquivo antigo pelo novo.
                unlink($nomeArquivo);
                rename($tempArquivoNome, $nomeArquivo);

                // Redireciona para a página inicial pra mostrar o usuário na lista
                header("Location: index.php");
                exit();
            }
        }
    } else {
        $erro = "Todos os campos são obrigatórios!";
        // Guarda os dados pra exibir no formulário novamente
        $usuarioEncontrado = [
            'id' => $id, 'matricula' => $matricula, 'nome' => $nome, 'email' => $email
        ];
    }
} else {
    // Pega o ID da URL pra saber qual usuário carregar
    $idParaEditar = $_GET['id'];
    if (file_exists($nomeArquivo) && !empty($idParaEditar)) {
        $arquivo = fopen($nomeArquivo, 'r');
        while (($linha = fgets($arquivo)) !== false) {
            $dados = explode(';', $linha);
            // Procura pela linha com o ID do usuário
            if ($dados[0] == $idParaEditar) {
                // Achou, então guarda os dados em um vetor pra usar no formulário
                $usuarioEncontrado = [
                    'id' => $dados[0],
                    'matricula' => $dados[1],
                    'nome' => $dados[2],
                    'email' => $dados[3]
                ];
                
                // Para o loop porque já encontrou
                break;
            }
        }
        fclose($arquivo);
    }
}

if ($usuarioEncontrado == null) {
    die("Erro: Usuário não encontrado ou ID inválido.");
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Editar Usuário</title>
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

<h1>Editar Usuário</h1>

<?php
if (!empty($erro)) {
    echo '<p style="color: red;">' . $erro . '</p>';
}
?>

<form action="editarUsuario.php" method="POST">
    <input type="hidden" name="id" value="<?php echo $usuarioEncontrado['id']; ?>">
    
    <p>
        <label for="matricula">Matrícula:</label><br>
        <input type="text" id="matricula" name="matricula" value="<?php echo $usuarioEncontrado['matricula']; ?>">
    </p>
    <p>
        <label for="nome">Nome:</label><br>
        <input type="text" id="nome" name="nome" value="<?php echo $usuarioEncontrado['nome']; ?>">
    </p>
    <p>
        <label for="email">Email:</label><br>
        <input type="text" id="email" name="email" value="<?php echo $usuarioEncontrado['email']; ?>">
    </p>
    <p>
        <button type="submit">Salvar Alterações</button>
    </p>
</form>

</body>
</html>