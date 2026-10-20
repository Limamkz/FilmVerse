<?php
// Capa local de contingência: garante que o catálogo nunca fique com espaços vazios
// quando um serviço externo de imagens estiver indisponível.
$title = trim((string) ($_GET['title'] ?? 'FilmVerse'));
$category = trim((string) ($_GET['category'] ?? 'Entretenimento'));
$title = function_exists('mb_substr') ? mb_substr($title, 0, 90, 'UTF-8') : substr($title, 0, 180);
$category = function_exists('mb_substr') ? mb_substr($category, 0, 35, 'UTF-8') : substr($category, 0, 70);
function svg_text(string $value): string {
    return htmlspecialchars($value, ENT_QUOTES | ENT_XML1, 'UTF-8');
}
$lines = preg_split('/\s+/', $title);
$wrapped = [];
$line = '';
foreach ($lines as $word) {
    if ((function_exists('mb_strlen') ? mb_strlen(trim($line . ' ' . $word), 'UTF-8') : strlen(trim($line . ' ' . $word))) > 17 && $line !== '') {
        $wrapped[] = trim($line);
        $line = $word;
    } else {
        $line = trim($line . ' ' . $word);
    }
}
if ($line !== '') $wrapped[] = $line;
$wrapped = array_slice($wrapped, 0, 4);
$startY = 490 - (count($wrapped) - 1) * 25;
header('Content-Type: image/svg+xml; charset=utf-8');
header('Cache-Control: public, max-age=86400');
echo '<svg xmlns="http://www.w3.org/2000/svg" width="500" height="750" viewBox="0 0 500 750">';
echo '<defs><linearGradient id="bg" x1="0" y1="0" x2="1" y2="1"><stop stop-color="#09070f"/><stop offset=".55" stop-color="#24103d"/><stop offset="1" stop-color="#080610"/></linearGradient><radialGradient id="glow"><stop stop-color="#a855f7" stop-opacity=".85"/><stop offset="1" stop-color="#6d28d9" stop-opacity="0"/></radialGradient><linearGradient id="line" x1="0" x2="1"><stop stop-color="#8b5cf6" stop-opacity="0"/><stop offset=".5" stop-color="#c084fc"/><stop offset="1" stop-color="#8b5cf6" stop-opacity="0"/></linearGradient></defs>';
echo '<rect width="500" height="750" fill="url(#bg)"/><circle cx="360" cy="210" r="250" fill="url(#glow)"/><circle cx="110" cy="320" r="180" fill="url(#glow)" opacity=".45"/>';
for ($i = 0; $i < 9; $i++) {
    $y = 100 + $i * 53;
    echo '<path d="M-40 ' . $y . ' Q250 ' . ($y - 80) . ' 540 ' . ($y + 15) . '" stroke="url(#line)" stroke-width="2" fill="none" opacity=".5"/>';
}
echo '<rect x="28" y="28" width="444" height="694" rx="18" fill="none" stroke="#c084fc" stroke-opacity=".48"/>';
echo '<g transform="translate(250 230)"><circle r="82" fill="#120a20" stroke="#a855f7" stroke-width="3"/><circle r="64" fill="none" stroke="#7c3aed" stroke-width="1.5"/><path d="M-62 0 C-30 -52 30 52 62 0 C30 -52 -30 52 -62 0" fill="none" stroke="#d8b4fe" stroke-width="5" stroke-linecap="round"/><circle r="8" fill="#fff"/></g>';
echo '<text x="250" y="390" fill="#d8b4fe" font-family="Arial,sans-serif" font-size="15" letter-spacing="4" text-anchor="middle">' . svg_text(function_exists('mb_strtoupper') ? mb_strtoupper($category, 'UTF-8') : strtoupper($category)) . '</text>';
foreach ($wrapped as $index => $text) {
    echo '<text x="250" y="' . ($startY + $index * 52) . '" fill="#ffffff" font-family="Arial,sans-serif" font-size="' . ((function_exists('mb_strlen') ? mb_strlen($text, 'UTF-8') : strlen($text)) > 15 ? 26 : 32) . '" font-weight="700" text-anchor="middle">' . svg_text($text) . '</text>';
}
echo '<path d="M155 585 H345" stroke="#a855f7" stroke-width="3" stroke-linecap="round"/><text x="250" y="635" fill="#b9a6d5" font-family="Arial,sans-serif" font-size="14" letter-spacing="5" text-anchor="middle">FILMVERSE</text></svg>';
