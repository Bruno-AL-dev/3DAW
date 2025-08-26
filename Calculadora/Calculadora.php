<?php
$v1 = 0;
$v2 = 0;
$result = 0;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $v1 = (int)$_POST["a"];
    $v2 = (int)$_POST["b"];
    $op = $_POST["op"];

    if ($op == "+")
        $result = $v1 + $v2;
    elseif ($op == "-")
            $result = $v1 - $v2;
    elseif ($op == "*")
            $result = $v1 * $v2;
    elseif ($op == "/")
            $result = $v1 / $v2;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Calculadora V2</title>
</head>
<body>
  <form action="ex03_calculadoraV2.php" method="post" name="soma">
    a: <input type="number" name="a"> <br><br>
    op:<input type="text" name="op"> <br><br>
    b: <input type="number" name="b"> <br><br>
    <input type="submit" value="Enviar">
  </form>
  <?php
      echo "<br><br>";
      echo "<h1>Resultado = $result</h1>";
  ?>
</body>
</html>
