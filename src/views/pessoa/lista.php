<div class="pure-form">
  <div class="title-list">
    <h2>Lista de Pessoas</h2>
  </div>
  <table class="pure-table" id="pessoaTable">
    <thead>
      <tr>
        <th>id</th>
        <th>Nome</th>
        <th>Email</th>
        <th>Telefone</th>
        <th>Nascimento</th>
        <th>Ações</th>
      </tr>
    </thead>
    <tbody></tbody>
  </table>
</div>
<a href="/pessoa/cadastro" class="button-success pure-button">
  <span>Cadastrar</span>
</a>
<script type="text/javascript">
  var  lista_pessoas = [];
  var pessoaDatatable = null;

  function createPessoaDatatable() {
    //https://www.cssscript.com/lightweight-vanilla-data-table-component/
    pessoaDatatable = new DataTable(document.getElementById('pessoaTable'), patternForDatatable);
  }

  function excluir(id, index){
    axios.delete("/api/pessoa/"+id)
    .then(function (response) {
      console.log(response.data);
      if (true === response.data.success) {
        lista_pessoas.splice(index, 1);
        document.getElementById('pessoaTable').children[1].children[index].remove();
        notifier.success('Pessoa deletada com sucesso');
      }else{
        notifier.alert(response.data.message);
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
        "<td>"+(pessoa['telefone'] == null ? "-" : pessoa['telefone'])+"</td> "+
        "<td>"+(pessoa['data_nascimento'] == null ? "-" : pessoa['data_nascimento'])+"</td> "+
        "<td> "+
          "<a href='/pessoa/alteracao/"+pessoa['id']+"' class='pure-button pure-button-primary'> "+
            "<span>Editar</span> "+
          "</a> "+
          "<button onclick=\"handleExcluir("+pessoa['id']+", "+i+");\" class='button-error pure-button'> "+
           "Excluir"+
          "</button>"+
        "</td> "+
      "</tr>";
    }
    let pessoaTBody = document.getElementById('pessoaTable').children[1].innerHTML = html;
    createPessoaDatatable();
  }

  function getPessoas(){
    axios.get("/api/pessoa")
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
