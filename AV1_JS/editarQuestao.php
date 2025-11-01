<?php
// Carrega os dados da pergunta usando PHP

// Carregamento dos dados com PHP
$nomeArquivo = 'questoes.txt';
$questaoEncontrada = null;

// Pega o ID que veio da URL do link em listarQuestoes.php
$idParaEditar = null;
if (isset($_GET['id'])) {
    $idParaEditar = $_GET['id'];
}

if ($idParaEditar && file_exists($nomeArquivo)) {
    $arquivo = fopen($nomeArquivo, 'r');
    while (($linha = fgets($arquivo)) !== false) {
        $dados = explode(';', $linha);
        if (trim($dados[0]) == $idParaEditar) {
            // Achou, então guarda os dados pra usar no formulário
            $questaoEncontrada = [
                'id' => trim($dados[0]), 
                'tipo' => trim($dados[1]), 
                'texto' => trim($dados[2])
            ];
            if (trim($dados[1]) == 'ME') {
                $questaoEncontrada['respostas'] = explode('|', trim($dados[3]));
                $questaoEncontrada['correta'] = (int)trim($dados[4]);
            }
            break;
        }
    }
    fclose($arquivo);
}

// Se não encontrou a questão, para a execução
if ($questaoEncontrada == null) {
    die("Erro: Questão não encontrada ou ID inválido. <a href='listarQuestoes.php'>Voltar</a>");
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Editar Pergunta</title>
    <script>
        // Função pra salvar com javascript
        function salvarAlteracoes() {
            // Pega os dados dos campos do formulário
            let id = document.getElementById("id_edit").value;
            let tipo = document.getElementById("tipo_edit").value;
            let pergunta = document.getElementById("pergunta_edit").value;
            let parametros = "id=" + id + "&tipo=" + tipo + "&pergunta=" + encodeURIComponent(pergunta);

            if (tipo === 'ME') {
                let respostas = document.getElementsByName("respostas_edit[]");
                for (let i = 0; i < respostas.length; i++) {
                    parametros += "&respostas[]=" + encodeURIComponent(respostas[i].value);
                }
                let radios = document.getElementsByName('resposta_correta_edit');
                for (let i = 0; i < radios.length; i++) {
                    if (radios[i].checked) { 
                        parametros += "&resposta_correta=" + radios[i].value; 
                        break; 
                    }
                }
            }

            // Faz a chamada para a API de edição
            let requisicao = new XMLHttpRequest();
            requisicao.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    // Mostra a mensagem de sucesso ou erro do PHP
                    document.getElementById("msg").innerHTML = '<p style="color: green;">' + this.responseText + '</p>';
                } else if (this.readyState == 4) {
                    // Mostra um erro se a chamada falhar
                     document.getElementById("msg").innerHTML = '<p style="color: red;">Erro ao salvar: ' + this.status + '</p>';
                }
            };
            // Chama a API
            requisicao.open("GET", "api_editar_questao.php?" + parametros, true);
            requisicao.send();
        }
    </script>
</head>
<body>
<header>
    <h3>Sistema de Jogo Corporativo</h3>
    <nav>
        <strong>Usuários:</strong>
        <a href="listarUsuarios.php">Listar</a> | 
        <a href="criarUsuario.php">Criar Novo</a>
        <br>
        <strong>Perguntas:</strong>
        <a href="listarQuestoes.php">Listar</a> | 
        <a href="criarQuestaoME.php">Criar (M.E.)</a> | 
        <a href="criarQuestaoTX.php">Criar (Texto)</a> |
    </nav>
    <hr>
</header>

<h1>Editando Pergunta (ID: <?php echo $questaoEncontrada['id']; ?>)</h1>

<div id="msg"></div>

<form id="formEdicao">
    <input type="hidden" id="id_edit" value="<?php echo $questaoEncontrada['id']; ?>">
    <input type="hidden" id="tipo_edit" value="<?php echo $questaoEncontrada['tipo']; ?>">
    <p>
        <label for="pergunta_edit">Texto da Pergunta:</label><br>
        <textarea id="pergunta_edit" rows="4" cols="50"><?php echo $questaoEncontrada['texto']; ?></textarea>
    </p>
    
    <?php
        $estiloDisplay = 'none'; // Por padrão fica escondido
        if ($questaoEncontrada['tipo'] == 'ME') {
            $estiloDisplay = 'block'; // Se for ME, mostra
        }
    ?>
    <div id="camposME_edit" style="display: <?php echo $estiloDisplay; ?>;">
        <p>
            <label>Opções de Resposta:</label><br>
            <?php 
            for ($i = 0; $i < 5; $i++):
                $resposta = '';
                if (isset($questaoEncontrada['respostas'][$i])) {
                    $resposta = $questaoEncontrada['respostas'][$i];
                }
                
                $checked = '';
                if (isset($questaoEncontrada['correta']) && $questaoEncontrada['correta'] == $i) {
                    $checked = 'checked';
                }
            ?>
                <input type="radio" name="resposta_correta_edit" value="<?php echo $i; ?>" <?php echo $checked; ?>> 
                <input type="text" name="respostas_edit[]" size="45" value="<?php echo $resposta; ?>"><br>
            <?php endfor; ?>
        </p>
    </div>
    <p>
        <input type="button" value="Salvar Alterações" onclick="salvarAlteracoes();">
    </p>
</form>
<br>
<a href="listarQuestoes.php">Voltar para a lista</a>

</body>
</html>