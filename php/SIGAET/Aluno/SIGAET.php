<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIGAET</title>
</head>
<body>
    <h1>Aluno</h1>

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
}else{
  $tipouser = $_SESSION['tipouser'];
  if ($tipouser !== "aluno") {
    header('Location: ./login.php?erro=001');
    exit();
  }
}
?>

</body>
</html>