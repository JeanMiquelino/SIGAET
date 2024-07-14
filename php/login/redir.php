<?php
session_start();
function validateToken($token) {
    return isset($_SESSION['token']) && hash_equals($_SESSION['token'], $token);
}

// Verificar o token do cookie
if (isset($_COOKIE['session_token'])) {
    $token = $_COOKIE['session_token'];
    if (!validateToken($token)) {
        // Token inválido, tratar a autenticação falhou
        header('Location: ./login.php?erro=002');
    }
} else {
    // Token não encontrado, tratar a autenticação falhou
    header('Location: ./login.php?erro=003');
}
// Verifica se as variáveis de sessão existem

    // Verifica se 'tipouser' foi enviado via POST ou está na sessão
    if (isset($_POST['tipouser'])) {
        $tipouser = $_POST['tipouser'];
        $_SESSION['tipouser'] = $tipouser;
    } else {
        $tipouser = $_SESSION['tipouser'];
    }
switch ($tipouser) {
    case 'administrador':
        header("Location: ../SIGAET/Administracao/SIGAET.php");
        break;
    case 'aluno':
        header("Location: ../SIGAET/Aluno/SIGAET.php");
        break;
    case 'professor':
        header("Location: ../SIGAET/Professor/SIGAET.php");
        break;
    case 'coordenador':
        header("Location: ../SIGAET/Coordenacao/SIGAET.php");
        break;
        case 'orientador':
        header("Location: ../SIGAET/Orientacao/SIGAET.php");
        break;
        case 'supervisor':
        header("Location: ../SIGAET/Supervisao/SIGAET.php");
        break;
    default:
    header("Location: ../../html/login.html?erro=001");
}
?>
