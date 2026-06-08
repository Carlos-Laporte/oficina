<?php

    session_start();
    require_once("../conexao.php");

    if (isset($_SESSION['adm_id'])) {
        header('Location: admin/index.php');
        exit;
    }

    $erro = '';


    if ($_SERVER['REQUEST_METHOD'] === 'POST') { 
        $email = $_POST['email'] ?? '';
        $senha = $_POST['senha'] ?? '';

        

        $stmt = $conn->prepare('SELECT id, nome, senha FROM adm WHERE email = :email');
        $stmt->bindValue(':email', $email);
        $stmt->execute();
        $adm = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($adm && password_verify($senha, $adm['senha'])) {
            $_SESSION['adm_id'] = $adm['id'];
            $_SESSION['adm_nome'] = $adm['nome'];
            header('Location: index.php');
            exit;
        } else {
            $erro = 'E-mail ou senha inválidos.';
        }

    }
?>

<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Login</title>
        <link rel="stylesheet" href="../public/css/styleLogin.css">
    </head>
    <body>
        <div class="corpoLogin">
            <div class="mensagemDeBoasVindas">
                <h2>Bem-vindo à Oficina</h2>
                <p>Faça login para acessar sua conta</p>
            </div>
            <div class="logo">
                <img src="../public/assets/logo.jpg" alt="Logo da Oficina">
            </div>
            <div class="formularioDeLogin">
                <h1>Login</h1>
                <form action="login.php" method="POST">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required>
                    <br>
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required>
                    <br>
                    <button type="submit">Login</button>
                </form>
            </div>
        </div>
    </body>
</html>