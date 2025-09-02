<?php
$v1 = $_GET["a"];
$v2 = $_GET["b"];
$op = $_GET["op"];
$result = 0;
if ($op == "+")
        $result = $v1 + $v2;
elseif ($op == "-")
        $result = $v1 - $v2;
elseif ($op == "*")
        $result = $v1 * $v2;
elseif ($op == "/")
        $result = $v1 / $v2;
        
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Calculadora</title>
</head>
<body>
  <form action="ex03_calculadora.php" method="get" name="soma">
    a: <input type="number" name="a"> <br><br>
    op:<input type="text" name="op"> <br><br>
    b: <input type="number" name="b"> <br><br>
    <input type="submit" value="Enviar">
  </form>
  <?php
      ECHO "<br><br>";
      ECHO "<h1>Resultado = $result</h1>";
  ?>
</body>
</html>
