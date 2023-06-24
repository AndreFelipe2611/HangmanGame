<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./style.css">
    <title>Document</title>
</head>

<body>
    <h1>Jogo da forca</h1>

    <div class="palavras">

        <?php
        session_start();

        // Palavras para o jogo
        $palavras = array("elefante", "banana", "computador", "programacao", "girafa");

        // Função para escolher uma palavra aleatória
        function escolherPalavraAleatoria($palavras)
        {
            return $palavras[array_rand($palavras)];
        }

        // Verifica se é um novo jogo ou se o jogador ganhou
        if (!isset($_SESSION['palavra']) || jogadorGanhou($_SESSION['palavra'], $_SESSION['letrasCorretas'])) {
            $_SESSION['palavra'] = escolherPalavraAleatoria($palavras);
            $_SESSION['letrasCorretas'] = array();
            $_SESSION['letrasErradas'] = array();
            $_SESSION['tentativas'] = 0;
            $_SESSION['maxTentativas'] = 6; // Definindo o número máximo de tentativas
        }

        // Recupera os dados do jogo
        $palavra = $_SESSION['palavra'];
        $letrasCorretas = $_SESSION['letrasCorretas'];
        $letrasErradas = $_SESSION['letrasErradas'];
        $tentativas = $_SESSION['tentativas'];
        $maxTentativas = $_SESSION['maxTentativas'];

        // Verifica se uma letra foi submetida
        if (isset($_POST['letra'])) {
            $letra = strtolower($_POST['letra']); // Converte para minúsculo

            // Verifica se a letra já foi tentada
            if (in_array($letra, $letrasCorretas) || in_array($letra, $letrasErradas)) {
                echo "Essa letra já foi tentada. Tente outra.<br><br>";
            } else {
                // Verifica se a letra está na palavra
                if (strpos($palavra, $letra) !== false) {
                    // Letra correta
                    $letrasCorretas[] = $letra;
                } else {
                    // Letra errada
                    $letrasErradas[] = $letra;
                    $tentativas++;
                }

                $_SESSION['letrasCorretas'] = $letrasCorretas;
                $_SESSION['letrasErradas'] = $letrasErradas;
                $_SESSION['tentativas'] = $tentativas;
            }
        }

        // Função para exibir a palavra com as letras adivinhadas
        function exibirPalavra($palavra, $letrasCorretas)
        {
            $exibir = '';
            foreach (str_split($palavra) as $letra) {
                if (in_array($letra, $letrasCorretas)) {
                    $exibir .= $letra . ' ';
                } else {
                    $exibir .= '_ ';
                }
            }
            return $exibir;
        }

        // Verifica se o jogador ganhou o jogo
        function jogadorGanhou($palavra, $letrasCorretas)
        {
            foreach (str_split($palavra) as $letra) {
                if (!in_array($letra, $letrasCorretas)) {
                    return false;
                }
            }
            return true;
        }

        // Verifica se o jogador perdeu o jogo
        function jogadorPerdeu($maxTentativas, $tentativas)
        {
            return $tentativas >= $maxTentativas;
        }

        // Exibe o status do jogo

        function exibirStatus($palavra, $maxTentativas, $tentativas, $letrasCorretas, $letrasErradas)
        {
            echo  "Palavra: " . exibirPalavra($palavra, $letrasCorretas) . "<br><br><br>";
            echo "Tentativas restantes: " . ($maxTentativas - $tentativas) . "<br><br>";
            echo "Letras erradas: " . implode(", ", $letrasErradas) . "<br><br>";
        }

        // Processa o formulário e atualiza o jogo
        if (jogadorGanhou($palavra, $_SESSION['letrasCorretas'])) {
            echo "Parabéns! Você ganhou o jogo. A palavra era: " . $palavra . "<br>";
            echo "Deseja continuar jogando?<br>";
        ?>
            <form method="post">
                <input class="btns" type="submit" name="continuar" value="Sim">
                <input class="btnn" type="submit" name="continuar" value="Não">
            </form>
        <?php
        } elseif (jogadorPerdeu($maxTentativas, $tentativas)) {
            echo "Você perdeu o jogo. A palavra era: " . $palavra . "<br>";
            echo "Deseja continuar jogando?<br>";
        ?>
    </div>
    <form method="post">
        <input class="btns" type="submit" name="continuar" value="Sim">
        <input class="btnn" type="submit" name="continuar" value="Não">
    </form>
<?php
            session_destroy();
        } else {
            exibirStatus($palavra, $maxTentativas, $tentativas, $letrasCorretas, $letrasErradas);
?>
    <form method="post">
        <label for="letra">Digite uma letra:</label>
        <input class="caixa" type="text" name="letra" id="letra" maxlength="1">
        <input class="btn" type="submit" value="Enviar">
    </form>
<?php
        }

        // Verifica se o jogador deseja continuar jogando
        if (isset($_POST['continuar'])) {
            if ($_POST['continuar'] === 'Não') {
                echo "Obrigado por jogar!";
            } else {
                // Reinicia o jogo
                $_SESSION['palavra'] = escolherPalavraAleatoria($palavras);
                $_SESSION['letrasCorretas'] = array();
                $_SESSION['letrasErradas'] = array();
                $_SESSION['tentativas'] = 0;
            }
        }
?>
</body>
</html>