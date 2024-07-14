<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIGAET</title>
</head>
<body>
    <h1>Resp</h1>
    <?php
session_start();

function validateToken($token) {
    return isset($_SESSION['token']) && hash_equals($_SESSION['token'], $token);
}

// Verificar se o token de sessão está presente
if (!isset($_SESSION['token'])) {
    header('Location: ../../login/login.php?erro=003');
    exit();
}

// Verificar o token do cookie
if (isset($_COOKIE['session_token'])) {
    $token = $_COOKIE['session_token'];
    if (!validateToken($token)) {
        // Token inválido, tratar a autenticação falhou
        header('Location: ../../login/login.php?erro=002');
        exit();
    }else{
        $tipouser = $_SESSION['tipouser'];
        if ($tipouser !== "responsavel") {
            header('Location: ../../login/login.php?erro=001');
            exit();
        }
    }
} else {
    // Token não encontrado, tratar a autenticação falhou
    header('Location: ../../login/login.php?erro=003');
    exit();
}

?>
</body>
</html>