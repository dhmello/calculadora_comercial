<?php
// Diretório onde os certificados estão armazenados
$certDirectory = __DIR__;

// Verifica se o nome do certificado foi passado na URL
if (isset($_GET['cert'])) {
    $certName = basename($_GET['cert']);
    $filePath = $certDirectory . '/' . $certName;

    // Verifica se o arquivo existe e inicia o download
    if (file_exists($filePath)) {
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($filePath) . '"');
        header('Content-Length: ' . filesize($filePath));
        flush(); // Flush system output buffer
        readfile($filePath);
        exit;
    } else {
        echo "Certificado não encontrado.";
    }
} else {
    // Se nenhum certificado foi passado, listar os certificados disponíveis
    $certificates = array_merge(glob($certDirectory . "/*.pfx"), glob($certDirectory . "/*.key"));

    echo "<h1>Certificados Disponíveis</h1>";
    if (empty($certificates)) {
        echo "<p>Nenhum certificado encontrado.</p>";
    } else {
        echo "<ul>";
        foreach ($certificates as $cert) {
            $fileName = basename($cert);
            echo "<li><a href='?cert=$fileName'>$fileName</a></li>";
        }
        echo "</ul>";
    }
}
?>
