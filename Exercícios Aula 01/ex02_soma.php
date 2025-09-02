<?php
$v1 = $_GET["a"];
$v2 = $_GET["b"];
$result = 0;
$result = $v1 + $v2;
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Soma</title>
</head>
<body>
  <form action="ex02_soma.php" method="get" name="soma">
    a: <input type="number" name="a"> <br><br>
    b: <input type="number" name="b"> <br><br>
    <input type="submit" value="Enviar">
  </form>
  <?php
      echo "<br><br>";
      echo "<h1>Resultado = $result</h1>";
  ?>
</body>
</html>
