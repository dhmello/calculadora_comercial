<?php
// Incluir o arquivo de inicialização para conectar ao banco de dados
include 'init.php';

try {
    // Consulta para buscar a descrição, juros e número de parcelas do banco de dados
    $stmt = $pdo->query("SELECT descricao, juros, parcelas FROM juros_mbc");
    $jurosmazer = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Retornar os dados como JSON
    header('Content-Type: application/json');
    echo json_encode($jurosmazer);
} catch (Exception $e) {
    // Retornar um erro em caso de falha na consulta
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Erro ao carregar os dados dos juros: ' . $e->getMessage()]);
}
?>
