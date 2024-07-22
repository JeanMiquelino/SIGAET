<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIGAET</title>
    <link rel="shortcut icon" href="../../img/logo.jpg" type="image/x-icon">
    <link rel="stylesheet" href="../../css/login.css">
</head>
<body>
    <div class="container login">
        <h2>Login - SIGAET</h2>
        <br>
        <img src="../../img/logo.jpg" alt="" id="logo-login">

        <?php
        $erro = isset($_GET['erro']) ? $_GET['erro'] : 0;
        if($erro != 0){
            echo "<br>";
            echo "<br>";
            switch ($erro) {
                case '001':
                    echo "<p class='erro'>Tipo de usuário incorreto.</p>";
                    break;
                case '002':
                    echo "<p class='erro'>Token de autenticação inválido.</p>";
                    break;
                case '003':
                    echo "<p class='erro'>Token de autenticação não encontrado.</p>";
                    break;
                case '004':
                    echo "<p class='erro'>Email ou senha incorretos.</p>";
                    break;
                
                default:
                echo "<p class='erro'>Erro desconhecido.</p>";
                    break;
            }
            
        }

        ?>
        <br>
        <form method="post" action="../../php/login/verificacao.php">
            <div id="container-email">
            <label for="email">Email Institucional</label>
            <input name="email" id="email" type="email" required>
            </div>
            <div id="container-pass">
                <label for="pass">Senha</label>
                <input name="pass" id="pass" type="password" required>
            </div>
            <button type="submit">Login</button>
        </form>
    </div>
</body>
</html>