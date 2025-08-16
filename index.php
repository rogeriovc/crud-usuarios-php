<?php 
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}
if (isset($_SESSION['usuario_nome'])) {
    echo "<span class='usuario-nome'>Olá, " . htmlspecialchars($_SESSION['usuario_nome']) . "</span>";
    echo "<a href='logout.php' class='btn-logout'>Sair</a>";
}


include "conexao.php";

// Excluir usuário
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM usuarios WHERE id=$id");
    header("Location: index.php?deleted=1");

}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD PHP - Sistema de Usuários</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="container fade-in">
        <div class="header">
            <h2>Lista de Usuários</h2>
            <p>Sistema de Gerenciamento CRUD</p>
        </div>
        
        <div class="content">
            <a href="form.php" class="btn-add-main">
                <svg class="icon" viewBox="0 0 24 24">
                    <path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/>
                </svg>
                Adicionar Novo Usuário
            </a>

            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>
                                <svg class="icon" style="margin-right: 8px;" viewBox="0 0 24 24">
                                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                                </svg>
                                ID
                            </th>
                            <th>
                                <svg class="icon" style="margin-right: 8px;" viewBox="0 0 24 24">
                                    <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                                </svg>
                                Nome
                            </th>
                            <th>
                                <svg class="icon" style="margin-right: 8px;" viewBox="0 0 24 24">
                                    <path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/>
                                </svg>
                                Email
                            </th>
                            <th>
                                <svg class="icon" style="margin-right: 8px;" viewBox="0 0 24 24">
                                    <path d="M6.62 10.79c1.44 2.83 3.76 5.14 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"/>
                                </svg>
                                Telefone
                            </th>
                            <th>
                                <svg class="icon" style="margin-right: 8px;" viewBox="0 0 24 24">
                                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                </svg>
                                Ações
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $result = mysqli_query($conn, "SELECT * FROM usuarios ORDER BY id DESC");
                        if (mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo "<tr>
                                        <td><strong>#{$row['id']}</strong></td>
                                        <td>{$row['nome']}</td>
                                        <td>{$row['email']}</td>
                                        <td>{$row['telefone']}</td>
                                        <td>
                                            <div class='action-buttons'>
                                                <a href='form.php?edit={$row['id']}' class='btn btn-edit'>
                                                    <svg class='icon' viewBox='0 0 24 24'>
                                                        <path d='M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34c-.39-.39-1.02-.39-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z'/>
                                                    </svg>
                                                    Editar
                                                </a>
                                                <a href='index.php?delete={$row['id']}' 
                                                   class='btn btn-delete' 
                                                   onclick='return confirm(\"🗑️ Tem certeza que deseja excluir este usuário?\\n\\nEsta ação não pode ser desfeita!\")'>
                                                    <svg class='icon' viewBox='0 0 24 24'>
                                                        <path d='M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z'/>
                                                    </svg>
                                                    Excluir
                                                </a>
                                            </div>
                                        </td>
                                      </tr>";
                            }
                        } else {
                            echo "<tr>
                                    <td colspan='5' style='text-align: center; padding: 40px; color: #666;'>
                                        <svg class='icon' style='width: 48px; height: 48px; margin-bottom: 15px; opacity: 0.5;' viewBox='0 0 24 24'>
                                            <path d='M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z'/>
                                        </svg>
                                        <br>
                                        <strong>Nenhum usuário encontrado</strong>
                                        <br>
                                        <span style='font-size: 0.9rem; opacity: 0.7;'>Clique em 'Adicionar Novo Usuário' para começar</span>
                                    </td>
                                  </tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="script.js"></script>
</body>
</html>