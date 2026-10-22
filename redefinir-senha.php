<?php
require_once 'includes/functions.php';

$page_title = 'Recuperar senha';
$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $email = strtolower(trim($_POST['email'] ?? ''));

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Informe um e-mail válido.';
    } else {
        try {
            $statement = db()->prepare('SELECT id FROM usuarios WHERE email = ?');
            $statement->execute([$email]);
            $message = 'Se existir uma conta associada a esse e-mail, ela poderá receber instruções de recuperação. Esta versão é demonstrativa: nenhum e-mail foi enviado e nenhuma senha foi alterada.';
        } catch (Throwable $exception) {
            $error = 'Não foi possível processar a solicitação agora. Tente novamente mais tarde.';
        }
    }
}

require 'includes/header.php';
?>
<section class="form-shell auth-recovery">
    <div class="form-art">
        <span class="eyebrow">Sem preocupação</span>
        <h2>Vamos te ajudar<br>a voltar.</h2>
        <p>Recupere o acesso à sua conta e continue explorando o universo FilmVerse.</p>
        <div class="auth-art-badges">
            <span>Recuperação de acesso</span>
            <span>Sua conta protegida</span>
        </div>
    </div>

    <div class="form-panel">
        <span class="eyebrow recovery-eyebrow">Recuperação de acesso</span>
        <h1>Esqueceu sua senha?</h1>
        <p>Digite o e-mail cadastrado para iniciar a recuperação da sua conta.</p>

        <?php if ($error): ?>
            <div class="form-alert"><?= e($error) ?></div>
        <?php endif; ?>
        <?php if ($message): ?>
            <div class="flash success recovery-message"><?= e($message) ?></div>
        <?php endif; ?>

        <form method="post">
            <?= csrf_field() ?>
            <label class="field">
                <span>E-mail cadastrado</span>
                <input type="email" name="email" autocomplete="email" required value="<?= e($_POST['email'] ?? '') ?>" placeholder="voce@email.com">
            </label>
            <button class="btn" type="submit">Solicitar recuperação →</button>
        </form>

        <p class="form-note recovery-back"><a href="login.php">← Lembrou da senha? Voltar para entrar</a></p>
    </div>
</section>
<?php require 'includes/footer.php'; ?>
