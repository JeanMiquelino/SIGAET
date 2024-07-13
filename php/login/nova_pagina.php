<?php
session_start();

// Verifica se as variáveis de sessão existem
if (isset($_SESSION['login_time']) && isset($_SESSION['email']) && isset($_SESSION['unidade']) && isset($_SESSION['tipouser'])) {
    $login_time = $_SESSION['login_time'];
    $email = $_SESSION['email'];
    $unidade = $_SESSION['unidade'];
    $tipouser = $_SESSION['tipouser'];

    // Aqui você pode realizar as ações necessárias com essas informações
    echo "Login realizado em: $login_time<br>";
    echo "Email: $email<br>";
    echo "Unidade: $unidade<br>";
} else {
    echo "Informações de sessão não encontradas.";
}
?>
