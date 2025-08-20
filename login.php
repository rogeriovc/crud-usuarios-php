<?php
session_start();
include "conexao.php";

$msg = '';

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    $query = "SELECT * FROM usuarios WHERE email = ?";
    if ($stmt = mysqli_prepare($conn, $query)) {
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($row = mysqli_fetch_assoc($result)) {
            if (password_verify($senha, $row['senha_hash'])) {
                $_SESSION['usuario_id'] = $row['id'];
                $_SESSION['usuario_nome'] = $row['nome'];
                $_SESSION['usuario_email'] = $row['email'];
                header("Location: index.php");
                exit;
            } else {
                $msg = "Senha incorreta.";
            }
        } else {
            $msg = "Usuário não encontrado.";
        }
        mysqli_stmt_close($stmt);
    } else {
        die("Erro na preparação da query: " . mysqli_error($conn));
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container form-container">
        <h2>Login</h2>
        <?php if (!empty($msg)): ?>
            <p style="color:red;"><?php echo $msg; ?></p>
        <?php endif; ?>
        <form method="POST" action="">
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required placeholder="seu@exemplo.com">
            </div>
            <div class="form-group">
                <label for="senha">Senha:</label>
                <input type="password" id="senha" name="senha" required placeholder="********">
            </div>
            <button type="submit" name="login" class="btn-submit">Entrar</button>
            <a href="register.php" class="link-voltar">Ainda não tem conta? Cadastre-se</a>
        </form>
    </div>

    
    <script src="script.js"></script>

    <?php if (isset($_GET['sucesso']) && $_GET['sucesso'] == 1): ?>
    <script>
        showNotification("Usuário cadastrado com sucesso!", "success");

       
        if (window.history.replaceState) {
            const url = window.location.href.split("?")[0];
            window.history.replaceState({}, document.title, url);
        }
    </script>
    <?php endif; ?>
    <script src="mascaras.js" ></script>
</body>
</html>
