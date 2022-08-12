<?php

session_start();
// Require composer autoloader
require __DIR__ . '/vendor/autoload.php';

// required files
require_once('Aplicacao.php');
require_once('./src/controllers/Geral.controller.php');
require_once('./src/controllers/Pessoa.controller.php');
require_once('./src/controllers/Cliente.controller.php');
require_once('./src/controllers/Funcionario.controller.php');
require_once('./src/controllers/Login.controller.php');

// Create Router instance
$router = new \Bramus\Router\Router();

$app = new Aplicacao();

// Define routes
//css / js routes
$router->mount('/public', function() use($router){
  $router->get('/javascripts/([A-Za-z0-9-_\.]+).js', function($filename){
    echo(file_get_contents('./public/javascripts/'.$filename));
  });

  $router->get('/styles/([A-Za-z0-9-_\.]+).css', function($filename){
    echo(file_get_contents('./public/styles/'.$filename));
  });
});

// tela inicial
$router->all('/', function () use($app) {
  ControllerGeral::Lista($app);
});

// api routes
$router->mount('/api', function() use($router, $app){
  $router->mount('/pessoa', function () use($router, $app) {
    $router->get('/', function() use($app){
      ControllerPessoa::Todas($app);
    });

    $router->get('/(\d+)', function($id) use($app){
      ControllerPessoa::Uma($app, $id);
    });

    $router->post('/cadastrar', function() use($app){
      ControllerPessoa::Cadastrar($app);
    });

    $router->delete('/deletar/(\d+)', function($id) use($app){
      ControllerPessoa::Deletar($app, $id);
    });

    $router->put('/alterar', function() use($app){
      ControllerPessoa::Alterar($app);
    });
  });

  $router->mount('/cliente', function () use($router, $app) {
    $router->get('/', function () use ($app) {
        ControllerCliente::Todos($app);
    });

    $router->get('/(\d+)', function ($id) use ($app) {
        ControllerCliente::Um($app, $id);
    });

    $router->post('/cadastrar', function () use ($app) {
        ControllerCliente::Cadastrar($app);
    });

    $router->delete('/deletar/(\d+)', function ($id) use ($app) {
        ControllerCliente::Deletar($app, $id);
    });

    $router->put('/alterar', function () use ($app) {
        ControllerCliente::Alterar($app);
    });
  });

  $router->mount('/funcionario', function () use($router, $app) {
    $router->get('/', function () use ($app) {
        ControllerFuncionario::Todos($app);
    });

    $router->get('/(\d+)', function ($id) use ($app) {
        ControllerFuncionario::Um($app, $id);
    });

    $router->post('/cadastrar', function () use ($app) {
        ControllerFuncionario::Cadastrar($app);
    });

    $router->delete('/deletar/(\d+)', function ($id) use ($app) {
        ControllerFuncionario::Deletar($app, $id);
    });

    $router->put('/alterar', function () use ($app) {
        ControllerFuncionario::Alterar($app);
    });
  });
});

// view routes
// inicial routes
$router->mount('/inicial', function() use($router, $app){
  $router->get('/', function() use($app) {
    ControllerGeral::Lista($app);
  });
});

// pessoa routes
$router->mount('/pessoa', function() use($router, $app){
  $router->get('/', function() use($app) {
    ControllerPessoa::Lista($app);
  });

  $router->get('/cadastro', function() use($app) {
    ControllerPessoa::Cadastro($app);
  });

  $router->get('/alteracao/(\d+)', function($id) use($app) {
    ControllerPessoa::Alteracao($app, $id);
  });
});

// cliente routes
$router->mount('/cliente', function() use($router, $app){
  $router->get('/', function() use($app) {
    ControllerCliente::Lista($app);
  });

  $router->get('/cadastro', function() use($app) {
    ControllerCliente::Cadastro($app);
  });

  $router->get('/alteracao/(\d+)', function($id) use($app) {
    ControllerCliente::Alteracao($app, $id);
  });
});

// funcionario routes
$router->mount('/funcionario', function() use($router, $app){
  $router->get('/', function() use($app) {
    ControllerFuncionario::Lista($app);
  });

  $router->get('/cadastro', function() use($app) {
    ControllerFuncionario::Cadastro($app);
  });

  $router->get('/alteracao/(\d+)', function($id) use($app) {
    ControllerFuncionario::Alteracao($app, $id);
  });
});

// login routes
$router->mount('/login', function() use($router, $app){
  $router->get('/', function () use ($app) {
    ControllerLogin::Lista($app);
  });

  $router->post('/', function () use ($app) {
    ControllerLogin::Logar($app);
  });

  $router->get('/sair', function () use ($app) {
    ControllerLogin::Deslogar($app);
  });
});

// 404 pages
$router->set404('/', function() use($app) {
  ControllerGeral::CarregaTela($app, ['pagina' => 'geral/404']);
});

$router->set404('/api(/.*)?', function() {
  header('HTTP/1.1 404 Not Found');
  header('Content-Type: application/json');

  $jsonArray = array();
  $jsonArray['status'] = "404";
  $jsonArray['status_text'] = "route not defined";

  echo json_encode($jsonArray);
});

// Run it!
$router->run();
