<form class="pure-form pure-form-aligned" id="funcionarioForm" action="index.php?m=funcionario&a=<?=$dados['acao']?>" method="post">
  <div class="pure-control-group">
    <div class="pure-u-1-3"></div>
    <div class="pure-u-1-3">
      <h2>Cadastro de Funcionário</h2>
    </div>
  </div>
  <input maxlength="10" type="hidden" name="id" value="<?=$dados['funcionario']['id']?>">
  <fieldset>
    <!-- Pessoa -->
    <div class="pure-control-group">
      <label for="id_pessoa">Pessoa:</label>
      <div class="pure-u-1-3">
        <select class="full-width" name="id_pessoa" id="id_pessoa">
          <option value="0">Selecione...</option>
          <?php foreach ($dados['pessoas'] as $p) { ?>
            <option value="<?=$p['id']?>" <?=$p['id'] == $dados['funcionario']['id_pessoa'] ? 'selected': ''?>><?=$p['id'].' - ' . $p['nome'] . ' - ' . $p['email']?></option>
          <?php } ?>
        </select>
      </div>
      <span class="pure-form-message-inline">* Obrigatório.</span>
    </div>

    <!-- Data Admissão -->
    <div class="pure-control-group">
      <label for="data_admissao">Data Admissão:</label>
      <input required type="date" name="data_admissao" id="data_admissao" value="<?=$dados['funcionario']['data_admissao_original']?>">
      <span class="pure-form-message-inline">* Obrigatório.</span>
    </div>

    <!-- Salário -->
    <div class="pure-control-group">
      <label for="salario">Salário:</label>
      <div class="pure-u-1-5">
        <input class="salario-mask full-width" required name="salario" id="salario" type="text" value="<?=$dados['funcionario']['salario']?>" />
      </div>
      <span class="pure-form-message-inline">* Obrigatório.</span>
    </div>

    <!--Controles -->
    <div class="pure-controls">
      <div onclick="handleSalvar();" class="button-success pure-button">
          Gravar
      </div>
      <div class="button-error pure-button">
        <a href="index.php?m=funcionario&a=lista">
          Voltar
        </a>
      </div>
    </div>
  </fieldset>
</form>
<script type="text/javascript">
  function handleSalvar() {
    notifier.confirm(
      "Confirma que deseja <?=$dados['acao']?> o funcionário?",
      () => {salvar();},
      () => {}
    );
  }

  function salvar(){
    let funcionarioForm = document.getElementById("funcionarioForm");

    axios.post(funcionarioForm.getAttribute("action"),
      new FormData(funcionarioForm)
    )
    .then(function (response) {
      if(false === response.data.success) {
        notifier.alert(response.data.message);
      }else if(true === response.data.success){
        window.location.href = "index.php?m=funcionario&a=lista";
      }else{
        notifier.alert("Erro grave ao gravar o funcionário");
      }
    })
    .catch(function (error) {
      console.error(error);
    });
  }

  window.addEventListener("load", function () {
    SimpleMaskMoney.setMask("#salario", {
      allowNegative: false ,
      cursor: 'move',
      decimalSeparator: ',',
      fixed:  true ,
      fractionDigits:  2 ,
      negativeSignAfter:  false ,
      prefix: '',
      suffix: '',
      thousandsSeparator: '.'
    });
  });
</script>