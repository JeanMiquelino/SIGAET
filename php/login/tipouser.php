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

$email = $_SESSION['email'];
$pass = $_SESSION['pass'];

if (isset($_POST['unidade'])) {
    $unidade = $_POST['unidade'];
    $_SESSION['unidade'] = $unidade;
} else {
    $unidade = $_SESSION['unidade'];
}

// Conexão com o banco de dados
$conngeral = new mysqli("localhost", "root", "", "eusers");
if ($conngeral->connect_error) {
    die("Connection failed: " . $conngeral->connect_error);
}

// Evita SQL Injection
$email = $conngeral->real_escape_string($email);

// Conexão com o banco de dados da unidade
$connuni = new mysqli("localhost", "root", "", "e" . $unidade);
if ($connuni->connect_error) {
    die("Connection failed: " . $connuni->connect_error);
}

// Executa a consulta na unidade
$stmt = $connuni->prepare("SELECT * FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$resultadouni = $stmt->get_result();

if ($resultadouni && $resultadouni->num_rows > 0) {
    $rowuni = $resultadouni->fetch_assoc();
    if ($rowuni['pass'] != $pass) {
        header("Location: ../login/login.php?erro=004");
        exit();
    }
    $tipouser = $rowuni['tipouser'];
    $_SESSION['nome'] = $rowuni['nome'];

    // Armazena informações na sessão
    $_SESSION['login_time'] = date("Y-m-d H:i:s");
    $_SESSION['tipouser'] = $tipouser;
    function generateToken($length = 32) {
        return bin2hex(random_bytes($length));
    }
    
    $_SESSION['token'] = generateToken();
    
    // Armazenar o token no cliente como um cookie
    setcookie('session_token', $_SESSION['token'], time() + 3600, '/', '', true, true);
    
    // Contagem dos tipos de usuário
    $tipos = explode(",", $tipouser);
    $tiposContagem = array_count_values($tipos);
    
    // Calcula a quantidade total
    $totalUsuarios = array_sum($tiposContagem);

    if ($totalUsuarios == 1) {
        // Redireciona automaticamente para a nova página
        header("Location: redir.php");
        exit();
    } else {
        echo "<div class='container login'>"; 
        echo "<h2>Escolha o tipo de usuário:</h2>"; 
        echo "<form method='post' action='redir.php'>";
        foreach ($tipos as $tipo) {
            echo "<button type='submit' name='tipouser' value='" . htmlspecialchars($tipo) . "'>" . mb_convert_case(htmlspecialchars($tipo), MB_CASE_TITLE, 'UTF-8') . "</button>";
        }
        echo "</form>";
    }

    // Fecha a conexão com a unidade
    $stmt->close();
    $connuni->close();
} else {
    header("Location: ./login.php?erro=004");
}

// Fecha a conexão geral
$conngeral->close();
?>
</body>
</html>