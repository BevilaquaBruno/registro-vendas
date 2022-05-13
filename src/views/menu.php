<div class="pure-menu pure-menu-horizontal menu-color">
  <a href="#" class="pure-menu-heading pure-menu-link">
    <?=(isset($_SESSION['islogged']) && true === $_SESSION['islogged']) ? $_SESSION['nome'] : 'Software Loja' ?>
  </a>
  <ul class="pure-menu-list">
    <li class="pure-menu-item">
      <a href="index.php" class="pure-menu-link">Início</a>
    </li>
    <?php if(isset($_SESSION['islogged']) && true === $_SESSION['islogged']){ ?>
      <li class="pure-menu-item">
        <a href="#" onclick="totallyLogout();" class="pure-menu-link">Logout</a>
      </li>
    <?php }else{ ?>
      <li class="pure-menu-item">
        <a href="index.php?m=login" class="pure-menu-link">Login</a>
      </li>
    <?php } ?>
    <?php if(isset($_SESSION['islogged']) && true === $_SESSION['islogged']){ ?>
      <li class="pure-menu-item pure-menu-has-children pure-menu-allow-hover">
        <a href="#" id="menuLink1" class="pure-menu-link">Cadastros</a>
        <ul class="pure-menu-children">
          <li class="pure-menu-item">
            <a href="index.php?m=pessoa&a=lista" class="pure-menu-link">Pessoas</a>
          </li>
          <li class="pure-menu-item">
            <a href="index.php?m=funcionario&a=lista" class="pure-menu-link">Funcionários</a>
          </li>
          <li class="pure-menu-item">
            <a href="index.php?m=produto&a=lista" class="pure-menu-link">Produtos</a>
          </li>
          <?php if("A" === $_SESSION['tipo']){ ?>
            <li class="pure-menu-item">
              <a href="index.php?m=usuario&a=lista" class="pure-menu-link">Usuários</a>
            </li>
          <?php } ?>
        </ul>
      </li>
    <?php } ?>
  </ul>
</div>
<script type="text/javascript">
  function totallyLogout() {
    axios.post("index.php?m=login&a=deslogar")
    .then(function (response) {
      if(false === response.data.success) {
        notifier.alert(response.data.message);
      }else if(true === response.data.success){
        window.location.href = "index.php";
      }else{
        notifier.alert("Erro ao deslogar");
      }
    })
    .catch(function (error) {
      console.error(error);
    });
  }
</script>