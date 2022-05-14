<form class="pure-form pure-form-aligned" id="loginForm" action="index.php?m=login&a=<?=$dados['acao']?>" method="post">
  <div class="pure-control-group">
    <div class="pure-u-1-4"></div>
    <div class="pure-u-1-4">
      <h2>Login</h2>
    </div>
  </div>
  <fieldset>
    <!-- Email -->
    <div class="pure-control-group">
      <label for="email">Email: </label>
      <div class="pure-u-1-4">
        <input class="full-width" maxlength="30" required type="text" name="email" id="email">
      </div>
      <span class="pure-form-message-inline">* Obrigatório.</span>
    </div>

    <!-- Senha -->
    <div class="pure-control-group">
      <label for="senha">Senha: </label>
      <div class="pure-u-1-4">
        <input class="full-width" maxlength="30" required type="password" name="senha" id="senha">
      </div>
      <span class="pure-form-message-inline">* Obrigatório.</span>
    </div>

    <!--Controles -->
    <div class="pure-controls">
      <div onclick="handleLogar();" class="button-success pure-button">
        Logar
      </div>
      <a class="button-error pure-button" href="index.php">
        Voltar
      </a>
    </div>
  </fieldset>
</form>
<script>
  function handleLogar(){
    let loginForm = document.getElementById("loginForm");

    axios.post(loginForm.getAttribute("action"),
      new FormData(loginForm)
    )
    .then(function (response) {
      if(false === response.data.success) {
        notifier.alert(response.data.message);
      }else if(true === response.data.success){
        window.location.href = "index.php";
      }else{
        notifier.alert("Erro ao logar");
      }
    })
    .catch(function (error) {
      console.error(error);
    });
  }
</script>