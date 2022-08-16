<form class="pure-form pure-form-aligned" action="/api/usuario/<?=$dados['acao']?>" method="post" id="usuarioForm">
  <div class="pure-control-group">
    <div class="pure-u-1-3"></div>
    <div class="pure-u-1-3">
      <h2>Cadastro de Usuário</h2>
    </div>
  </div>
  <input maxlength="10" type="hidden" name="id" value="<?=$dados['usuario']['id']?>">

  <!-- Nome -->
  <div class="pure-control-group">
    <label for="nome">Nome: </label>
    <div class="pure-u-1-3">
      <input class="full-width" maxlength="100" required type="text" name="nome" id="nome" value="<?=$dados['usuario']['nome']?>">
    </div>
    <span class="pure-form-message-inline">* Obrigatório.</span>
  </div>

  <!-- Email -->
  <div class="pure-control-group">
    <label for="email">Email: </label>
    <div class="pure-u-1-3">
      <input class="full-width" maxlength="30" required type="text" name="email" id="email" value="<?=$dados['usuario']['email']?>">
    </div>
    <span class="pure-form-message-inline">* Obrigatório.</span>
  </div>

  <!-- Tipo -->
  <div class="pure-control-group">
    <label for="tipo">Tipo: </label>
      <div class="pure-u-1-4">
        <select class="full-width" name="tipo" id="tipo">
          <option value="nenhum">Selecione...</option>
          <option value="F" <?=($dados['usuario']['tipo'] === 'F' ? 'selected' : '')?> >Funcionário</option>
          <option value="A" <?=($dados['usuario']['tipo'] === 'A' ? 'selected' : '')?> >Administrador</option>
        </select>
      </div>
      <span class="pure-form-message-inline">* Obrigatório.</span>
  </div>

  <?php if ('cadastrar' === $dados['acao'] || $dados['usuario']['id'] == $_SESSION['id_u']){ ?>
    <!-- Senha -->
    <div class="pure-control-group">
      <label for="senha">Senha: </label>
      <div class="pure-u-1-4">
        <input class="full-width" maxlength="30" required type="password" name="senha" id="senha">
      </div>
      <span class="pure-form-message-inline">* Obrigatório.</span>
    </div>

    <!-- Repetir Senha -->
    <div class="pure-control-group">
      <label for="repetir_senha">Repita a senha: </label>
      <div class="pure-u-1-4">
        <input class="full-width" maxlength="30" required type="password" name="repetir_senha" id="repetir_senha">
      </div>
      <span class="pure-form-message-inline">* Obrigatório.</span>
    </div>
  <?php } ?>

  <!--Controles -->
  <div class="pure-controls">
    <div onclick="handleSalvar();" class="button-success pure-button">
        Gravar
    </div>
    <a class="button-error pure-button" href="/usuario">
      Voltar
    </a>
  </div>
</form>
<script type="text/javascript">
  function handleSalvar() {
    notifier.confirm(
      "Confirma que deseja <?=$dados['acao']?> o usuário?",
      () => {salvar();},
      () => {}
    );
  }

  function salvar(){
    let usuarioForm = document.getElementById("usuarioForm");

    if("<?=$dados['acao']?>" === "cadastrar"){
      axios.post(usuarioForm.getAttribute("action"),
        new FormData(usuarioForm)
      )
      .then(function (response) {
        if(false === response.data.success) {
          notifier.alert( ("" === response.data.message ? "Erro grave ao gravar usuário" : response.data.message) );
        }else if(true === response.data.success){
          window.location.href = "/usuario";
        }else{
          notifier.alert("Erro grave ao gravar o usuário");
        }
      })
      .catch(function (error) {
        console.error(error);
      });
    }else{
      axios.put(usuarioForm.getAttribute("action"),
        new FormData(usuarioForm)
      )
      .then(function (response) {
        if(false === response.data.success) {
          notifier.alert( ("" === response.data.message ? "Erro grave ao gravar usuário" : response.data.message) );
        }else if(true === response.data.success){
          window.location.href = "/usuario";
        }else{
          notifier.alert("Erro grave ao gravar o usuário");
        }
      })
      .catch(function (error) {
        console.error(error);
      });
    }
  }
</script>
