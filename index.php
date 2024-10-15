<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Mazer - Ferramenta para Colegas</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="icon" type="image/png" href="assets/img/favicon.png">
</head>
<body>
<!-- Botão Admin -->
<a href="admin.php" class="admin-button"><i class="fas fa-user-cog"></i></a>

<!-- Contador de Usuarios Online -->
<div id="usuarios-online-container">
<i class="fas fa-eye"></i><p id="usuarios-online"></p>
</div>

<!-- Switch de Modo Escuro -->
<div class="switch-container">
    <label class="switch">
        <input type="checkbox" id="theme-switch" onclick="toggleTheme()">
        <span class="slider"></span>
    </label>
    <label for="theme-switch">Modo Escuro</label>
</div>

<div class="container">
    <!-- Abas -->
    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <?php
        // Defina a prioridade dos módulos
        $prioridade = ['calculadora', 'metas', 'boleto']; // Defina os módulos que devem aparecer primeiro (sem extensão)

        // Obtenha a lista de módulos
        $modules = glob('modulos/*.php');
        $moduleNames = array_map(function($module) {
            return basename($module, '.php');
        }, $modules);

        // Separe os módulos prioritários e os demais
        $modulosOrdenados = [];
        foreach ($prioridade as $prioritizedModule) {
            if (in_array($prioritizedModule, $moduleNames)) {
                $modulosOrdenados[] = $prioritizedModule;
                $moduleNames = array_diff($moduleNames, [$prioritizedModule]);
            }
        }

        // Ordene alfabeticamente os módulos restantes
        sort($moduleNames);

        // Combine os módulos prioritários com os restantes
        $modulosOrdenados = array_merge($modulosOrdenados, $moduleNames);

        // Renderize as abas com os módulos
        $first = true;
        foreach ($modulosOrdenados as $moduleName) {
            $tabName = strtoupper(str_replace('_', ' ', $moduleName));
            echo '<li class="nav-item">';
            echo '<a class="nav-link ' . ($first ? 'active' : '') . '" id="' . $moduleName . '-tab" data-toggle="tab" href="#' . $moduleName . '" role="tab" aria-controls="' . $moduleName . '" aria-selected="' . ($first ? 'true' : 'false') . '">' . $tabName . '</a>';
            echo '</li>';
            $first = false;
        }
        ?>
    </ul>

    <div class="tab-content" id="myTabContent">
        <?php
        $first = true;
        foreach ($modulosOrdenados as $moduleName) {
            echo '<div class="tab-pane fade ' . ($first ? 'show active' : '') . '" id="' . $moduleName . '" role="tabpanel" aria-labelledby="' . $moduleName . '-tab">';
            include 'modulos/' . $moduleName . '.php';
            echo '</div>';
            $first = false;
        }
        ?>
    </div>
</div>

<!-- Rodapé -->
<footer class="text-center mt-5">
    <p>Desenvolvido por Douglas Mello - <a href="https://github.com/dhmello">github.com/dhmello</a></p>
    <p style="color: red; font-weight: bold;">Atenção: Esta ferramenta é para uso interno e exclusivo da Mazer. Não compartilhe externamente.</p>
</footer>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<!-- Script para atualizar o contador de usuários em tempo real -->
<script>
function toggleTheme() {
    const body = document.body;
    const isDark = body.classList.toggle('dark-mode');
    localStorage.setItem('theme', isDark ? 'dark' : 'light');
}

document.addEventListener('DOMContentLoaded', () => {
    const theme = localStorage.getItem('theme');
    if (theme === 'dark') {
        document.body.classList.add('dark-mode');
        document.getElementById('theme-switch').checked = true; // Atualiza o checkbox corretamente
    }
});
</script>
<script>
function atualizarUsuariosOnline() {
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById("usuarios-online").innerHTML = this.responseText;
        }
    };
    xhttp.open("GET", "contador.php", true);
    xhttp.send();
}

// Atualiza a cada 10 segundos
setInterval(atualizarUsuariosOnline, 1000);

// Chama a função ao carregar a página
window.onload = atualizarUsuariosOnline;
</script>

</body>
</html>
