<form class="pure-form pure-form-aligned" action="/api/pessoa/<?=$dados['acao']?>" method="post" id="pessoaForm">
  <div class="pure-control-group">
    <div class="pure-u-1-3"></div>
    <div class="pure-u-1-3">
      <h2>Cadastro de Pessoa</h2>
    </div>
  </div>
  <input maxlength="10" type="hidden" name="id" value="<?=$dados['pessoa']['id']?>">

  <!-- Nome -->
  <div class="pure-control-group">
    <label for="nome">Nome: </label>
    <div class="pure-u-1-3">
      <input class="full-width" maxlength="100" required type="text" name="nome" id="nome" value="<?=$dados['pessoa']['nome']?>">
    </div>
    <span class="pure-form-message-inline">* Obrigatório.</span>
  </div>

  <!-- Email -->
  <div class="pure-control-group">
    <label for="email">Email: </label>
    <div class="pure-u-1-3">
      <input class="full-width" maxlength="30" required type="text" name="email" id="email" value="<?=$dados['pessoa']['email']?>">
    </div>
    <span class="pure-form-message-inline">* Obrigatório.</span>
  </div>

  <!-- Telefone -->
  <div class="pure-control-group">
    <label for="telefone">Telefone: </label>
    <input type="text" class="telefone-mask" data-mask="(##) # ####-####" name="telefone" id="telefone" value="<?=$dados['pessoa']['telefone']?>">
  </div>

  <!-- Data Nascimento -->
  <div class="pure-control-group">
    <label for="data_nascimento">Data Nascimento: </label>
    <input type="date" name="data_nascimento" id="data_nascimento" value="<?=$dados['pessoa']['data_nascimento_original']?>">
  </div>

  <!--Controles -->
  <div class="pure-controls">
    <div onclick="handleSalvar();" class="button-success pure-button">
        Gravar
    </div>
    <a class="button-error pure-button" href="/pessoa">
      Voltar
    </a>
  </div>
</form>
<script type="text/javascript">
  function handleSalvar() {
    notifier.confirm(
      "Confirma que deseja <?=$dados['acao']?> a pessoa?",
      () => {salvar();},
      () => {}
    );
  }

  function salvar(){
    let pessoaForm = document.getElementById("pessoaForm");

    axios.post(pessoaForm.getAttribute("action"),
      new FormData(pessoaForm)
    )
    .then(function (response) {
      if(false === response.data.success) {
        notifier.alert( ("" === response.data.message ? "Erro grave ao gravar pessoa" : response.data.message) );
      }else if(true === response.data.success){
        window.location.href = "/pessoa";
      }else{
        notifier.alert("Erro grave ao gravar a pessoa");
      }
    })
    .catch(function (error) {
      console.error(error);
    });
  }

  window.addEventListener("load", function () {
    PureMask.format(".telefone-mask", true);
  });
</script>
