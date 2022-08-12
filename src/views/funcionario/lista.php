<div class="pure-form">
  <div class="title-list">
    <h2>Lista de Funcionários</h2>
  </div>
  <table class="pure-table" id="funcionarioTable">
    <thead>
      <tr>
        <th>id</th>
        <th>Nome</th>
        <th>Email</th>
        <th>Admissão</th>
        <th>Salário</th>
        <th>Ações</th>
      </tr>
    </thead>
    <tbody></tbody>
  </table>
</div>
<a href="/funcionario/cadastro" class="button-success pure-button">
  <span>Cadastrar</span>
</a>

<script type="text/javascript">
  var  lista_funcionarios = [];
  var funcionarioDatatable = null;

  function createFuncionarioDatatable() {
    //https://www.cssscript.com/lightweight-vanilla-data-table-component/
    funcionarioDatatable = new DataTable(document.getElementById('funcionarioTable'), patternForDatatable);
  }

  function excluir(id, index){
    axios.delete("/api/funcionario/deletar/"+id)
    .then(function (response) {
      console.log(response.data);
      if (true === response.data.success) {
        lista_funcionarios.splice(index, 1);
        document.getElementById('funcionarioTable').children[1].children[index].remove();
        notifier.success('Funcionário deletado com sucesso');
      }
    })
    .catch(function (error) {
      console.error(error);
    });
  }

  function handleExcluir(id, index) {
    notifier.confirm(
      "Confirma que deseja excluir o funcionário?",
      () => {excluir(id, index);},
      () => {}
    );
  }

  function limparTabela(){
    let funcionarioTable = document.getElementById('funcionarioTable');
    funcionarioTable.children[1].innerHTML = "";
  }

  function povoarTabela() {
    let html = '';
    for (let i = 0; i < lista_funcionarios.length; i++) {
      const funcionario = lista_funcionarios[i];
      html += "<tr>"+
        "<td>"+funcionario['id']+"</td> "+
        "<td>"+funcionario['nome']+"</td> "+
        "<td>"+funcionario['email']+"</td> "+
        "<td>"+funcionario['data_admissao']+"</td> "+
        "<td>"+(returnBrazilianCurrency(parseFloat(funcionario['salario'])))+"</td> "+
        "<td> "+
          "<a href='/funcionario/alteracao/"+funcionario['id']+"' class='pure-button pure-button-primary'> "+
            "<span>Editar</span> "+
          "</a> "+
          "<button onclick=\"handleExcluir("+funcionario['id']+", "+i+");\" class='button-error pure-button'> "+
           "Excluir"+
          "</button>"+
        "</td> "+
      "</tr>";
    }
    let funcionarioTBody = document.getElementById('funcionarioTable').children[1].innerHTML = html;
    createFuncionarioDatatable();
  }

  function getFuncionarios(){
    axios.get("/api/funcionario")
    .then(function (response) {
      lista_funcionarios = response.data;
      povoarTabela();
    })
    .catch(function (error) {
      console.error(error);
    });
  }

  window.addEventListener("load", function () {
    getFuncionarios();
  });
</script>