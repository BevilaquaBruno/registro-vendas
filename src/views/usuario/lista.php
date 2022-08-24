<div class="pure-form">
  <div class="title-list">
    <h2>Lista de Usuários</h2>
  </div>
  <table class="pure-table" id="usuarioTable">
    <thead>
      <tr>
        <th>id</th>
        <th>Nome</th>
        <th>Email</th>
        <th>Tipo</th>
        <th>Ações</th>
      </tr>
    </thead>
    <tbody></tbody>
  </table>
</div>
<a href="/usuario/cadastro" class="button-success pure-button">
  <span>Cadastrar</span>
</a>
<script type="text/javascript">
  var  lista_usuarios = [];
  var usuarioDatatable = null;

  function createUsuarioDatatable() {
    //https://www.cssscript.com/lightweight-vanilla-data-table-component/
    usuarioDatatable = new DataTable(document.getElementById('usuarioTable'), patternForDatatable);
  }

  function excluir(id, index){
    axios.delete("/api/usuario/"+id)
    .then(function (response) {
      console.log(response.data);
      if (true === response.data.success) {
        lista_usuarios.splice(index, 1);
        document.getElementById('usuarioTable').children[1].children[index].remove();
        notifier.success('Usuário deletado com sucesso');
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
      "Confirma que deseja excluir o usuário?",
      () => {excluir(id, index);},
      () => {}
    );
  }

  function limparTabela(){
    let usuarioTable = document.getElementById('usuarioTable');
    usuarioTable.children[1].innerHTML = "";
  }

  function povoarTabela() {
    let html = '';
    for (let i = 0; i < lista_usuarios.length; i++) {
      const usuario = lista_usuarios[i];
      let tipo_usuario = '-';
      switch (usuario['tipo']) {
        case 'A':
          tipo_usuario = 'Administrador';
          break;
        case 'F':
          tipo_usuario = 'Funcionário';
          break;
        default:
          tipo_usuario = '-';
          break;
      }
      html += "<tr>"+
        "<td>"+usuario['id']+"</td> "+
        "<td>"+usuario['nome']+"</td> "+
        "<td>"+usuario['email']+"</td> "+
        "<td>"+(tipo_usuario)+"</td> "+
        "<td> "+
          "<a href='/usuario/alteracao/"+usuario['id']+"' class='pure-button pure-button-primary'> "+
            "<span>Editar</span> "+
          "</a> "+
          "<button onclick=\"handleExcluir("+usuario['id']+", "+i+");\" class='button-error pure-button'> "+
           "Excluir"+
          "</button>"+
        "</td> "+
      "</tr>";
    }
    document.getElementById('usuarioTable').children[1].innerHTML = html;
    createUsuarioDatatable();
  }
  function getUsuarios(){
    axios.get("/api/usuario")
    .then(function (response) {
      lista_usuarios = response.data;
      povoarTabela();
    })
    .catch(function (error) {
      console.error(error);
    });
  }

  window.addEventListener("load", function () {
    getUsuarios();
  });
</script>
