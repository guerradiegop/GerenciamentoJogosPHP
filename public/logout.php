<?php
require_once __DIR__ . '/../app/auth.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { http_response_code(405); exit('Método não permitido.'); }
verify_csrf();
logout_user();
header('Location: /');
exit;
