<?php
  session_start();
  $lista_modulos = ['inicial', 'pessoa', 'funcionario', 'login', 'usuario'];
  $lista_acoes = ['lista', 'cadastro', 'cadastrar', 'alteracao', 'alterar', 'deletar', 'listajson', 'logar', 'deslogar'];

  $modulo = isset($_GET['m']) ? $_GET['m'] : null;
  $acao = isset($_GET['a']) ? $_GET['a'] : null;

  // if array_key returns 0 there is no problem
  $modulo = array_search($modulo, $lista_modulos) ? $modulo : 'inicial';
  $acao = array_search($acao, $lista_acoes) ? $acao : 'lista';


  require_once('Aplicacao.php');
  $app = new Aplicacao();

  try {
    switch ($modulo) {
      case 'inicial':
        require_once('./src/controllers/Geral.controller.php');
        $controllerGeral = new ControllerGeral();
        $controllerGeral->$acao($app);
        break;
      case 'pessoa':
        require_once('./src/controllers/pessoa.controller.php');
        $controllerPessoa = new ControllerPessoa();
        $controllerPessoa->$acao($app);
        break;
      case 'funcionario':
        require_once('./src/controllers/Funcionario.controller.php');
        $controllerFuncionario = new ControllerFuncionario();
        $controllerFuncionario->$acao($app);
        break;
      case 'login':
        require_once('./src/controllers/Login.controller.php');
        $controllerLogin = new ControllerLogin();
        $controllerLogin->$acao($app);
        break;
      case 'usuario':
        require_once('./src/controllers/Usuario.controller.php');
        $controllerUsuario = new ControllerUsuario();
        $controllerUsuario->$acao($app);
        break;
      default:
        require_once('./src/controllers/Geral.controller.php');
        $controllerGeral = new ControllerGeral();
        $controllerGeral->$acao($app);
        break;
    }
  } catch (\Throwable $th) {
    require_once('./src/controllers/Geral.controller.php');
    $controllerGeral = new ControllerGeral();
    $controllerGeral->carregaTela($app, ['pagina' => 'geral/404']);
  }
?>
