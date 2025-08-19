<?php
$adm_email = "rogervclaporta@gmail.com";

session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

include "conexao.php";

$id = "";
$nome = "";
$email = "";
$telefone = "";
$senha = "";

// Editar usuário
if (isset($_GET['edit'])) {
    $id = intval($_GET['edit']);
    $result = mysqli_query($conn, "SELECT * FROM usuarios WHERE id=$id");
    if ($row = mysqli_fetch_assoc($result)) {
        // Bloquear edição se não for o próprio usuário ou admin
        if ($_SESSION['usuario_id'] != $id && $_SESSION['usuario_email'] != $adm_email) {
            echo "<script>alert('Você não tem permissão para editar este usuário.'); window.location='index.php';</script>";
            exit;
        }

        $nome = $row['nome'];
        $email = $row['email'];
        $telefone = $row['telefone'];
        // senha não é mostrada por segurança!
    } else {
        echo "<script>alert('Usuário não encontrado.'); window.location='index.php';</script>";
        exit;
    }
}

// Salvar ou atualizar
if (isset($_POST['salvar'])) {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $telefone = $_POST['telefone'];
    $senha = $_POST['senha']; // novo campo

    // Novo usuário
    if ($_POST['id'] == "") {
        $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
        mysqli_query($conn, "INSERT INTO usuarios (nome, email, telefone, senha_hash) VALUES ('$nome', '$email', '$telefone', '$senha_hash')");
        header("Location: index.php?added=1");
        exit;
    } else {
        $id = intval($_POST['id']);
        // Verifica permissão para editar
        if ($_SESSION['usuario_id'] != $id && $_SESSION['usuario_email'] != $adm_email) {
            echo "<script>alert('Você não tem permissão para editar este usuário.'); window.location='index.php';</script>";
            exit;
        }

        if ($senha != "") {
            $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
            mysqli_query($conn, "UPDATE usuarios SET nome='$nome', email='$email', telefone='$telefone', senha_hash='$senha_hash' WHERE id=$id");
        } else {
            mysqli_query($conn, "UPDATE usuarios SET nome='$nome', email='$email', telefone='$telefone' WHERE id=$id");
        }
        header("Location: index.php?updated=1");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title><?php echo $id ? "Editar Usuário" : "Novo Usuário"; ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <div class="container">
        <div class="header">
            <h2><?php echo $id ? "Editar Usuário: " : "Sistema de CRUD: "; ?>Bem-Vindo!</h2>
        </div>

        <a href="index.php" class="link-voltar">← Visualizar Usuários</a>
        <div class="content">
            <div class="form-container"> 
                <h1><?php echo $id ? "Editar Usuário" : "Novo Usuário"; ?></h1>
<form method="POST">

    <input type="hidden" name="id" value="<?php echo $id; ?>">
    <div class="form-group">
        <label for="nome">Nome:</label>
        <input type="text" id="nome" name="nome" value="<?php echo $nome; ?>" required placeholder="Nome">
    </div>
    <div class="form-group">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?php echo $email; ?>" required placeholder="teste@gmail.com">
    </div>
    <div class="form-group">
        <label for="telefone">Telefone:</label>
        <input type="text" id="telefone" name="telefone" value="<?php echo $telefone; ?>" required placeholder="(DD) X XXXX-XXXX">
    </div>
    <div class="form-group">
        <label for="senha">Senha:</label>
        <input type="password" id="senha" name="senha" <?php echo $id ? "" : "required"; ?> placeholder="Digite a senha do usuário">
        <?php if ($id) echo "<small>Deixe em branco para não alterar a senha (Ou digite uma nova senha).</small>"; ?>
    </div>
    <button type="submit" name="salvar" class="btn-submit">Salvar</button>
</form>
</div>
</div>
    </div>

</body>
</html>

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
