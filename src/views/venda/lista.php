<div class="pure-form">
  <div class="title-list">
    <h2>Lista de Vendas</h2>
  </div>
  <table class="pure-table" id="vendaTable">
    <thead>
      <tr>
        <th>id</th>
        <th>Cliente</th>
        <th>Vendedor</th>
        <th>Qtd Itens</th>
        <th>Total (R$)</th>
        <th>Ações</th>
      </tr>
    </thead>
    <tbody></tbody>
  </table>
</div>
<a href="/venda/cadastro" class="button-success pure-button">
  <span>Cadastrar</span>
</a>

<script type="text/javascript">
  var  lista_vendas = [];
  var vendaDatatable = null;

  function createVendaDatatable() {
    //https://www.cssscript.com/lightweight-vanilla-data-table-component/
    vendaDatatable = new DataTable(document.getElementById('vendaTable'), patternForDatatable);
  }

  function excluir(id, index){
    axios.delete("/api/venda/"+id)
    .then(function (response) {
      console.log(response.data);
      if (true === response.data.success) {
        lista_vendas.splice(index, 1);
        document.getElementById('vendaTable').children[1].children[index].remove();
        notifier.success('Venda deletado com sucesso');
      }
    })
    .catch(function (error) {
      console.error(error);
    });
  }

  function handleExcluir(id, index) {
    notifier.confirm(
      "Confirma que deseja excluir a venda?",
      () => {excluir(id, index);},
      () => {}
    );
  }

  function limparTabela(){
    let vendaTable = document.getElementById('vendaTable');
    vendaTable.children[1].innerHTML = "";
  }

  function povoarTabela() {
    let html = '';
    for (let i = 0; i < lista_vendas.length; i++) {
      const venda = lista_vendas[i];
      html += "<tr>"+
        "<td>"+venda['id']+"</td> "+
        "<td>"+venda['cliente_nome']+"</td> "+
        "<td>"+venda['funcionario_nome']+"</td> "+
        "<td>"+returnBrazilianNumber(parseFloat(venda['qtd_produtos']))+"</td> "+
        "<td>"+returnBrazilianCurrency(parseFloat(venda['valor_total']))+"</td> "+
        "<td> "+
          "<a href='/venda/alteracao/"+venda['id']+"' class='pure-button pure-button-primary'> "+
            "<span>Editar</span> "+
          "</a> "+
          "<button onclick=\"handleExcluir("+venda['id']+", "+i+");\" class='button-error pure-button'> "+
           "Excluir"+
          "</button>"+
        "</td> "+
      "</tr>";
    }
    let clienteTbody = document.getElementById('vendaTable').children[1].innerHTML = html;
    createVendaDatatable();
  }

  function getVendas(){
    axios.get("/api/venda")
    .then(function (response) {
      lista_vendas = response.data;
      povoarTabela();
    })
    .catch(function (error) {
      console.error(error);
    });
  }

  window.addEventListener("load", function () {
    getVendas();
  });
</script>