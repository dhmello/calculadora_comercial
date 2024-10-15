<?php
require_once 'init.php'; // Inclui a conexão com o banco de dados

try {
    // Cria a conexão com o banco de dados
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Consulta para obter os juros do Mercado Pago
    $stmt = $pdo->prepare("SELECT descricao, juros FROM juros_mercadopago ORDER BY id ASC");
    $stmt->execute();

    // Obtém os resultados como um array associativo
    $juros = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Retorna os resultados em formato JSON
    echo json_encode($juros);

} catch (PDOException $e) {
    echo 'Erro ao conectar ao banco de dados: ' . $e->getMessage();
}
