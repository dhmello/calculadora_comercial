<?php
$host = 'localhost';
$dbname = '';
$username = '';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro na conexão: " . $e->getMessage());
}

// Verificação e criação das tabelas necessárias para o diretório principal
$tables = [
    'admins' => "CREATE TABLE IF NOT EXISTS admins (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(255) NOT NULL,
        password VARCHAR(255) NOT NULL
    )",
    'juros_mazer' => "CREATE TABLE IF NOT EXISTS juros_mazer (
        id INT AUTO_INCREMENT PRIMARY KEY,
        descricao VARCHAR(255) NOT NULL,
        juros DECIMAL(5,2) NOT NULL
    )",
    'juros_lenovo' => "CREATE TABLE IF NOT EXISTS juros_lenovo (
        id INT AUTO_INCREMENT PRIMARY KEY,
        descricao VARCHAR(255) NOT NULL,
        juros DECIMAL(5,2) NOT NULL
    )",
    'juros_mercadopago' => "CREATE TABLE IF NOT EXISTS juros_mercadopago (
        id INT AUTO_INCREMENT PRIMARY KEY,
        descricao VARCHAR(255) NOT NULL,
        juros DECIMAL(5,2) NOT NULL
    )",
    'juros_pagseguro' => "CREATE TABLE IF NOT EXISTS juros_pagseguro (
        id INT AUTO_INCREMENT PRIMARY KEY,
        descricao VARCHAR(255) NOT NULL,
        juros DECIMAL(5,2) NOT NULL
    )"
];

foreach ($tables as $name => $sql) {
    $pdo->exec($sql);
}

// Criação do usuário admin padrão se não existir
$stmt = $pdo->prepare("SELECT COUNT(*) FROM admins");
$stmt->execute();
if ($stmt->fetchColumn() == 0) {
    $password = password_hash('admin123', PASSWORD_DEFAULT);
    $pdo->prepare("INSERT INTO admins (username, password) VALUES ('admin', :password)")
        ->execute(['password' => $password]);
}
?>