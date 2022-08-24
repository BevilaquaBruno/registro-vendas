<form class="pure-form pure-form-aligned" id="produtoForm" action="/api/produto/" method="post">
  <div class="pure-control-group">
    <div class="pure-u-1-3"></div>
    <div class="pure-u-1-3">
      <h2>Cadastro de Produto</h2>
    </div>
  </div>
  <input maxlength="10" type="hidden" name="id" value="<?=$dados['produto']['id']?>">
  <fieldset>

    <!-- Descrição -->
    <div class="pure-control-group">
      <label for="descricao">Descrição: </label>
      <div class="pure-u-1-3">
        <input class="full-width" maxlength="120" required type="text" name="descricao" id="descricao" value="<?=$dados['produto']['descricao']?>">
      </div>
      <span class="pure-form-message-inline">* Obrigatório.</span>
    </div>

    <!-- Quantidade -->
    <div class="pure-control-group">
      <label for="quantidade">Quantidade:</label>
      <div class="pure-u-1-5">
        <input class="quantidade-mask full-width" required name="quantidade" id="quantidade" type="text" value="<?=$dados['produto']['quantidade']?>" />
      </div>
      <span class="pure-form-message-inline">* Obrigatório.</span>
    </div>

    <!-- Unidade Medida -->
    <div class="pure-control-group">
      <label for="unidade_medida">Unid. Medida:</label>
      <div class="pure-u-1-3">
        <select class="full-width" name="unidade_medida" id="unidade_medida">
          <option value="nenhuma">Selecione...</option>
          <?php foreach ($dados['unidades_medida'] as $um) { ?>
            <option value="<?=$um?>" <?=$um == $dados['produto']['unidade_medida'] ? 'selected': ''?>><?=$um?></option>
          <?php } ?>
        </select>
      </div>
      <span class="pure-form-message-inline">* Obrigatório.</span>
    </div>

    <!-- Venda (R$) -->
    <div class="pure-control-group">
      <label for="valor_venda">Venda (R$):</label>
      <div class="pure-u-1-5">
        <input class="valor_venda-mask full-width" required name="valor_venda" id="valor_venda" type="text" value="<?=$dados['produto']['valor_venda']?>" />
      </div>
      <span class="pure-form-message-inline">* Obrigatório.</span>
    </div>

    <!-- Compra (R$) -->
    <div class="pure-control-group">
      <label for="valor_compra">Compra (R$):</label>
      <div class="pure-u-1-5">
        <input class="valor_compra-mask full-width" required name="valor_compra" id="valor_compra" type="text" value="<?=$dados['produto']['valor_compra']?>" />
      </div>
      <span class="pure-form-message-inline">* Obrigatório.</span>
    </div>

    <!--Controles -->
    <div class="pure-controls">
      <div onclick="handleSalvar();" class="button-success pure-button">
          Gravar
      </div>
      <a class="button-error pure-button" href="/produto">
        Voltar
      </a>
    </div>
  </fieldset>
</form>
<script type="text/javascript">
  function handleSalvar() {
    notifier.confirm(
      "Confirma que deseja <?=$dados['acao']?> o produto?",
      () => {salvar();},
      () => {}
    );
  }

  function salvar(){
    let produtoForm = document.getElementById("produtoForm");

    if("<?=$dados['acao']?>" === "cadastrar"){
      axios.post(produtoForm.getAttribute("action"),
        new FormData(produtoForm)
      )
      .then(function (response) {
        if(false === response.data.success) {
          notifier.alert(response.data.message);
        }else if(true === response.data.success){
          window.location.href = "/produto";
        }else{
          notifier.alert("Erro grave ao gravar o produto");
        }
      })
      .catch(function (error) {
        console.error(error);
      });
    }else{
      axios.put(produtoForm.getAttribute("action"),
        new FormData(produtoForm)
      )
      .then(function (response) {
        if(false === response.data.success) {
          notifier.alert(response.data.message);
        }else if(true === response.data.success){
          window.location.href = "/produto";
        }else{
          notifier.alert("Erro grave ao gravar o produto");
        }
      })
      .catch(function (error) {
        console.error(error);
      });
    }
  }

  window.addEventListener("load", function () {
    // obj clone
    let quantidadePatternForMask = Object.assign({}, moneyPatternForMask);
    quantidadePatternForMask.fractionDigits = 3;
    quantidadePatternForMask.allowNegative = true;

    SimpleMaskMoney.setMask("#quantidade", quantidadePatternForMask);
    SimpleMaskMoney.setMask("#valor_venda", moneyPatternForMask);
    SimpleMaskMoney.setMask("#valor_compra", moneyPatternForMask);
  });
</script>