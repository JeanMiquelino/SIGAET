<?php
session_start();

$email = $_POST['email'];
$pass = $_POST['pass'];

// Conexão com o banco de dados
$conngeral = new mysqli("localhost", "root", "", "eusers");
if ($conngeral->connect_error) {
    die("Connection failed: " . $conngeral->connect_error);
}

// Evita SQL Injection
$email = $conngeral->real_escape_string($email);

// Executa a consulta
$resultadogeral = $conngeral->query("SELECT unidade FROM users WHERE email = '$email'");
if ($resultadogeral && $resultadogeral->num_rows > 0) {
    $rowgeral = $resultadogeral->fetch_assoc();
    $unidade = $rowgeral['unidade'];

    // Conexão com o banco de dados da unidade
    $connuni = new mysqli("localhost", "root", "", "e".$unidade);
    if ($connuni->connect_error) {
        die("Connection failed: " . $connuni->connect_error);
    }

    // Executa a consulta na unidade
    $resultadouni = $connuni->query("SELECT * FROM users WHERE email = '$email'");
    if ($resultadouni && $resultadouni->num_rows > 0) {
        $rowuni = $resultadouni->fetch_assoc();
        if ($rowuni['pass'] != $pass) {
            echo "<h1 class='enu'>Senha incorreta.</h1>";
            exit();
        }
        $tipouser = $rowuni['tipouser'];

        // Armazena informações na sessão
        $_SESSION['login_time'] = date("Y-m-d H:i:s");
        $_SESSION['email'] = $email;
        $_SESSION['unidade'] = $unidade;

        // Contagem dos tipos de usuário
        $tipos = explode(",", $tipouser);
        $tiposContagem = array_count_values($tipos);
        
        // Calcula a quantidade total
        $totalUsuarios = array_sum($tiposContagem);
        echo "<br>Total de usuários: $totalUsuarios";

        if ($totalUsuarios == 1) {
            // Redireciona automaticamente para a nova página
            header("Location: nova_pagina.php");
            exit();
        } else {
            $contador = 0;
            while ($totalUsuarios > $contador) {
                echo "<form method='post' action='nova_pagina.php'>";
                echo "<button type='submit'>".$tipos[$contador]."</button>";
                echo "</form>";
                $contador++;
            }
        }

        // Fecha a conexão com a unidade
        $connuni->close();
    } else {
        echo "<h1 class='enu'>Senha incorreta ou usuário não encontrado.</h1>";
    }
} else {
    echo "<h1 class='enu'>Nenhum resultado encontrado para o e-mail informado.</h1>";
}

// Fecha a conexão geral
$conngeral->close();
?>
