<div class="pure-form">
  <div class="title-list">
    <h2>Lista de Pessoas</h2>
  </div>
  <table class="pure-table" id="pessoaTable">
    <thead>
      <tr>
        <th>id</th>
        <th>Nome</th>
        <th>email</th>
        <th>Telefone</th>
        <th>Nascimento</th>
        <th>Ações</th>
      </tr>
    </thead>
    <tbody></tbody>
  </table>
</div>
<button class="button-success pure-button">
  <a href="index.php?m=pessoa&a=cadastro">Cadastrar</a>
</button>
<script type="text/javascript">
  var  lista_pessoas = [];
  var pessoaDatatable = null;

  function createPessoaDatatable() {
    //https://www.cssscript.com/lightweight-vanilla-data-table-component/
    pessoaDatatable = new DataTable(document.getElementById('pessoaTable'), patternForDatatable);
  }

  function excluir(id, index){
    axios.get("index.php?m=pessoa&a=deletar&id="+id)
    .then(function (response) {
      console.log(response.data);
      if (true === response.data.success) {
        lista_pessoas.splice(index, 1);
        document.getElementById('pessoaTable').children[1].children[index].remove();
        notifier.success('Pessoa deletada com sucesso');
      }
    })
    .catch(function (error) {
      console.error(error);
    });
  }

  function handleExcluir(id, index) {
    notifier.confirm(
      "Confirma que deseja excluir a pessoa?",
      () => {excluir(id, index);},
      () => {}
    );
  }

  function limparTabela(){
    let pessoaTable = document.getElementById('pessoaTable');
    pessoaTable.children[1].innerHTML = "";
  }

  function povoarTabela() {
    let html = '';
    for (let i = 0; i < lista_pessoas.length; i++) {
      const pessoa = lista_pessoas[i];
      html += "<tr>"+
        "<td>"+pessoa['id']+"</td> "+
        "<td>"+pessoa['nome']+"</td> "+
        "<td>"+pessoa['email']+"</td> "+
        "<td>"+pessoa['telefone']+"</td> "+
        "<td>"+pessoa['data_nascimento']+"</td> "+
        "<td> "+
          "<button class='pure-button pure-button-primary'> "+
            "<a href='index.php?m=pessoa&a=alteracao&id="+pessoa['id']+"'>Editar</a> "+
          "</button> "+
          "<button class='button-error pure-button'> "+
           "<span onclick=\"handleExcluir("+pessoa['id']+", "+i+");\">Excluir</span> "+
          "</button>"+
        "</td> "+
      "</tr>";
    }
    let pessoaTBody = document.getElementById('pessoaTable').children[1].innerHTML = html;
    createPessoaDatatable();
  }

  function getPessoas(){
    axios.get("index.php?m=pessoa&a=listajson")
    .then(function (response) {
      lista_pessoas = response.data;
      povoarTabela();
    })
    .catch(function (error) {
      console.error(error);
    });
  }

  window.addEventListener("load", function () {
    getPessoas();
  });
</script>
