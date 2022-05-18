<form class="pure-form pure-form-aligned" id="vendaForm" action="index.php?m=venda&a=<?=$dados['acao']?>" method="post">
<!-- venda_produtos -->
<input type="hidden" name="venda_produtos" id="venda_produtos" value="[]">
  <div class="pure-control-group">
    <div class="pure-u-1-3"></div>
    <div class="pure-u-1-3">
      <h2>Nova Venda</h2>
    </div>
  </div>
  <!-- id venda -->
  <input maxlength="10" type="hidden" name="id" value="<?=$dados['venda']['id']?>">
  <fieldset>
    <!-- Cliente -->
    <div class="pure-control-group">
      <label for="id_cliente">Cliente:</label>
      <div class="pure-u-1-3">
        <select class="full-width" name="id_cliente" id="id_cliente">
          <option value="0">Selecione...</option>
          <?php foreach ($dados['clientes'] as $p) { ?>
            <option value="<?=$p['id']?>" <?=$p['id'] == $dados['venda']['id_cliente'] ? 'selected': ''?>><?=$p['id'].' - ' . $p['nome'] . ' - ' . $p['email']?></option>
          <?php } ?>
        </select>
      </div>
      <span class="pure-form-message-inline">* Obrigatório.</span>
    </div>

    <!-- Vendedor -->
    <div class="pure-control-group">
      <label for="id_funcionario">Vendedor:</label>
      <div class="pure-u-1-3">
        <select class="full-width" name="id_funcionario" id="id_funcionario">
          <option value="0">Selecione...</option>
          <?php foreach ($dados['funcionarios'] as $p) { ?>
            <option value="<?=$p['id']?>" <?=$p['id'] == $dados['venda']['id_funcionario'] ? 'selected': ''?>><?=$p['id'].' - ' . $p['nome'] . ' - ' . $p['email']?></option>
          <?php } ?>
        </select>
      </div>
      <span class="pure-form-message-inline">* Obrigatório.</span>
    </div>

    <!-- Data Venda -->
    <div class="pure-control-group">
      <label for="data_venda">Data:</label>
      <input required type="date" name="data_venda" id="data_venda" value="<?=$dados['venda']['data_venda_original']?>">
      <span class="pure-form-message-inline">* Obrigatório.</span>
    </div>

    <div class="pure-control-group">
      <div class="pure-u-1-8"></div>
      <div class="pure-u-4-5">
        <!-- Tabela Venda produto -->
        <table id="tableVendaProduto" class="pure-table">
          <thead>
            <tr>
              <th class='hidden'>id</th>
              <th class='hidden'>id_produto</th>
              <th>Produto</th>
              <th>Quantidade</th>
              <th>Valor Unit.</th>
              <th>Valor Total</th>
              <th>Ações</th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
      </div>
      <div class="pure-u-1-8"></div>
      <div class="pure-u-1-3">
        <a class="hidden" id="openModalLink" href="#" data-modal-trigger aria-controls="modalProduto" aria-expanded="false">Open modal</a>
        <div onclick="abreFormProduto();" class="button-success pure-button">Adicionar</div>
      </div>
    </div>

    <!-- Nome -->
    <div class="pure-control-group">
      <label for="observacao">Observação: </label>
      <div class="pure-u-4-5">
        <textarea maxlength="200" class="full-width" name="observacao" id="observacao" cols="10" rows="3"><?=$dados['venda']['observacao']?></textarea>
      </div>
    </div>

    <!--Controles -->
    <div class="pure-controls">
      <div onclick="handleSalvar();" class="button-success pure-button">
          Gravar
      </div>
      <a class="button-error pure-button" href="index.php?m=venda&a=lista">
        Voltar
      </a>
    </div>
  </fieldset>

  <section class="modal" id="modalProduto" data-modal-target>
    <div class="modal__overlay" data-modal-close tabindex="-1"></div>
    <div class="modal__wrapper">
      <div class="modal__header">
        <div class="modal__title" id="modalProdutoTitle"></div>
        <button class="modal__close" data-modal-close aria-label="Close Modal"></button>
      </div>
      <div class="modal__content">
        <!-- Index -->
        <input type="hidden" id="index_produto_form">
        <!-- id -->
        <input type="hidden" id="id_form" value="0">
        <!-- Produto -->
        <div class="pure-control-group">
          <label for="id_produto_form">Produto:</label>
          <div class="pure-u-3-5">
            <select onchange="changeProdutoHandler();" class="full-width" name="id_produto_form" id="id_produto_form">
              <option value="0">Selecione...</option>
              <?php foreach ($dados['produtos'] as $p) { ?>
                <option produto-descricao="<?=$p['descricao']?>" produto-valor-venda="<?=$p['valor_venda']?>" value="<?=$p['id']?>">Qtd <?=$p['quantidade']?> <?=$p['descricao']?></option>
              <?php } ?>
            </select>
          </div>
        </div>

        <!-- Quantidade -->
        <div class="pure-control-group">
          <label for="quantidade_form">Quantidade:</label>
          <div class="pure-u-3-5">
            <input class="quantidade-mask full-width" required name="quantidade_form" id="quantidade_form" type="text" />
          </div>
        </div>

        <!-- Valor Unitário (R$) -->
        <div class="pure-control-group">
          <label for="valor_unitario_form">Valor Unit. (R$):</label>
          <div class="pure-u-3-5">
            <input class="valor-unitario-mask full-width" required name="valor_unitario_form" id="valor_unitario_form" type="text" />
          </div>
        </div>

        <!--Controles -->
        <div class="pure-controls">
          <div onclick="adicionarProduto();" class="button-success pure-button">
            Gravar
          </div>
          <div id="btnVoltarModalProduto" class="button-error pure-button" data-modal-close aria-label="Close Modal">
            Voltar
          </div>
        </div>
      </div>
    </div>
  </section>
</form>
<script type="text/javascript">
  var vendaProdutoDatatable = null;
  var lista_venda_produto = <?=json_encode($dados['venda']['venda_produto'])?>;

  function handleSalvar() {
    notifier.confirm(
      "Confirma que deseja <?=$dados['acao']?> a venda?",
      () => {salvar();},
      () => {}
    );
  }

  function abreFormProduto(i = null) {
    if(i != null){
      document.getElementById('modalProdutoTitle').innerText = 'Alterar produto';
      document.getElementById('id_produto_form').value = lista_venda_produto[i].id_produto;
      document.getElementById('quantidade_form').value = returnBrazilianNumber(parseFloat(lista_venda_produto[i].quantidade));
      document.getElementById('valor_unitario_form').value = returnBrazilianNumber(parseFloat(lista_venda_produto[i].valor_unitario));
      document.getElementById('index_produto_form').value = i;
      document.getElementById('id_form').value = lista_venda_produto[i].id;
      document.getElementById('id_produto_form').setAttribute('readonly', true);
    }else{
      document.getElementById('modalProdutoTitle').innerText = 'Adicionar produto';
      document.getElementById('id_produto_form').value = 0;
      document.getElementById('quantidade_form').value = '0,000';
      document.getElementById('valor_unitario_form').value = '0,00';
      document.getElementById('index_produto_form').value = 'novo';
      document.getElementById('id_form').value = 0;
      document.getElementById('id_produto_form').removeAttribute('readonly');
    }

    document.getElementById('openModalLink').click();
  }

  function adicionarProduto(){
    let selectProduto = document.getElementById('id_produto_form');
    let selectedProduto = selectProduto.options[selectProduto.selectedIndex];
    let descricao_produto = selectedProduto.getAttribute('produto-descricao');
    let id_produto = document.getElementById('id_produto_form').value;
    let quantidade = document.getElementById('quantidade_form').value;
    let valor_unitario = document.getElementById('valor_unitario_form').value;
    let index_produto = document.getElementById('index_produto_form').value;
    let id = document.getElementById('id_form').value;

    if('0' == id_produto){
      notifier.alert('Produto é obrigatório.');
      return false;
    }

    if('novo' === index_produto){
      lista_venda_produto.push({
        'id': 0,
        'id_produto': id_produto,
        'descricao_produto': descricao_produto,
        'quantidade': quantidade.replaceAll('.', '').replaceAll(',', '.'),
        'valor_unitario': valor_unitario.replaceAll('.', '').replaceAll(',', '.')
      });
    }else{
      lista_venda_produto[index_produto] = {
        'id': id,
        'id_produto': id_produto,
        'descricao_produto': descricao_produto,
        'quantidade': quantidade.replaceAll('.', '').replaceAll(',', '.'),
        'valor_unitario': valor_unitario.replaceAll('.', '').replaceAll(',', '.')
      };
    }

    document.getElementById('btnVoltarModalProduto').click();
    povoarTableVendaProduto();
  }

  function changeProdutoHandler(){
    let selectProduto = document.getElementById('id_produto_form');
    let selectedElement = selectProduto.options[selectProduto.selectedIndex];

    if(selectedElement.getAttribute('value') != '0')
      document.getElementById('valor_unitario_form').value = returnBrazilianNumber(parseFloat(selectedElement.getAttribute('produto-valor-venda')));
    else
      document.getElementById('valor_unitario_form').value = 0;
  }

  function createVendaProdutoDatatable() {
    //https://www.cssscript.com/lightweight-vanilla-data-table-component/
    vendaProdutoDatatable = new DataTable(document.getElementById('tableVendaProduto'),
    {
      sortable: false,
      searchable: false,
      perPage: 100,
      perPageSelect: [],
      labels: {
        placeholder: "Procurando...", // The search input placeholder
        perPage: "{select} registros por página", // per-page dropdown label
        noRows: "Nenhum registro encontrado", // Message shown when there are no search results
        info: "Mostrando de {start} até {end} de {rows} registros" //
      },
      layout: {
        top: "",
        bottom: ""
      }
    });
  }

  function povoarTableVendaProduto(){
    document.getElementById('tableVendaProduto').children[1].innerHTML = '';

    let html = '';
    for (let i = 0; i < lista_venda_produto.length; i++) {
      const venda = lista_venda_produto[i];
      html += "<tr "+(-1 < venda['id'].toString().indexOf('-remover') ? 'class="hidden"': '')+" >"+
        "<td class='hidden'>"+venda['id']+"</td> "+
        "<td class='hidden'>"+venda['id_produto']+"</td> "+
        "<td>"+venda['descricao_produto']+"</td> "+
        "<td>"+returnBrazilianNumber(parseFloat(venda['quantidade']))+"</td> "+
        "<td>"+returnBrazilianCurrency(parseFloat(venda['valor_unitario']))+"</td> "+
        "<td>"+returnBrazilianCurrency( parseFloat(venda['valor_unitario']) * venda['quantidade'] )+"</td> "+
        "<td> "+
          "<div onclick='abreFormProduto(\""+i+"\");' class='pure-button button-xsmall pure-button-primary'> "+
            "<span>Editar</span> "+
          "</div> "+
          "<div onclick=\"excluirProduto("+i+");\" class='button-error button-xsmall pure-button'> "+
           "Excluir"+
          "</div>"+
        "</td> "+
      "</tr>";
    }

    document.getElementById('tableVendaProduto').children[1].innerHTML = html;
    createVendaProdutoDatatable();
  }

  function excluirProduto(index) {
    if('0' == lista_venda_produto[index].id){
      lista_venda_produto.splice(index, 1);
    }else{
      lista_venda_produto[index].id = lista_venda_produto[index].id+'-remover';
    }

    povoarTableVendaProduto();
  }

  function salvar(){
    document.getElementById("venda_produtos").value = JSON.stringify(lista_venda_produto);
    let vendaForm = document.getElementById("vendaForm");

    axios.post(vendaForm.getAttribute("action"),
      new FormData(vendaForm)
    )
    .then(function (response) {
      if(false === response.data.success) {
        notifier.alert(response.data.message);
      }else if(true === response.data.success){
        window.location.href = "index.php?m=venda&a=lista";
      }else{
        notifier.alert("Erro grave ao gravar a venda");
      }
    })
    .catch(function (error) {
      console.error(error);
    });
  }

  window.addEventListener('load', function(){
    povoarTableVendaProduto();

    let quantidadePatternForMask = Object.assign({}, moneyPatternForMask);
    quantidadePatternForMask.fractionDigits = 3;
    quantidadePatternForMask.allowNegative = true;

    SimpleMaskMoney.setMask("#quantidade_form", quantidadePatternForMask);
    SimpleMaskMoney.setMask("#valor_unitario_form", moneyPatternForMask);
  });
</script>