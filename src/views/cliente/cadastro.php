<form class="pure-form pure-form-aligned" id="clienteForm" action="index.php?m=cliente&a=<?=$dados['acao']?>" method="post">
  <div class="pure-control-group">
    <div class="pure-u-1-3"></div>
    <div class="pure-u-1-3">
      <h2>Cadastro de Cliente</h2>
    </div>
  </div>
  <input maxlength="10" type="hidden" name="id" value="<?=$dados['cliente']['id']?>">
  <fieldset>
    <!-- Pessoa -->
    <div class="pure-control-group">
      <label for="id_pessoa">Pessoa:</label>
      <div class="pure-u-1-3">
        <select class="full-width" name="id_pessoa" id="id_pessoa">
          <option value="0">Selecione...</option>
          <?php foreach ($dados['pessoas'] as $p) { ?>
            <option value="<?=$p['id']?>" <?=$p['id'] == $dados['cliente']['id_pessoa'] ? 'selected': ''?>><?=$p['id'].' - ' . $p['nome'] . ' - ' . $p['email']?></option>
          <?php } ?>
        </select>
      </div>
      <span class="pure-form-message-inline">* Obrigatório.</span>
    </div>

    <!-- Endereço -->
    <div class="pure-control-group">
      <label for="endereco">Endereço: </label>
      <div class="pure-u-1-3">
        <input class="full-width" maxlength="30" required type="text" name="endereco" id="endereco" value="<?=$dados['cliente']['endereco']?>">
      </div>
    </div>

    <!-- Unidade Medida -->
    <div class="pure-control-group">
      <label for="uf">UF:</label>
      <div class="pure-u-1-3">
        <select class="full-width" name="uf" id="uf">
          <option value="nenhuma">Selecione...</option>
          <?php foreach ($dados['ufs'] as $um) { ?>
            <option value="<?=$um?>" <?=$um == $dados['cliente']['uf'] ? 'selected': ''?>><?=$um?></option>
          <?php } ?>
        </select>
      </div>
    </div>

    <!-- Cidade -->
    <div class="pure-control-group">
      <label for="cidade">Cidade: </label>
      <div class="pure-u-1-3">
        <input class="full-width" maxlength="30" required type="text" name="cidade" id="cidade" value="<?=$dados['cliente']['cidade']?>">
      </div>
    </div>

    <!-- País -->
    <div class="pure-control-group">
      <label for="pais">País: </label>
      <div class="pure-u-1-3">
        <input class="full-width" maxlength="30" required type="text" name="pais" id="pais" value="<?=$dados['cliente']['pais']?>">
      </div>
    </div>

    <!-- Bairro -->
    <div class="pure-control-group">
      <label for="bairro">Bairro: </label>
      <div class="pure-u-1-3">
        <input class="full-width" maxlength="30" required type="text" name="bairro" id="bairro" value="<?=$dados['cliente']['bairro']?>">
      </div>
    </div>

    <!--Controles -->
    <div class="pure-controls">
      <div onclick="handleSalvar();" class="button-success pure-button">
          Gravar
      </div>
      <div class="button-error pure-button">
        <a href="index.php?m=cliente&a=lista">
          Voltar
        </a>
      </div>
    </div>
  </fieldset>
</form>
<script type="text/javascript">
  function handleSalvar() {
    notifier.confirm(
      "Confirma que deseja <?=$dados['acao']?> o cliente?",
      () => {salvar();},
      () => {}
    );
  }

  function salvar(){
    let clienteForm = document.getElementById("clienteForm");

    axios.post(clienteForm.getAttribute("action"),
      new FormData(clienteForm)
    )
    .then(function (response) {
      if(false === response.data.success) {
        notifier.alert(response.data.message);
      }else if(true === response.data.success){
        window.location.href = "index.php?m=cliente&a=lista";
      }else{
        notifier.alert("Erro grave ao gravar o cliente");
      }
    })
    .catch(function (error) {
      console.error(error);
    });
  }
</script>