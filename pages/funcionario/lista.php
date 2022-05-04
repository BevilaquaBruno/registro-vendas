<table class="myTable" id="funcionarioTable">
  <thead>
    <tr>
      <th>id</th>
      <th>Nome</th>
      <th>email</th>
      <th>Admissão</th>
      <th>Salário</th>
      <th>Ações</th>
    </tr>
  </thead>
  <tbody></tbody>
</table>
<button class="myButton myButtonGreen">
  <a href="index.php?m=funcionario&a=cadastro">Cadastrar</a>
</button>

<script type="text/javascript">
  var  lista_funcionarios = [];
  window.onload = function () {
    //https://www.cssscript.com/lightweight-vanilla-data-table-component/
    let funcionarioTable = document.getElementById('funcionarioTable');
    var dataTable = new DataTable(funcionarioTable, {});

    getFuncionarios();
  }

  function excluir(id, index){
    axios.get("index.php?m=funcionario&a=deletar&id="+id)
    .then(function (response) {
      console.log(response.data);
      if (true === response.data.success) {
        lista_funcionarios.splice(index, 1);
        document.getElementById('funcionarioTable').children[1].children[index].remove();
      }
    })
    .catch(function (error) {
      console.error(error);
    });
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
        "<td>"+funcionario['salario']+"</td> "+
        "<td> "+
          "<button class='myButton myButtonBlue'> "+
            "<a href='index.php?m=funcionario&a=alteracao&id="+funcionario['id']+"'>Editar</a> "+
          "</button> "+
          "<button class='myButton myButtonRed'> "+
           "<span onclick=\"excluir("+funcionario['id']+", "+i+");\">Excluir</span> "+
          "</button>"+
        "</td> "+
      "</tr>";
    }
    let funcionarioTBody = document.getElementById('funcionarioTable').children[1].innerHTML = html;
  }

  function getFuncionarios(){
    axios.get("index.php?m=funcionario&a=listajson")
    .then(function (response) {
      lista_funcionarios = response.data;
      povoarTabela();
    })
    .catch(function (error) {
      console.error(error);
    });
  }
</script>