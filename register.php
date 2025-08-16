<?php
include "conexao.php";
$msg = '';

if (isset($_POST['cadastrar'])) {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $telefone = $_POST['telefone'];
    $senha = $_POST['senha'];
    $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

    // Verificar se email já existe
    $query = "SELECT id FROM usuarios WHERE email = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);

    if (mysqli_stmt_num_rows($stmt) > 0) {
        $msg = "Email já cadastrado.";
    } else {
        $insert = "INSERT INTO usuarios (nome, email, telefone, senha_hash) VALUES (?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $insert);
        mysqli_stmt_bind_param($stmt, "ssss", $nome, $email,$telefone, $senha_hash);
        if (mysqli_stmt_execute($stmt)) {
            header("Location: login.php");
            exit;
        } else {
            $msg = "Erro ao cadastrar.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastro de Usuário</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container form-container">
        <h2>Cadastro de Usuário</h2>
        <?php if ($msg): ?>
            <p style="color:red;"><?php echo $msg; ?></p>
        <?php endif; ?>
        <form method="POST" action="">
            <div class="form-group">
                <label for="nome">Nome:</label>
                <input type="text" id="nome" name="nome" required placeholder="Seu nome completo">
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required placeholder="seu@exemplo.com">
            </div>
            <div class="form-group">
                <label for="telefone">Telefone:</label>
                <input type="text" id="telefone" name="telefone"  required placeholder="(DD) X XXXX-XXXX">
            </div>
            <div class="form-group">
                <label for="senha">Senha:</label>
                <input type="password" id="senha" name="senha" required placeholder="******">
            </div>
            <button type="submit" name="cadastrar" class="btn-submit">Cadastrar</button>
        </form>
        <a href="login.php" class="link-voltar">Já tem conta? Faça login</a>
        <a href="login.php">Já é cadastrado? Faça login</a>

    </div>
    <script>
    // Máscara de telefone (formato brasileiro)
    const telefoneInput = document.getElementById('telefone');
    telefoneInput.addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, ''); // remove tudo que não é número
        if (value.length > 11) value = value.slice(0, 11); // limita a 11 dígitos

        if (value.length > 6) {
            e.target.value = `(${value.slice(0, 2)}) ${value.slice(2, 7)}-${value.slice(7)}`;
        } else if (value.length > 2) {
            e.target.value = `(${value.slice(0, 2)}) ${value.slice(2)}`;
        } else {
            e.target.value = value;
        }
    });

    // Validação de email simples
    const form = document.querySelector('form');
    form.addEventListener('submit', function(e) {
        const email = document.getElementById('email').value;
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        if (!emailRegex.test(email)) {
            alert('Por favor, insira um email válido!');
            e.preventDefault();
        }
    });
</script>
</body>
</html>
