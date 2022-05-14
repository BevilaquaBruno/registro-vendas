<?php
require_once('./src/models/Pessoa.model.php');
require_once('./src/models/CLiente.model.php');

class ControllerCliente {
  public function lista($app){
    $app->validarUsuario($app, "F");

    $dados = [
      'pagina' => 'cliente/lista'
    ];

    $controllerGeral = new ControllerGeral();
    $controllerGeral->carregaTela($app, $dados);
  }

  public function listajson($app){
    $mdlCliente = new ModelCliente();
    $lista_cliente = $mdlCliente->todos($app->db);

    echo(json_encode($lista_cliente));
  }

  public function cadastro($app) {
    $app->validarUsuario($app, "F");

    $mdlPessoa = new ModelPessoa();
    $dados = [
      'pagina' => 'cliente/cadastro',
      'acao' => "cadastrar",
      "pessoas" => $mdlPessoa->todas($app->db),
      "ufs" => $app->ufs,
      'cliente' => [
        'id' => 0,
        'id_pessoa' => '',
        'endereco' => '',
        'uf' => '',
        'bairro' => '',
        'pais' => '',
        'cidade' => ''
      ]
    ];
    $controllerGeral = new ControllerGeral();
    $controllerGeral->carregaTela($app, $dados);
  }

  public function cadastrar($app) {
    $app->validarUsuario($app, "F", true);

    $cliente = [
      "id_pessoa" => (int)$_POST['id_pessoa'],
      "endereco" => ('' === $_POST['endereco']) ? null : $_POST['endereco'],
      "uf" => ($_POST['uf']) === "nenhuma" ? null : $_POST['uf'],
      "bairro" => ('' === $_POST['bairro']) ? null : $_POST['bairro'],
      "pais" => ('' === $_POST['pais']) ? null : $_POST['pais'],
      "cidade" => ('' === $_POST['cidade']) ? null : $_POST['cidade']
    ];

    if(null === $cliente['id_pessoa'] || 0 === $cliente['id_pessoa']){
      echo(json_encode([ "success" => false, "message" => "Falta selecionar a pessoa" ]));
      exit();
    }

    if(null != $cliente['uf']){
      if (false === array_search($cliente['uf'], $app->ufs)) {
        echo(json_encode([ "success" => false, "message" => "UF inválida" ]));
        exit();
      }
    }

    $mdlCliente = new ModelCliente();
    $result = $mdlCliente->cadastrar($app->db, $cliente);

    echo(json_encode([ "success" => $result, "message" => "" ]));
  }

  public function deletar($app) {
    $app->validarUsuario($app, "F", true);

    $id = $_GET['id'];

    $mdlCliente = new ModelCliente();
    $result = $mdlCliente->excluir($app->db, $id);

    echo(json_encode([ "success" => $result, "message" => true === $result ? "Cliente excluído com sucesso" : "Erro ao excluir cliente" ]));
  }

  public function alteracao($app) {
    $app->validarUsuario($app, "F");

    $mdlPessoa = new ModelPessoa();
    $mdlCliente = new ModelCliente();
    $id = $_GET['id'];

    $dados = [
      'pagina' => 'cliente/cadastro',
      'acao' => "alterar",
      "pessoas" => $mdlPessoa->todas($app->db),
      "ufs" => $app->ufs,
      "cliente" => $mdlCliente->um($app->db, $id)
    ];
    $controllerGeral = new ControllerGeral();
    $controllerGeral->carregaTela($app, $dados);
  }

  public function alterar($app) {
    $app->validarUsuario($app, "F", true);

    $cliente = [
      "id" => (int)$_POST['id'],
      "id_pessoa" => $_POST['id_pessoa'],
      "endereco" => $_POST['endereco'],
      "uf" => $_POST['uf'],
      "bairro" => $_POST['bairro'],
      "pais" => $_POST['pais'],
      "cidade" => $_POST['cidade']
    ];

    if(null === $cliente['id'] || 0 === $cliente['id']){
      echo(json_encode([ "success" => false, "message" => "Erro grave ao alterar o cliente, atualize a página" ]));
      exit();
    }

    if(null === $cliente['id_pessoa'] || 0 === $cliente['id_pessoa']){
      echo(json_encode([ "success" => false, "message" => "Falta selecionar a pessoa" ]));
      exit();
    }

    $mdlCliente = new ModelCliente();
    $result = $mdlCliente->alterar($app->db, $cliente);

    echo(json_encode([ "success" => $result, "message" => "" ]));
  }
}
?>
