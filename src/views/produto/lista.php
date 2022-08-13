<div class="pure-form">
  <div class="title-list">
    <h2>Lista de Produtos</h2>
  </div>
  <table class="pure-table" id="produtoTable">
    <thead>
      <tr>
        <th>id</th>
        <th>Descrição</th>
        <th>Quantidade U.M.</th>
        <th>(R$) Compra/Venda</th>
        <th>Ações</th>
      </tr>
    </thead>
    <tbody></tbody>
  </table>
</div>
<a href="/produto/cadastro" class="button-success pure-button">
  <span>Cadastrar</span>
</a>
<script type="text/javascript">
  var  lista_produtos = [];
  var produtoDatatable = null;

  function createProdutoDatatable() {
    //https://www.cssscript.com/lightweight-vanilla-data-table-component/
    produtoDatatable = new DataTable(document.getElementById('produtoTable'), patternForDatatable);
  }

  function excluir(id, index){
    axios.delete("/api/produto/deletar/"+id)
    .then(function (response) {
      console.log(response.data);
      if (true === response.data.success) {
        lista_produtos.splice(index, 1);
        document.getElementById('produtoTable').children[1].children[index].remove();
        notifier.success('Produto deletado com sucesso');
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
      "Confirma que deseja excluir o produto?",
      () => {excluir(id, index);},
      () => {}
    );
  }

  function limparTabela(){
    let produtoTable = document.getElementById('produtoTable');
    produtoTable.children[1].innerHTML = "";
  }

  function povoarTabela() {
    let html = '';
    for (let i = 0; i < lista_produtos.length; i++) {
      const produto = lista_produtos[i];
      html += "<tr>"+
        "<td>"+produto['id']+"</td> "+
        "<td>"+produto['descricao']+"</td> "+
        "<td>"+returnBrazilianNumber(produto['quantidade'])+" "+produto['unidade_medida']+"</td> "+
        "<td>"+returnBrazilianCurrency(parseFloat(produto['valor_compra']))+" / "+returnBrazilianCurrency(parseFloat(produto['valor_venda']))+"</td> "+
        "<td> "+
          "<a href='/produto/alteracao/"+produto['id']+"' class='pure-button pure-button-primary'> "+
            "<span>Editar</span> "+
          "</a> "+
          "<button onclick=\"handleExcluir("+produto['id']+", "+i+");\" class='button-error pure-button'> "+
           "Excluir"+
          "</button>"+
        "</td> "+
      "</tr>";
    }
    document.getElementById('produtoTable').children[1].innerHTML = html;
    createProdutoDatatable();
  }
  function getProdutos(){
    axios.get("/api/produto")
    .then(function (response) {
      lista_produtos = response.data;
      povoarTabela();
    })
    .catch(function (error) {
      console.error(error);
    });
  }

  window.addEventListener("load", function () {
    getProdutos();
  });
</script>
