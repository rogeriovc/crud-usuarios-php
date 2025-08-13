<?php
include "conexao.php";

// Valores padrão
$id = "";
$nome = "";
$email = "";
$telefone = "";

// Editar usuário
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $result = mysqli_query($conn, "SELECT * FROM usuarios WHERE id=$id");
    $row = mysqli_fetch_assoc($result);
    $nome = $row['nome'];
    $email = $row['email'];
    $telefone = $row['telefone'];
}

// Salvar ou atualizar
if (isset($_POST['salvar'])) {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $telefone = $_POST['telefone'];

    if ($_POST['id'] == "") {
        mysqli_query($conn, "INSERT INTO usuarios (nome, email, telefone) VALUES ('$nome', '$email', '$telefone')");
        header("Location: index.php?added=1");  // Redireciona com parâmetro 'added' para novo usuário
        exit;
    } else {
        $id = $_POST['id'];
        mysqli_query($conn, "UPDATE usuarios SET nome='$nome', email='$email', telefone='$telefone' WHERE id=$id");
        header("Location: index.php?updated=1");  // Redireciona com parâmetro 'updated' para edição
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

    <div class="content">
        <div class="form-container"> 
            <form method="POST">
                <input type="hidden" name="id" value="<?php echo $id; ?>">

                <div class="form-group">
                    <label for="nome">Nome:</label>
                    <input type="text" id="nome" name="nome" value="<?php echo $nome; ?>" required placeholder="Nome" >
                </div>

                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" value="<?php echo $email; ?>" required  placeholder="teste@gmail.com">
                </div>

                <div class="form-group">
                    <label for="telefone">Telefone:</label>
                    <input type="text" id="telefone" name="telefone" value="<?php echo $telefone; ?>" required placeholder="(DD) X XXXX-XXXX">
                </div>

                <button type="submit" name="salvar" class="btn-submit">Salvar</button>
            </form>

            <a href="index.php" class="link-voltar">← Visualizar Usuários</a>
        </div>
    </div>
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
