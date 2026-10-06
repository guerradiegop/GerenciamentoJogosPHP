<?php
require_once __DIR__ . '/../app/auth.php';
$current=require_auth();
if($_SERVER['REQUEST_METHOD']!=='POST'){http_response_code(405);exit('Método não permitido.');}
verify_csrf(); $id=(int)($_POST['id']??0);
$stmt=db()->prepare('DELETE FROM games WHERE id=? AND user_id=?');$stmt->execute([$id,$current['id']]);
flash('success',$stmt->rowCount()?'Jogo excluído com sucesso.':'Jogo não encontrado.');
redirect('/games.php');
