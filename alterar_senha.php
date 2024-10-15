<?php
session_start();
include 'init.php';

// Verifica se o usuário está logado
if (!isset($_SESSION['admin_logged_in_main'])) {
    header('Location: login.php');
    exit;
}

// Mensagens de erro e sucesso
$error = '';
$success = '';

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $currentPassword = $_POST['current_password'];
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];

    // Verifica se a nova senha e a confirmação são iguais
    if ($newPassword !== $confirmPassword) {
        $error = 'A nova senha e a confirmação não correspondem.';
    } else {
        // Obtém o usuário logado
        $stmt = $pdo->prepare("SELECT * FROM admins WHERE username = ?");
        $stmt->execute([$_SESSION['admin_username']]);
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verifica se a senha atual está correta
        if ($admin && password_verify($currentPassword, $admin['password'])) {
            // Atualiza a senha no banco de dados
            $newPasswordHashed = password_hash($newPassword, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("UPDATE admins SET password = ? WHERE username = ?");
            $stmt->execute([$newPasswordHashed, $_SESSION['admin_username']]);

            $success = 'Senha alterada com sucesso!';
        } else {
            $error = 'Senha atual incorreta.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Alterar Senha</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/admin-style.css">
</head>
<body class="admin-body">
    <div class="alterar-senha-container">
        <div class="alterar-senha-card">
            <h2 class="text-center">Alterar Senha</h2>
            <?php if ($error): ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>
            <?php if ($success): ?>
                <div class="alert alert-success" role="alert">
                    <?php echo $success; ?>
                </div>
            <?php endif; ?>
            <form action="alterar_senha.php" method="post">
                <div class="form-group">
                    <label for="current_password">Senha Atual:</label>
                    <input type="password" class="form-control" id="current_password" name="current_password" required>
                </div>
                <div class="form-group">
                    <label for="new_password">Nova Senha:</label>
                    <input type="password" class="form-control" id="new_password" name="new_password" required>
                </div>
                <div class="form-group">
                    <label for="confirm_password">Confirmar Nova Senha:</label>
                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                </div>
                <button type="submit" class="btn btn-primary btn-block">Alterar Senha</button>
            </form>
        </div>
    </div>
</body>
</html>
