<?php
session_start();

// Conexão com o banco de dados
include 'init.php';

// Verificar se há um administrador registrado, se não, cria um administrador padrão
$checkAdmin = $pdo->query("SELECT COUNT(*) FROM admins")->fetchColumn();
if ($checkAdmin == 0) {
    $defaultUsername = 'admin';
    $defaultPassword = password_hash('admin123', PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT INTO admins (username, password) VALUES (?, ?)");
    $stmt->execute([$defaultUsername, $defaultPassword]);
}

// Processo de login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM admins WHERE username = ?");
    $stmt->execute([$username]);
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($admin && password_verify($password, $admin['password'])) {
        // Definir a sessão para o admin
        $_SESSION['admin_logged_in_main'] = true;
        $_SESSION['admin_username'] = $username;

        // Redirecionar para o painel de administração
        header('Location: admin.php');
        exit();
    } else {
        $error = 'Nome de usuário ou senha incorretos.';
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Login Admin</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/admin-style.css">
</head>
<body class="admin-body">
    <div class="login-container">
        <div class="login-card">
            <h2 class="text-center">Login Admin</h2>
            <?php if (isset($error)): ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>
            <form action="login.php" method="post">
                <div class="form-group">
                    <label for="username">Nome de Usuário:</label>
                    <input type="text" class="form-control" id="username" name="username" required>
                </div>
                <div class="form-group">
                    <label for="password">Senha:</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <button type="submit" class="btn btn-primary btn-block">Entrar</button>
            </form>
        </div>
    </div>
</body>
</html>
