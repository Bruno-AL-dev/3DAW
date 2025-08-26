<?php
$msg = "";
$arquivoNome = "disciplinas.txt";

if (isset($_POST['acao']) && $_POST['acao'] == 'salvar') {

    $nome = $_POST['nome'];
    $sigla = $_POST['sigla'];
    $carga = $_POST['carga'];

    if ($nome == "" || $sigla == "" || $carga == "") {
        $msg = "Erro: Todos os campos são obrigatórios!";
    } else {
        $linhaNum = $_POST['linha'];
        
        $linhas = file($arquivoNome);
        $linhas[$linhaNum] = "$nome;$sigla;$carga\n";
        file_put_contents($arquivoNome, implode('', $linhas));
        
        $msg = "Disciplina alterada com sucesso!";
    }
}

$linhaNum = $_POST['linha'];
$linhas = file($arquivoNome);
$dadosLinha = explode(";", $linhas[$linhaNum]);
$nomeAtual = $dadosLinha[0];
$siglaAtual = $dadosLinha[1];
$cargaAtual = trim($dadosLinha[2]);

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Alterar Disciplina</title>
</head>
<body>
  <h2>Alterar Disciplina</h2>

  <?php
  if ($msg) {
      echo "$msg";
  }
  ?>

  <form action="ex05_alterarDisciplina.php" method="post">
    <input type="hidden" name="acao" value="salvar">
    <input type="hidden" name="linha" value="<?php echo $linhaNum; ?>">
    
    Nome: <input type="text" name="nome" value="<?php echo $nomeAtual; ?>"> <br><br>
    Sigla: <input type="text" name="sigla" value="<?php echo $siglaAtual; ?>"> <br><br>
    Carga Horária: <input type="text" name="carga" value="<?php echo $cargaAtual; ?>"> <br><br>
    <input type="submit" value="Salvar Alterações">
  </form>
<hr>
<br>
<a href="ex04_listarTodasDisciplinas.php">Voltar para a Listagem</a>
</body>
</html>