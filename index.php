<?php
  $lista_modulos = ['inicial', 'pessoa', 'funcionario'];
  $lista_acoes = ['lista', 'consulta', 'cadastro', 'cadastrar', 'alteracao', 'alterar', 'deletar', 'listajson'];

  $modulo = isset($_GET['m']) ? $_GET['m'] : null;
  $acao = isset($_GET['a']) ? $_GET['a'] : null;

  $modulo = array_search($modulo, $lista_modulos) ? $modulo : 'inicial';
  $acao = array_search($acao, $lista_acoes) ? $acao : 'lista';


  require_once('Aplicacao.php');
  $app = new Aplicacao();

  switch ($modulo) {
    case 'inicial':
      require_once('./controllers/Geral.controller.php');
      $controllerGeral = new ControllerGeral();
      $controllerGeral->$acao($app);
      break;
    case 'pessoa':
      require_once('./controllers/pessoa.controller.php');
      $controllerPessoa = new ControllerPessoa();
      $controllerPessoa->$acao($app);
      break;
    case 'funcionario':
      require_once('./controllers/Funcionario.controller.php');
      $controllerFuncionario = new ControllerFuncionario();
      $controllerFuncionario->$acao($app);
      break;
    default:
      require_once('./controllers/Geral.controller.php');
      $controllerGeral = new ControllerGeral();
      $controllerGeral->$acao($app);
      break;
  }
?>
