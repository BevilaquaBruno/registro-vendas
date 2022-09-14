<label for="Nome">Nome: <?=$dados['usuario']['nome']?></label>
<br>
<label for="Nome">E-mail: <?=$dados['usuario']['email']?></label>
<br>
<label for="Nome">token: <input disabled type="password" id="token" value="<?=$dados['usuario']['token']?>"></label>
<span onclick="document.getElementById('token').setAttribute('type','text');">Show</span>
