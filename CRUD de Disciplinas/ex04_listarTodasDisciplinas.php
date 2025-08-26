<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listagem de Disciplinas</title>
</head>
<body>
    <h2>Disciplinas Cadastradas</h2>
    <table>
        <thead>
            <tr>
                <th>Nome</th>
                <th>Sigla</th>
                <th>Carga Horária</th>
                <th colspan="2">Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $arquivoNome = "disciplinas.txt";

            $arqDisc = fopen($arquivoNome, 'r') or die("Erro ao abrir o arquivo");

            fgets($arqDisc);

            $numeroLinha = 1;

            while (!feof($arqDisc)) {
                $linha = fgets($arqDisc);

                if (trim($linha) == "") continue;

                $colunaDados = explode(";", $linha);

                echo "<tr>";
                echo "<td>" . $colunaDados[0] . "</td>";
                echo "<td>" . $colunaDados[1] . "</td>";
                echo "<td>" . $colunaDados[2] . "</td>";

                echo "<td>";
                echo "<form action='ex05_alterarDisciplina.php' method='post'>";
                echo "<input type='hidden' name='linha' value='" . $numeroLinha . "'>";
                echo "<button type='submit'>Alterar</button>";
                echo "</form>";
                echo "</td>";

                echo "<td>";
                echo "<form action='ex06_excluirDisciplina.php' method='post' onsubmit='return confirm(\"Tem certeza?\");'>";
                echo "<input type='hidden' name='linha' value='" . $numeroLinha . "'>";
                echo "<button type='submit'>Excluir</button>";
                echo "</form>";
                echo "</td>";

                echo "</tr>";

                $numeroLinha++;
                }

            fclose($arqDisc);
            ?>
        </tbody>
    </table>

    <br>
    <a href="ex03_IncluirDisciplina.php">Adicionar Nova Disciplina</a>

</body>
</html>