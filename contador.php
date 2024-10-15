<?php
session_start();

// Definir tempo máximo de inatividade (em segundos)
$tempo_inatividade = 1; // 1 minutos

// Definir o caminho para o arquivo de registros de usuários online
$file = 'usuarios_online.txt';

// Obter o identificador da sessão atual
$id_sessao = session_id();

// Obter o horário atual
$agora = time();

// Verificar se o arquivo já existe
if (!file_exists($file)) {
    // Criar o arquivo se ele não existir
    file_put_contents($file, '');
}

// Ler o conteúdo do arquivo
$conteudo = file_get_contents($file);
$usuarios_online = $conteudo ? unserialize($conteudo) : [];

// Atualizar o horário do usuário atual ou adicionar um novo usuário
$usuarios_online[$id_sessao] = $agora;

// Remover sessões inativas
foreach ($usuarios_online as $sessao => $ultimo_acesso) {
    if ($agora - $ultimo_acesso > $tempo_inatividade) {
        unset($usuarios_online[$sessao]);
    }
}

// Salvar a lista atualizada de usuários online
file_put_contents($file, serialize($usuarios_online));

// Contar o número de usuários online
$num_usuarios_online = count($usuarios_online);

// Exibir o número de usuários online
echo $num_usuarios_online;
?>
