<div class="pure-menu pure-menu-horizontal menu-color">
  <a href="#" class="pure-menu-heading pure-menu-link">
    <?=(isset($_SESSION['islogged']) && true === $_SESSION['islogged']) ? $_SESSION['nome'] : 'Software Loja' ?>
  </a>
  <ul class="pure-menu-list">
    <li class="pure-menu-item">
      <a href="/" class="pure-menu-link">Início</a>
    </li>
    <?php if(isset($_SESSION['islogged']) && true === $_SESSION['islogged']){ ?>
      <li class="pure-menu-item">
        <a href="#" onclick="totallyLogout();" class="pure-menu-link">Logout</a>
      </li>
    <?php }else{ ?>
      <li class="pure-menu-item">
        <a href="/login" class="pure-menu-link">Login</a>
      </li>
      <li class="pure-menu-item">
        <a href="/signup" class="pure-menu-link">Criar Conta</a>
      </li>
    <?php } ?>
    <?php if(isset($_SESSION['islogged']) && true === $_SESSION['islogged'] && "W" === $_SESSION['tipo']){ ?>
      <li class="pure-menu-item">
        <a href="/usuario/perfil" class="pure-menu-link">Perfil</a>
      </li>
    <?php } ?>
    <?php if(isset($_SESSION['islogged']) && true === $_SESSION['islogged'] && ("A" === $_SESSION['tipo'] || "F" === $_SESSION['tipo'])){ ?>
      <li class="pure-menu-item pure-menu-has-children pure-menu-allow-hover">
        <a href="#" id="menuLink1" class="pure-menu-link">Cadastros</a>
        <ul class="pure-menu-children">
          <li class="pure-menu-item">
            <a href="/pessoa" class="pure-menu-link">Pessoas</a>
          </li>
          <li class="pure-menu-item">
            <a href="/funcionario" class="pure-menu-link">Funcionários</a>
          </li>
          <li class="pure-menu-item">
            <a href="/produto" class="pure-menu-link">Produtos</a>
          </li>
          <li class="pure-menu-item">
            <a href="/cliente" class="pure-menu-link">Clientes</a>
          </li>
          <?php if("A" === $_SESSION['tipo']){ ?>
            <li class="pure-menu-item">
              <a href="/usuario" class="pure-menu-link">Usuários</a>
            </li>
          <?php } ?>
        </ul>
      </li>
      <li class="pure-menu-item">
        <a href="/venda" class="pure-menu-link">Venda</a>
      </li>
    <?php } ?>
    <li class="pure-menu-item">
      <a href="/developer" class="pure-menu-link">Developer</a>
    </li>
  </ul>
</div>
<script type="text/javascript">
  function totallyLogout() {
    axios.get("/login/sair")
    .then(function (response) {
      if(false === response.data.success) {
        notifier.alert(response.data.message);
      }else if(true === response.data.success){
        window.location.href = "/inicial";
      }else{
        notifier.alert("Erro ao deslogar");
      }
    })
    .catch(function (error) {
      console.error(error);
    });
  }
</script>