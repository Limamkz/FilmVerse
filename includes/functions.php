<?php
if (session_status() !== PHP_SESSION_ACTIVE) session_start();
require_once __DIR__.'/db.php';
function e($v): string { return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); }
function logged_in(): bool { return isset($_SESSION['user_id']); }
function require_login(): void { if (!logged_in()) { header('Location: login.php?next=favoritos.php'); exit; } }
function current_user(): ?array {
    if (!logged_in()) return null;
    static $u = false;
    if ($u === false) { $s=db()->prepare('SELECT id,nome,email FROM usuarios WHERE id=?'); $s->execute([$_SESSION['user_id']]); $u=$s->fetch() ?: null; }
    return $u;
}
function flash($message, $type='success'): void { $_SESSION['flash']=['message'=>$message,'type'=>$type]; }
function take_flash(): ?array { $f=$_SESSION['flash']??null; unset($_SESSION['flash']); return $f; }
function csrf_field(): string { if(empty($_SESSION['csrf'])) $_SESSION['csrf']=bin2hex(random_bytes(32)); return '<input type="hidden" name="csrf" value="'.e($_SESSION['csrf']).'">'; }
function csrf_check(): void { if(!hash_equals($_SESSION['csrf']??'', $_POST['csrf']??'')) { http_response_code(403); exit('Solicitação inválida. Atualize a página e tente novamente.'); } }
function send_owner_email($nome,$email): void {
    if (!RESEND_API_KEY || !filter_var(OWNER_EMAIL,FILTER_VALIDATE_EMAIL)) return;
    $payload=json_encode(['from'=>'FilmVerse <onboarding@resend.dev>','to'=>[OWNER_EMAIL],'subject'=>'Novo cadastro no FilmVerse','html'=>'<h2>Novo usuário cadastrado</h2><p><b>Nome:</b> '.htmlspecialchars($nome).'</p><p><b>E-mail:</b> '.htmlspecialchars($email).'</p>']);
    $ch=curl_init('https://api.resend.com/emails'); curl_setopt_array($ch,[CURLOPT_POST=>true,CURLOPT_RETURNTRANSFER=>true,CURLOPT_HTTPHEADER=>['Authorization: Bearer '.RESEND_API_KEY,'Content-Type: application/json'],CURLOPT_POSTFIELDS=>$payload,CURLOPT_TIMEOUT=>5]); curl_exec($ch); curl_close($ch);
}
