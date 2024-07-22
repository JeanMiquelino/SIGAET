<?php
session_start();

// Conexão com o banco de dados geral
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "eusers";
$conngeral = new mysqli($servername, $username, $password, $dbname);

// Verifica se a conexão foi bem-sucedida
if ($conngeral->connect_error) {
    die("Connection failed: " . $conngeral->connect_error);
}

// Configurações do Twilio
$account_sid = 'Account SID';
$auth_token = 'Auth Token';
$twilio_number = 'Twilio Number';

$email = $_POST['email'] ?? '';
$pass = $_POST['pass'] ?? '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['pass'])) {
        if(empty($email) || empty($pass)) {
            header('Location: ./login.php');
            exit();
        }

        $stmt = $conngeral->prepare("SELECT email, pass, tel FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if($user && $pass = $user['pass']) {
            $codigo_verificacao = rand(100000, 999999); // Gera um código de 6 dígitos
            $_SESSION['codigo_verificacao'] = $codigo_verificacao;
            $_SESSION['email'] = $email;

            // Enviar código de verificação via Twilio usando cURL
            $data = [
                'To' => $user['tel'],
                'From' => $twilio_number,
                'Body' => "Seu código de verificação é: $codigo_verificacao"
            ];
            $url = 'https://api.twilio.com/2010-04-01/Accounts/' . $account_sid . '/Messages.json';

            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
            curl_setopt($ch, CURLOPT_USERPWD, $account_sid . ':' . $auth_token);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($ch);
            curl_close($ch);

             if ($response === false) {
                 echo 'Erro ao enviar o código de verificação.';
                 exit();
            }
        } else {
            $_SESSION['nao_autenticado'] = true;
            header('Location: ./login.php');
            exit();
        }
    } elseif (isset($_POST['codigo_verificacao'])) {
        if ($_POST['codigo_verificacao'] == $_SESSION['codigo_verificacao']) {
            function generateToken($length = 32) {
                return bin2hex(random_bytes($length));
            }
            
            $_SESSION['token'] = generateToken();
            
            // Armazenar o token no cliente como um cookie
            setcookie('session_token', $_SESSION['token'], time() + 3600, '/', '', true, true);
            header('Location: selectunidade.php');
            exit();
        } else {
            $erro_verificacao = "Código de verificação incorreto.";
        }
    }
}

$conngeral->close();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Verificação</title>
    <link rel="shortcut icon" href="../../img/logo.jpg" type="image/x-icon">
    <link rel="stylesheet" href="../../css/login.css">
    <link rel="stylesheet" href="../../css/backvalidacao.css">
</head>
<body>
    <?php if (isset($_SESSION['codigo_verificacao'])): ?>
        <div class="container loginverificacao">
        <form action="verificacao.php" method="post">
            <br>
            <label for="codigo_verificacao">Código de Verificação:</label>
            <br>
            <input type="text" id="codigo_verificacao" name="codigo_verificacao" placeholder="Insira o código de enviado para o seu telefone." required>
            <br>
            <?php if (isset($erro_verificacao)): ?>
            <label class="errovalida";"><?php echo htmlspecialchars($erro_verificacao); ?></label><br>
        <?php endif; ?>
    <?php endif; ?>
            <button type="submit">Confirmar</button>
        </form>
        </div>

</body>
</html>
