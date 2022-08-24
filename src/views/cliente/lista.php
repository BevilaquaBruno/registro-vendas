<div class="pure-form">
  <div class="title-list">
    <h2>Lista de Cliente</h2>
  </div>
  <table class="pure-table" id="clienteTable">
    <thead>
      <tr>
        <th>id</th>
        <th>Nome</th>
        <th>Email</th>
        <th>Cidade</th>
        <th>Qtd. Vendas</th>
        <th>Ações</th>
      </tr>
    </thead>
    <tbody></tbody>
  </table>
</div>
<a href="/cliente/cadastro" class="button-success pure-button">
  <span>Cadastrar</span>
</a>

<script type="text/javascript">
  var  lista_clientes = [];
  var clienteDatatable = null;

  function createClienteDatatable() {
    //https://www.cssscript.com/lightweight-vanilla-data-table-component/
    clienteDatatable = new DataTable(document.getElementById('clienteTable'), patternForDatatable);
  }

  function excluir(id, index){
    axios.delete("/api/cliente/"+id)
    .then(function (response) {
      console.log(response.data);
      if (true === response.data.success) {
        lista_clientes.splice(index, 1);
        document.getElementById('clienteTable').children[1].children[index].remove();
        notifier.success('Cliente deletado com sucesso');
      }
    })
    .catch(function (error) {
      console.error(error);
    });
  }

  function handleExcluir(id, index) {
    notifier.confirm(
      "Confirma que deseja excluir o cliente?",
      () => {excluir(id, index);},
      () => {}
    );
  }

  function limparTabela(){
    let clienteTable = document.getElementById('clienteTable');
    clienteTable.children[1].innerHTML = "";
  }

  function povoarTabela() {
    let html = '';
    for (let i = 0; i < lista_clientes.length; i++) {
      const cliente = lista_clientes[i];
      html += "<tr>"+
        "<td>"+cliente['id']+"</td> "+
        "<td>"+cliente['nome']+"</td> "+
        "<td>"+cliente['email']+"</td> "+
        "<td>"+(null === cliente['cidade'] ? '-' : cliente['cidade'])+"</td> "+
        "<td>"+cliente['qtd_vendas']+"</td> "+
        "<td> "+
          "<a href='/cliente/alteracao/"+cliente['id']+"' class='pure-button pure-button-primary'> "+
            "<span>Editar</span> "+
          "</a> "+
          "<button onclick=\"handleExcluir("+cliente['id']+", "+i+");\" class='button-error pure-button'> "+
           "Excluir"+
          "</button>"+
        "</td> "+
      "</tr>";
    }
    let clienteTbody = document.getElementById('clienteTable').children[1].innerHTML = html;
    createClienteDatatable();
  }

  function getClientes(){
    axios.get("/api/cliente")
    .then(function (response) {
      lista_clientes = response.data;
      povoarTabela();
    })
    .catch(function (error) {
      console.error(error);
    });
  }

  window.addEventListener("load", function () {
    getClientes();
  });
</script>