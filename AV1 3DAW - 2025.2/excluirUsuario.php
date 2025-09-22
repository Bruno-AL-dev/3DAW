<?php
$arqUsuarios = 'usuarios.txt';

if (isset($_GET['id'])) {
    $idParaExcluir = $_GET['id'];
    
    $linhas = file($arqUsuarios, FILE_IGNORE_NEW_LINES);
    $novasLinhas = [];

    foreach ($linhas as $linha) {
        $dados = explode(';', $linha);
        if ($dados[0] != $idParaExcluir) {

            $novasLinhas[] = $linha . PHP_EOL;
        }
    }

    file_put_contents($arqUsuarios, implode(PHP_EOL, $novasLinhas));
}

header('Location: index.php');
exit;
?>