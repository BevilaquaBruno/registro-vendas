<div class="formDiv">
  <form action="index.php?m=pessoa&a=<?=$dados['acao']?>" method="post">
    <input maxlength="10" type="hidden" name="id" value="<?=$dados['pessoa']['id']?>">
    <label for="nome">Nome: </label>
    <input maxlength="100" type="text" name="nome" id="nome" value="<?=$dados['pessoa']['nome']?>">
    <label for="email">Email: </label>
    <input maxlength="30" type="text" name="email" id="email" value="<?=$dados['pessoa']['email']?>">
    <label for="telefone">Telefone: </label>
    <input maxlength="11" type="text" name="telefone" id="telefone" value="<?=$dados['pessoa']['telefone']?>">
    <label for="data_nascimento">Data Nascimento: </label>
    <input type="date" name="data_nascimento" id="data_nascimento" value="<?=$dados['pessoa']['data_nascimento_original']?>">
    <input type="submit" value="Gravar" class="myButton myButtonGreen">
    <a href="index.php?m=pessoa&a=lista">
      <div class="backFormButton">
        Voltar
      </div>
    </a>
  </form>
</div>