<div class="formDiv">
  <form id="funcionarioForm" action="index.php?m=funcionario&a=<?=$dados['acao']?>" method="post">
    <input maxlength="10" type="hidden" name="id" value="<?=$dados['funcionario']['id']?>">
    <label for="id_pessoa">Pessoa:</label>
    <select name="id_pessoa" id="id_pessoa">
      <option value="0">Selecione...</option>
      <?php foreach ($dados['pessoas'] as $p) { ?>
        <option value="<?=$p['id']?>" <?=$p['id'] == $dados['funcionario']['id_pessoa'] ? 'selected': ''?>><?=$p['id'].' - '.$p['nome']?></option>
      <?php } ?>
    </select>
    <label for="data_admissao">Data Admissão:</label>
    <input type="date" name="data_admissao" id="data_admissao" value="<?=$dados['funcionario']['data_admissao_original']?>">
    <label for="salario">Salário:</label>
    <input name="salario" id="salario" type="number" min="0.00" max="9999999999.99" step="0.01" value="<?=$dados['funcionario']['salario']?>" />
    <div onclick="handleSalvar();" type="button" class="submitFormButton">Gravar</div>
    <a href="index.php?m=funcionario&a=lista">
      <div class="backFormButton">
        Voltar
      </div>
    </a>
  </form>
</div>
<script type="text/javascript">
  function handleSalvar() {
    notifier.confirm(
      "Confirma que deseja cadastrar o funcionário?",
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
      }else{
        window.location.href = "index.php?m=funcionario&a=lista";
      }
    })
    .catch(function (error) {
      console.error(error);
    });
  }
</script>