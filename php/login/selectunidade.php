<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../../img/logo.jpg" type="image/x-icon">
    <link rel="stylesheet" href="../../css/configslogin.css">
    <title>SIGAET</title>
</head>
<body>
    
<?php
session_start();
function validateToken($stoken, $ctoken) {
    return isset($stoken) && hash_equals($stoken, $ctoken);
}

// Verificação do token
$stoken = $_SESSION['token'] ?? null;
$ctoken = $_COOKIE['session_token'] ?? null;

if (!isset($ctoken) || !validateToken($stoken, $ctoken)) {
    // Token inválido ou não encontrado, tratar a autenticação falhou
    header('Location: ./login.php?erro=002');
    exit();
}


$servername = "localhost";
$username = "root";
$password = "";
$dbname = "eusers";
$email = $_SESSION['email'];

// Criação da conexão
$conngeral = new mysqli($servername, $username, $password, $dbname);

// Verificação de conexão
if ($conngeral->connect_error) {
    die("Connection failed: " . $conngeral->connect_error);
}

// Preparando a consulta para evitar SQL Injection
$stmt = $conngeral->prepare("SELECT unidade FROM users WHERE email = ?");
$stmt->bind_param("s", $email);

// Executando a consulta
$stmt->execute();
$resultadogeral = $stmt->get_result();

// Verificação do resultado da consulta
if ($resultadogeral && $resultadogeral->num_rows > 0) {
    $unidades = [];
    while ($rowgeral = $resultadogeral->fetch_assoc()) {
        // Divide a lista de unidades separadas por vírgulas
        $unidades = array_merge($unidades, explode(',', $rowgeral['unidade']));
    }
    
    // Remove duplicatas se houver
    $unidades = array_unique($unidades);
    
    // Verifica a quantidade de unidades
    if (count($unidades) == 1) {
        // Redireciona automaticamente se há apenas uma unidade
        $unidade = $unidades[0];
        $_SESSION['unidade'] = $unidade;
        header("Location: ./tipouser.php");
        exit();
    } else {
        // Gera botões para cada unidade se há mais de uma unidade
        echo "<div class='container login'>";
        echo "<h2>Escolha a unidade:</h2>";
        echo "<form method='post' action='./tipouser.php'>";
        foreach ($unidades as $unidade) {
            echo "<button type='submit' name='unidade' value='" . htmlspecialchars($unidade) . "'>" . htmlspecialchars($unidade) . "</button><br>";
        }
        echo "</form>";
        echo "</div>";
    }
} else {
    header("Location: ./login.php?erro=004");
}

// Fechando a declaração e a conexão
$stmt->close();
$conngeral->close();
?>
</body>
</html>
