<?php
include 'init.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Atualizar os juros do MBC
    foreach ($_POST['juros_mbc'] as $descricao => $juros) {
        $stmt = $db->prepare("UPDATE juros_mbc SET juros = :juros WHERE descricao = :descricao");
        $stmt->execute([':juros' => $juros, ':descricao' => $descricao]);
    }

    // Atualizar os juros do PagSeguro
    foreach ($_POST['juros_pagseguro'] as $descricao => $juros) {
        $stmt = $db->prepare("UPDATE juros_pagseguro SET juros = :juros WHERE descricao = :descricao");
        $stmt->execute([':juros' => $juros, ':descricao' => $descricao]);
    }

    // Atualizar os juros do Mercado Pago
    foreach ($_POST['juros_mercadopago'] as $descricao => $juros) {
        $stmt = $db->prepare("UPDATE juros_mercadopago SET juros = :juros WHERE descricao = :descricao");
        $stmt->execute([':juros' => $juros, ':descricao' => $descricao]);
    }

    header("Location: admin.php");
    exit;
}
?>
