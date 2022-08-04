<?php
  require_once('Aplicacao.php');
  require_once('./src/controllers/Geral.controller.php');
  require_once('./src/controllers/pessoa.controller.php');
  require_once('./src/controllers/Funcionario.controller.php');
  require_once('./src/controllers/Login.controller.php');
  require_once('./src/controllers/Usuario.controller.php');
  require_once('./src/controllers/Produto.controller.php');
  require_once('./src/controllers/Cliente.controller.php');
  require_once('./src/controllers/Venda.controller.php');

  session_start();
  $lista_modulos = ['inicial', 'pessoa', 'funcionario', 'login', 'usuario', 'produto', 'cliente', 'venda'];
  $lista_acoes = ['lista', 'cadastro', 'cadastrar', 'alteracao', 'alterar', 'deletar', 'listajson', 'logar', 'deslogar'];

  $modulo = isset($_GET['m']) ? $_GET['m'] : null;
  $acao = isset($_GET['a']) ? $_GET['a'] : null;

  // if array_key returns 0 there is no problem
  $modulo = false === array_search($modulo, $lista_modulos) ? 'inicial' : $modulo;
  $acao = false === array_search($acao, $lista_acoes) ? 'lista' : $acao;

  $app = new Aplicacao();

  try {
    switch ($modulo) {
      case 'inicial':
        ControllerGeral::$acao($app);
        break;
      case 'pessoa':
        ControllerPessoa::$acao($app);
        break;
      case 'funcionario':
        ControllerFuncionario::$acao($app);
        break;
      case 'login':
        ControllerLogin::$acao($app);
        break;
      case 'usuario':
        ControllerUsuario::$acao($app);
        break;
      case 'produto':
        ControllerProduto::$acao($app);
        break;
      case 'cliente':
        ControllerCliente::$acao($app);
        break;
      case 'venda':
        ControllerVenda::$acao($app);
        break;
      default:
        ControllerGeral::CarregaTela($app, ['pagina' => 'geral/404']);
        break;
    }
  } catch (\Throwable $th) {
    //var_dump($th);exit();
    ControllerGeral::CarregaTela($app, ['pagina' => 'geral/404']);
  }
?>
