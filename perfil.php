<?php
require_once 'includes/functions.php';
require_login();

$page_title = 'Minha conta';
$user = current_user();
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $senhaAtual = $_POST['senha_atual'] ?? '';
    $novaSenha = $_POST['nova_senha'] ?? '';
    $confirmarSenha = $_POST['confirmar_senha'] ?? '';

    $statement = db()->prepare('SELECT senha FROM usuarios WHERE id = ?');
    $statement->execute([$_SESSION['user_id']]);
    $savedPassword = $statement->fetchColumn();

    if (!$savedPassword || !password_verify($senhaAtual, $savedPassword)) {
        $error = 'A senha atual está incorreta.';
    } elseif (strlen($novaSenha) < 8) {
        $error = 'A nova senha precisa ter pelo menos 8 caracteres.';
    } elseif ($novaSenha !== $confirmarSenha) {
        $error = 'A confirmação da nova senha não confere.';
    } elseif (password_verify($novaSenha, $savedPassword)) {
        $error = 'Escolha uma senha diferente da atual.';
    } else {
        $newHash = password_hash($novaSenha, PASSWORD_DEFAULT);
        $update = db()->prepare('UPDATE usuarios SET senha = ? WHERE id = ?');
        $update->execute([$newHash, $_SESSION['user_id']]);
        $success = 'Sua senha foi alterada com sucesso.';
    }
}

require 'includes/header.php';
?>
<section class="page-head account-head">
    <span class="eyebrow">Seu espaço pessoal</span>
    <h1>Minha <span class="purple">conta</span></h1>
    <p>Confira suas credenciais e mantenha sua conta segura.</p>
</section>

<section class="account-layout">
    <div class="account-card account-details">
        <div class="account-avatar"><?= e(mb_strtoupper(mb_substr($user['nome'] ?? 'U', 0, 1, 'UTF-8'), 'UTF-8')) ?></div>
        <h2><?= e($user['nome'] ?? 'Usuário') ?></h2>
        <p class="account-subtitle">Informações da sua conta FilmVerse</p>
        <div class="credential-row">
            <span>Nome</span>
            <strong><?= e($user['nome'] ?? '') ?></strong>
        </div>
        <div class="credential-row">
            <span>E-mail cadastrado</span>
            <strong><?= e($user['email'] ?? '') ?></strong>
        </div>
        <a class="btn btn-ghost account-list-link" href="favoritos.php">Ver minha lista</a>
    </div>

    <div class="account-card password-card">
        <h2>Alterar senha</h2>
        <p>Para sua segurança, confirme sua senha atual antes de escolher uma nova.</p>
        <?php if ($error): ?><div class="form-alert"><?= e($error) ?></div><?php endif; ?>
        <?php if ($success): ?><div class="flash success account-message"><?= e($success) ?></div><?php endif; ?>
        <form method="post">
            <?= csrf_field() ?>
            <label class="field">
                <span>Senha atual</span>
                <input type="password" name="senha_atual" autocomplete="current-password" required placeholder="Digite sua senha atual">
            </label>
            <label class="field">
                <span>Nova senha</span>
                <input type="password" name="nova_senha" autocomplete="new-password" minlength="8" required placeholder="Mínimo de 8 caracteres">
            </label>
            <label class="field">
                <span>Confirmar nova senha</span>
                <input type="password" name="confirmar_senha" autocomplete="new-password" minlength="8" required placeholder="Confirme a nova senha (mínimo de 8 caracteres)">
            </label>
            <div class="forgot-row account-forgot-row">
                <a class="forgot-link" href="redefinir-senha.php">Esqueceu sua senha?</a>
            </div>
            <button class="btn" type="submit">Salvar nova senha</button>
        </form>
    </div>
</section>
<?php require 'includes/footer.php'; ?>
