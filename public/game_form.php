<?php
require_once __DIR__ . '/../app/layout.php';
$current=require_auth(); $pdo=db(); $id=(int)($_GET['id'] ?? $_POST['id'] ?? 0);
$game=['name'=>'','platform'=>'','media_type'=>'online','store'=>'','notes'=>''];
if($id){$s=$pdo->prepare('SELECT * FROM games WHERE id=? AND user_id=?');$s->execute([$id,$current['id']]);$found=$s->fetch();if(!$found){http_response_code(404);exit('Jogo não encontrado.');}$game=$found;}
$errors=[];
if($_SERVER['REQUEST_METHOD']==='POST'){
    verify_csrf();
    foreach(['name','platform','media_type','store','notes'] as $key)$game[$key]=trim((string)($_POST[$key]??''));
    if($game['name']==='')$errors[]='Informe o nome do jogo.';
    if($game['platform']==='')$errors[]='Informe a plataforma.';
    if(!in_array($game['media_type'],['online','physical'],true))$errors[]='Selecione um formato válido.';
    if($game['media_type']==='online' && $game['store']==='')$errors[]='Informe a loja vinculada ao jogo digital.';
    if($game['media_type']==='physical')$game['store']=null;
    if(!$errors){
        if($id){$s=$pdo->prepare('UPDATE games SET name=?,platform=?,media_type=?,store=?,notes=?,updated_at=CURRENT_TIMESTAMP WHERE id=? AND user_id=?');$s->execute([$game['name'],$game['platform'],$game['media_type'],$game['store'],$game['notes'],$id,$current['id']]);flash('success','Jogo atualizado com sucesso.');}
        else{$s=$pdo->prepare('INSERT INTO games (user_id,name,platform,media_type,store,notes) VALUES (?,?,?,?,?,?)');$s->execute([$current['id'],$game['name'],$game['platform'],$game['media_type'],$game['store'],$game['notes']]);flash('success','Jogo cadastrado com sucesso.');}
        redirect('/games.php');
    }
}
render_header($id?'Editar jogo':'Cadastrar jogo',true);
?>
<section class="page-head"><div><span class="eyebrow">CRUD</span><h1><?= $id?'Editar jogo':'Cadastrar jogo' ?></h1><p>Registre os dados da sua cópia do jogo.</p></div></section>
<section class="panel form-panel">
<?php if($errors):?><div class="alert alert-error"><ul><?php foreach($errors as $error):?><li><?= e($error) ?></li><?php endforeach;?></ul></div><?php endif;?>
<form method="post" class="form-grid" id="gameForm"><input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>"><?php if($id):?><input type="hidden" name="id" value="<?= $id ?>"><?php endif;?>
<label>Nome do jogo<input required name="name" value="<?= e($game['name']) ?>" placeholder="Ex.: Diablo IV"></label>
<label>Plataforma<input required name="platform" value="<?= e($game['platform']) ?>" placeholder="PC, PlayStation 5, Xbox..."></label>
<label>Formato<select required name="media_type" id="mediaType"><option value="online" <?= $game['media_type']==='online'?'selected':'' ?>>Digital / Online</option><option value="physical" <?= $game['media_type']==='physical'?'selected':'' ?>>Disco físico</option></select></label>
<label id="storeWrap">Loja<select name="store" id="store"><option value="">Selecione</option><?php foreach(['Steam','EPIC','Battle.net','GOG','PlayStation Store','Microsoft Store','Nintendo eShop','Outra'] as $store):?><option <?= $game['store']===$store?'selected':'' ?>><?= e($store) ?></option><?php endforeach;?></select></label>
<label class="span-2">Observações<textarea name="notes" rows="4" placeholder="Edição, DLCs, observações gerais..."><?= e($game['notes']) ?></textarea></label>
<div class="span-2 form-actions"><button class="btn" type="submit">Salvar</button><a class="btn btn-secondary" href="/games.php">Cancelar</a></div>
</form></section>
<script>const media=document.getElementById('mediaType'),wrap=document.getElementById('storeWrap'),store=document.getElementById('store');function toggle(){const online=media.value==='online';wrap.style.display=online?'grid':'none';store.required=online;if(!online)store.value='';}media.addEventListener('change',toggle);toggle();</script>
<?php render_footer(); ?>
