<div class="pure-form">
  <div class="title-list">
    <h2>Perfil do Usuário</h2>
  </div>
  <div class="pure-control-group" style="margin-top: 10px;">
    <div class="pure-u-1-3">
      <label><b>Nome:</b> <?= $dados['usuario']['nome'] ?></label>
    </div>
  </div>
  <div class="pure-control-group" style="margin-top: 10px;">
    <div class="pure-u-1-3">
      <label><b>E-mail:</b> <?= $dados['usuario']['email'] ?></label>
    </div>
  </div>
  <div class="pure-control-group" style="margin-top: 10px;">
    <label for="Nome"><b>api_token:</b></label>
      <div class="pure-u-1-2">
        <input readonly type="password" id="token" value="<?= $dados['usuario']['token'] ?>">
        <span class="pure-button-primary pure-button" id="btn-show-hide" onclick="showOrHide();">Show</span>
        <span class="pure-button-primary pure-button" id="btn-show-hide" onclick="copy();">Copy</span>
      </div>
    </div>
</div>
<script>
  function copy(){
    let token = document.getElementById('token').value;
    navigator.clipboard.writeText(token);

    notifier.success('Copiado com sucesso!');
  };
  function showOrHide(){
    let input = document.getElementById('token');
    let btn = document.getElementById('btn-show-hide');

    if(input.getAttribute('type') === 'text'){
      input.setAttribute('type', 'password');
      btn.innerText = 'Show';
    }else{
      input.setAttribute('type', 'text');
      btn.innerText = 'Hide';
    }
  }
</script>
