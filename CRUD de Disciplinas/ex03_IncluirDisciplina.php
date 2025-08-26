<?php
$msg = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $nome = $_POST["nome"];
  $sigla = $_POST["sigla"];
  $carga = $_POST["carga"];

  if ($nome == "" || $sigla == "" || $carga == "") {
    $msg = "Erro: Todos os campos são obrigatórios!";
  } else {
    $arquivo = "disciplinas.txt";
    if(!file_exists("disciplinas.txt")) {
      $arqDisc = fopen("disciplinas.txt", "w") or die("Erro ao abrir o arquivo");
      $linha = "nome;sigla;carga;\n";
      fwrite($arqDisc,$linha);
      fclose($arqDisc);
    }

    $arqDisc = fopen($arquivo, "a") or die("Erro ao abrir o arquivo para salvar");

    $novaLinha = $nome . ";" . $sigla . ";" . $carga . "\n";

    fwrite($arqDisc, $novaLinha);

    fclose($arqDisc);

    $msg = "Disciplina salva com sucesso!";
  }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Incluir Disciplina</title>
</head>
<body>
  <h2>Incluir Disciplina</h2>
  <?php
    if ($msg) {
      echo "$msg";
    }
  ?>  

  <form action="ex03_IncluirDisciplina.php" method="post">
    Nome: <input type="text" name="nome"> <br><br>
    Sigla:<input type="text" name="sigla"> <br><br>
    Carga Horaria: <input type="text" name="carga"> <br><br>
    <input type="submit" value="Criar nova disciplina">
  </form>
<hr>
<br>
<a href="ex04_listarTodasDisciplinas.php">Listar todas as Disciplinas</a>
</body>
</html>