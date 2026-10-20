<?php
require_once 'includes/functions.php';

if (logged_in()) {
    header('Location: index.php');
    exit;
}

$page_title = 'Entrar';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();

    $email = strtolower(trim($_POST['email'] ?? ''));
    $senha = $_POST['senha'] ?? '';

    try {
        $statement = db()->prepare(
            'SELECT id, nome, email, senha FROM usuarios WHERE email = ?'
        );
        $statement->execute([$email]);
        $user = $statement->fetch();

        if ($user && password_verify($senha, $user['senha'])) {
            session_regenerate_id(true);
            $_SESSION['user_id'] = $user['id'];
            flash('Bem-vindo de volta, ' . $user['nome'] . '!');

            $next = $_GET['next'] ?? $_POST['next'] ?? 'index.php';
            if (!preg_match('/^[a-zA-Z0-9_-]+\.php(\?.*)?$/', $next)) {
                $next = 'index.php';
            }

            header('Location: ' . $next);
            exit;
        }

        $error = 'E-mail ou senha incorretos.';
    } catch (Throwable $exception) {
        $error = 'Não foi possível conectar ao banco. Importe o arquivo database.sql e confira config.php.';
    }
}

require 'includes/header.php';
?>

<section class="form-shell auth-login">
    <div class="form-art">
        <span class="eyebrow">Que bom ter você aqui</span>
        <h2>Histórias incríveis<br>te esperam.</h2>
        <p>Volte para sua lista, encontre novas histórias e continue de onde parou.</p>
        <div class="auth-art-badges">
            <span>Filmes</span>
            <span>Séries</span>
            <span>Animes</span>
            <span>Jogos</span>
        </div>
    </div>

    <div class="form-panel">
        <h1>Bem-vindo de volta.</h1>
        <p>Entre na sua conta para acessar sua lista pessoal.</p>

        <?php if ($error): ?>
            <div class="form-alert"><?= e($error) ?></div>
        <?php endif; ?>

        <form method="post">
            <?= csrf_field() ?>
            <input type="hidden" name="next" value="<?= e($_GET['next'] ?? 'index.php') ?>">

            <label class="field">
                <span>E-mail</span>
                <input
                    type="email"
                    name="email"
                    autocomplete="email"
                    required
                    value="<?= e($_POST['email'] ?? '') ?>"
                    placeholder="voce@email.com"
                >
            </label>

            <label class="field">
                <span>Senha</span>
                <input
                    type="password"
                    name="senha"
                    autocomplete="current-password"
                    required
                    placeholder="Sua senha"
                >
            </label>

            <div class="forgot-row">
                <a class="forgot-link" href="redefinir-senha.php">Esqueceu sua senha?</a>
            </div>

            <button class="btn" type="submit">Entrar na plataforma →</button>
        </form>

        <p class="form-note">Ainda não tem conta? <a href="cadastro.php">Cadastre-se grátis</a></p>
    </div>
</section>

<?php require 'includes/footer.php'; ?>
