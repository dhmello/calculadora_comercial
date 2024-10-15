<?php
session_start();
include 'init.php';

// Verifica se o usuário está logado
if (!isset($_SESSION['admin_logged_in_main'])) {
    header('Location: login.php');
    exit;
}

// Função para atualizar todos os juros, parcelas e descrições
if (isset($_POST['update_all'])) {
    $ids = $_POST['ids'];
    $juros = $_POST['juros'];
    $parcelas = $_POST['parcelas'];
    $descricoes = $_POST['descricoes'];
    $table = $_POST['table'];

    foreach ($ids as $index => $id) {
        if (is_numeric($juros[$index]) && is_numeric($parcelas[$index])) {
            $stmt = $pdo->prepare("UPDATE $table SET descricao = :descricao, juros = :juros, parcelas = :parcelas WHERE id = :id");
            $stmt->execute([
                'descricao' => $descricoes[$index],
                'juros' => $juros[$index],
                'parcelas' => $parcelas[$index],
                'id' => $id
            ]);
        }
    }
    
    // Definindo a mensagem de sucesso com a classe CSS de sucesso
    $message = '<div class="alert alert-success">Todas as condições foram atualizadas com sucesso!</div>';
}

// Função para deletar uma condição
if (isset($_POST['delete_condition'])) {
    $id = $_POST['id'];
    $table = $_POST['table'];

    $stmt = $pdo->prepare("DELETE FROM $table WHERE id = :id");
    $success = $stmt->execute(['id' => $id]);

    if ($success) {
        $message = "Condição deletada com sucesso!";
    } else {
        $message = "Erro ao deletar condição.";
    }
}

// Função para adicionar uma nova condição
if (isset($_POST['add_condition'])) {
    $descricao = $_POST['descricao'];
    $juros = $_POST['juros'];
    $parcelas = $_POST['parcelas'];
    $table = $_POST['table'];

    if (!empty($descricao) && is_numeric($juros) && is_numeric($parcelas) && !empty($table)) {
        $stmt = $pdo->prepare("INSERT INTO $table (descricao, juros, parcelas) VALUES (:descricao, :juros, :parcelas)");
        $success = $stmt->execute(['descricao' => $descricao, 'juros' => $juros, 'parcelas' => $parcelas]);

        if ($success) {
            $message = "Nova condição adicionada com sucesso!";
        } else {
            $message = "Erro ao adicionar nova condição.";
        }
    } else {
        $message = "Valores inválidos fornecidos.";
    }
}

// Função para buscar os juros de cada tabela
function getJuros($pdo, $table) {
    $stmt = $pdo->prepare("SELECT * FROM $table");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

$juros_mazer = getJuros($pdo, 'juros_mazer');
$juros_mbc = getJuros($pdo, 'juros_mbc');
$juros_mercadopago = getJuros($pdo, 'juros_mercadopago');
$juros_pagseguro = getJuros($pdo, 'juros_pagseguro');
$juros_lenovo = getJuros($pdo, 'juros_lenovo');
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Painel de Administração</title>
    <link rel="stylesheet" href="assets/css/admin-style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="admin-container">
        <div class="admin-header">
            <a href="alterar_senha.php" class="admin-button">Alterar Senha</a>
            <a href="logout.php" class="admin-button">Sair</a>
        </div>

        <?php if (!empty($message)): ?>
            <div class="alert"><?php echo $message; ?></div>
        <?php endif; ?>

        <!-- Contêiner de Juros mazer -->
        <div class="card">
            <div class="card-header">
                <h2>Juros Mazer</h2>
            </div>
            <div class="card-body">
                <form method="post">
                    <input type="hidden" name="table" value="juros_mazer">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Descrição</th>
                                <th>Juros (%)</th>
                                <th>Parcelas</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($juros_mazer as $index => $row): ?>
                                <tr>
                                    <td><?php echo $row['id']; ?></td>
                                    <td><input type="text" name="descricoes[]" value="<?php echo $row['descricao']; ?>"></td>
                                    <td>
                                        <input type="number" name="juros[]" value="<?php echo $row['juros']; ?>" step="0.01">
                                        <input type="hidden" name="ids[]" value="<?php echo $row['id']; ?>">
                                    </td>
                                    <td>
                                        <input type="number" name="parcelas[]" value="<?php echo $row['parcelas']; ?>" step="1">
                                    </td>
                                    <td>
                                        <form method="post">
                                            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                            <input type="hidden" name="table" value="juros_mazer">
                                            <button type="submit" name="delete_condition" class="delete-button">Deletar</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <button type="submit" name="update_all" class="save-all-button">Salvar Todas</button>
                </form>
                <!-- Formulário para adicionar nova condição -->
                <form method="post" class="add-condition-form">
                    <input type="text" name="descricao" placeholder="Nova Descrição" required>
                    <input type="number" name="juros" step="0.01" placeholder="Novo Juros (%)" required>
                    <input type="number" name="parcelas" step="1" placeholder="Parcelas" required>
                    <input type="hidden" name="table" value="juros_mazer">
                    <button type="submit" name="add_condition" class="add-button"><i class="fas fa-plus"></i> Adicionar Nova Condição</button>
                </form>
            </div>
        </div>
        <!-- Contêiner de Juros MBC -->
        <div class="card">
            <div class="card-header">
                <h2>Juros MBC</h2>
            </div>
            <div class="card-body">
                <form method="post">
                    <input type="hidden" name="table" value="juros_mbc">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Descrição</th>
                                <th>Juros (%)</th>
                                <th>Parcelas</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($juros_mbc as $index => $row): ?>
                                <tr>
                                    <td><?php echo $row['id']; ?></td>
                                    <td><input type="text" name="descricoes[]" value="<?php echo $row['descricao']; ?>"></td>
                                    <td>
                                        <input type="number" name="juros[]" value="<?php echo $row['juros']; ?>" step="0.01">
                                        <input type="hidden" name="ids[]" value="<?php echo $row['id']; ?>">
                                    </td>
                                    <td>
                                        <input type="number" name="parcelas[]" value="<?php echo $row['parcelas']; ?>" step="1">
                                    </td>
                                    <td>
                                        <form method="post">
                                            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                            <input type="hidden" name="table" value="juros_mbc">
                                            <button type="submit" name="delete_condition" class="delete-button">Deletar</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <button type="submit" name="update_all" class="save-all-button">Salvar Todas</button>
                </form>
                <!-- Formulário para adicionar nova condição -->
                <form method="post" class="add-condition-form">
                    <input type="text" name="descricao" placeholder="Nova Descrição" required>
                    <input type="number" name="juros" step="0.01" placeholder="Novo Juros (%)" required>
                    <input type="number" name="parcelas" step="1" placeholder="Parcelas" required>
                    <input type="hidden" name="table" value="juros_mbc">
                    <button type="submit" name="add_condition" class="add-button"><i class="fas fa-plus"></i> Adicionar Nova Condição</button>
                </form>
            </div>
        </div>
        <!-- Contêiner de Juros Lenovo -->
        <div class="card">
            <div class="card-header">
                <h2>Juros Lenovo</h2>
            </div>
            <div class="card-body">
                <form method="post">
                    <input type="hidden" name="table" value="juros_lenovo">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Descrição</th>
                                <th>Juros (%)</th>
                                <th>Parcelas</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($juros_lenovo as $index => $row): ?>
                                <tr>
                                    <td><?php echo $row['id']; ?></td>
                                    <td><input type="text" name="descricoes[]" value="<?php echo $row['descricao']; ?>"></td>
                                    <td>
                                        <input type="number" name="juros[]" value="<?php echo $row['juros']; ?>" step="0.01">
                                        <input type="hidden" name="ids[]" value="<?php echo $row['id']; ?>">
                                    </td>
                                    <td>
                                        <input type="number" name="parcelas[]" value="<?php echo $row['parcelas']; ?>" step="1">
                                    </td>
                                    <td>
                                        <form method="post">
                                            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                            <input type="hidden" name="table" value="juros_lenovo">
                                            <button type="submit" name="delete_condition" class="delete-button">Deletar</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <button type="submit" name="update_all" class="save-all-button">Salvar Todas</button>
                </form>
                <!-- Formulário para adicionar nova condição -->
                <form method="post" class="add-condition-form">
                    <input type="text" name="descricao" placeholder="Nova Descrição" required>
                    <input type="number" name="juros" step="0.01" placeholder="Novo Juros (%)" required>
                    <input type="number" name="parcelas" step="1" placeholder="Parcelas" required>
                    <input type="hidden" name="table" value="juros_lenovo">
                    <button type="submit" name="add_condition" class="add-button"><i class="fas fa-plus"></i> Adicionar Nova Condição</button>
                </form>
            </div>
        </div>
        <!-- Repita a estrutura para MercadoPago e PagSeguro -->

        <!-- Contêiner de Juros MercadoPago -->
        <div class="card">
            <div class="card-header">
                <h2>Juros MercadoPago</h2>
            </div>
            <div class="card-body">
                <form method="post">
                    <input type="hidden" name="table" value="juros_mercadopago">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Descrição</th>
                                <th>Juros (%)</th>
                                <th>Parcelas</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($juros_mercadopago as $index => $row): ?>
                                <tr>
                                    <td><?php echo $row['id']; ?></td>
                                    <td><input type="text" name="descricoes[]" value="<?php echo $row['descricao']; ?>"></td>
                                    <td>
                                        <input type="number" name="juros[]" value="<?php echo $row['juros']; ?>" step="0.01">
                                        <input type="hidden" name="ids[]" value="<?php echo $row['id']; ?>">
                                    </td>
                                    <td>
                                        <input type="number" name="parcelas[]" value="<?php echo $row['parcelas']; ?>" step="1">
                                    </td>
                                    <td>
                                        <form method="post">
                                            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                            <input type="hidden" name="table" value="juros_mercadopago">
                                            <button type="submit" name="delete_condition" class="delete-button">Deletar</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <button type="submit" name="update_all" class="save-all-button">Salvar Todas</button>
                </form>
                <!-- Formulário para adicionar nova condição -->
                <form method="post" class="add-condition-form">
                    <input type="text" name="descricao" placeholder="Nova Descrição" required>
                    <input type="number" name="juros" step="0.01" placeholder="Novo Juros (%)" required>
                    <input type="number" name="parcelas" step="1" placeholder="Parcelas" required>
                    <input type="hidden" name="table" value="juros_mercadopago">
                    <button type="submit" name="add_condition" class="add-button"><i class="fas fa-plus"></i> Adicionar Nova Condição</button>
                </form>
            </div>
        </div>

        <!-- Contêiner de Juros PagSeguro -->
        <div class="card">
            <div class="card-header">
                <h2>Juros PagSeguro</h2>
            </div>
            <div class="card-body">
                <form method="post">
                    <input type="hidden" name="table" value="juros_pagseguro">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Descrição</th>
                                <th>Juros (%)</th>
                                <th>Parcelas</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($juros_pagseguro as $index => $row): ?>
                                <tr>
                                    <td><?php echo $row['id']; ?></td>
                                    <td><input type="text" name="descricoes[]" value="<?php echo $row['descricao']; ?>"></td>
                                    <td>
                                        <input type="number" name="juros[]" value="<?php echo $row['juros']; ?>" step="0.01">
                                        <input type="hidden" name="ids[]" value="<?php echo $row['id']; ?>">
                                    </td>
                                    <td>
                                        <input type="number" name="parcelas[]" value="<?php echo $row['parcelas']; ?>" step="1">
                                    </td>
                                    <td>
                                        <form method="post">
                                            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                            <input type="hidden" name="table" value="juros_pagseguro">
                                            <button type="submit" name="delete_condition" class="delete-button">Deletar</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <button type="submit" name="update_all" class="save-all-button">Salvar Todas</button>
                </form>
                <!-- Formulário para adicionar nova condição -->
                <form method="post" class="add-condition-form">
                    <input type="text" name="descricao" placeholder="Nova Descrição" required>
                    <input type="number" name="juros" step="0.01" placeholder="Novo Juros (%)" required>
                    <input type="number" name="parcelas" step="1" placeholder="Parcelas" required>
                    <input type="hidden" name="table" value="juros_pagseguro">
                    <button type="submit" name="add_condition" class="add-button"><i class="fas fa-plus"></i> Adicionar Nova Condição</button>
                </form>
            </div>
        </div>

    </div>
</body>
</html>
