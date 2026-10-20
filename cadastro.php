<?php
require_once 'includes/functions.php';

$page_title = 'Criar conta';
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();

    $nome = trim($_POST['nome'] ?? '');
    $email = strtolower(trim($_POST['email'] ?? ''));
    $senha = $_POST['senha'] ?? '';
    $confirmarSenha = $_POST['confirmar_senha'] ?? '';

    if (mb_strlen($nome) < 2) {
        $errors[] = 'Informe seu nome completo.';
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Informe um e-mail válido.';
    }
    if (strlen($senha) < 8) {
        $errors[] = 'A senha precisa ter pelo menos 8 caracteres.';
    }
    if ($senha !== $confirmarSenha) {
        $errors[] = 'As senhas não coincidem.';
    }

    if (!$errors) {
        try {
            $statement = db()->prepare(
                'INSERT INTO usuarios (nome, email, senha) VALUES (?, ?, ?)'
            );
            $statement->execute([
                $nome,
                $email,
                password_hash($senha, PASSWORD_DEFAULT),
            ]);

            send_owner_email($nome, $email);
            flash('Conta criada com sucesso! Agora você já pode entrar.');
            header('Location: login.php');
            exit;
        } catch (PDOException $exception) {
            if ($exception->getCode() === '23000') {
                $errors[] = 'Este e-mail já está cadastrado.';
            } else {
                $errors[] = 'Não foi possível concluir o cadastro. Confira a conexão com o banco de dados.';
            }
        }
    }
}

require 'includes/header.php';
?>

<section class="form-shell auth-register">
    <div class="form-art">
        <span class="eyebrow">Faça parte</span>
        <h2>Seu próximo universo<br>está te esperando.</h2>
        <p>Crie sua conta e organize os conteúdos que quer assistir, jogar e descobrir.</p>
        <div class="auth-art-badges">
            <span>Sua lista pessoal</span>
            <span>Novas descobertas</span>
        </div>
    </div>

    <div class="form-panel">
        <h1>Criar conta</h1>
        <p>Preencha os dados para começar sua jornada no FilmVerse.</p>

        <?php if ($errors): ?>
            <div class="form-alert">
                <?php foreach ($errors as $error): ?>
                    <div><?= e($error) ?></div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form method="post">
            <?= csrf_field() ?>

            <label class="field">
                <span>Nome completo</span>
                <input
                    name="nome"
                    autocomplete="name"
                    value="<?= e($_POST['nome'] ?? '') ?>"
                    required
                    minlength="2"
                    placeholder="Como podemos te chamar?"
                >
            </label>

            <label class="field">
                <span>E-mail</span>
                <input
                    type="email"
                    name="email"
                    autocomplete="email"
                    value="<?= e($_POST['email'] ?? '') ?>"
                    required
                    placeholder="voce@email.com"
                >
            </label>

            <label class="field">
                <span>Senha</span>
                <input
                    type="password"
                    name="senha"
                    autocomplete="new-password"
                    required
                    minlength="8"
                    placeholder="Mínimo de 8 caracteres"
                >
            </label>

            <label class="field">
                <span>Confirmar senha</span>
                <input
                    type="password"
                    name="confirmar_senha"
                    autocomplete="new-password"
                    required
                    placeholder="Digite a senha novamente"
                >
            </label>

            <button class="btn" type="submit">Criar minha conta →</button>
        </form>

        <p class="form-note">Já tem uma conta? <a href="login.php">Entrar</a></p>
    </div>
</section>

<?php require 'includes/footer.php'; ?>
