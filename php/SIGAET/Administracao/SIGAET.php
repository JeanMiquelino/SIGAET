<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../../css/navbar.css">
    <link rel="stylesheet" href="../../../css/SIGAET/Administracao/SIGAET.css">
    <title>SIGAET</title>
</head>
<body>
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
        if ($tipouser !== "administrador") {
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
<div id="nav-bar">
  <input id="nav-toggle" type="checkbox"/>
  <div id="nav-header"><a id="nav-title" href="https://codepen.io" target="_blank">C<i class="fab fa-codepen"></i>DEPEN</a>
    <label for="nav-toggle"><span id="nav-toggle-burger"></span></label>
    <hr/>
  </div>
  <div id="nav-content">
    <div class="nav-button"><i class="fas fa-palette"></i><span>Your Work</span></div>
    <div class="nav-button"><i class="fas fa-images"></i><span>Assets</span></div>
    <div class="nav-button"><i class="fas fa-thumbtack"></i><span>Pinned Items</span></div>
    <hr/>
    <div class="nav-button"><i class="fas fa-heart"></i><span>Following</span></div>
    <div class="nav-button"><i class="fas fa-chart-line"></i><span>Trending</span></div>
    <div class="nav-button"><i class="fas fa-fire"></i><span>Challenges</span></div>
    <div class="nav-button"><i class="fas fa-magic"></i><span>Spark</span></div>
    <hr/>
    <div class="nav-button"><i class="fas fa-gem"></i><span>Codepen Pro</span></div>
    <div id="nav-content-highlight"></div>
  </div>
  <input id="nav-footer-toggle" type="checkbox"/>
  <div id="nav-footer">
    <div id="nav-footer-heading">
      <div id="nav-footer-avatar"><img src="https://gravatar.com/avatar/4474ca42d303761c2901fa819c4f2547"/></div>
      <div id="nav-footer-titlebox"><a id="nav-footer-title" href="https://codepen.io/uahnbu/pens/public" target="_blank"><?php echo $_SESSION['nome'];?></a><span id="nav-footer-subtitle"><?php echo mb_convert_case(htmlspecialchars($tipouser), MB_CASE_TITLE, 'UTF-8');?></span></div>
      <label for="nav-footer-toggle"><i class="fas fa-caret-up"></i></label>
    </div>
    <div id="nav-footer-content">
      <Lorem>ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</Lorem>
    </div>
  </div>
</div>
</body>
</html>