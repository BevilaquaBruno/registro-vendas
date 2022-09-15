<?php
require_once('./src/models/Pessoa.model.php');
require_once('./src/models/Cliente.model.php');

class ControllerCliente {
  // api routes
  public static function Todos($app) {
    $app->validarUsuario($app, "F", true);

    $lista_cliente = ModelCliente::Todos($app->db);

    http_response_code(200);
    echo(json_encode($lista_cliente));
  }

  public static function Um($app, $id){
    $app->validarUsuario($app, "F", true);

    $cliente = ModelCliente::Um($app->db, $id);

    http_response_code(200);
    echo(json_encode($cliente));
  }

  public static function Cadastrar($app) {
    $app->validarUsuario($app, "F", true);

    $cliente = [
      "id_pessoa" => (isset($_POST['id_pessoa']) ? (int)$_POST['id_pessoa'] : NULL),
      "endereco" => (!isset($_POST['endereco']) || '' === $_POST['endereco']) ? null : $_POST['endereco'],
      "uf" => (!isset($_POST['uf']) || $_POST['uf'] === "nenhuma") ? null : $_POST['uf'],
      "bairro" => (!isset($_POST['bairro']) || '' === $_POST['bairro']) ? null : $_POST['bairro'],
      "pais" => (!isset($_POST['pais']) || '' === $_POST['pais']) ? null : $_POST['pais'],
      "cidade" => (!isset($_POST['cidade']) || '' === $_POST['cidade']) ? null : $_POST['cidade']
    ];

    if(null === $cliente['id_pessoa'] || 0 === $cliente['id_pessoa']){
      http_response_code(400);
      echo(json_encode([ "success" => false, "message" => "Falta selecionar a pessoa" ]));
      exit();
    }

    if(null != $cliente['uf']){
      if (false === array_search($cliente['uf'], $app->ufs)) {
        http_response_code(400);
        echo(json_encode([ "success" => false, "message" => "UF inválida" ]));
        exit();
      }
    }

    $result = ModelCliente::Cadastrar($app->db, $cliente);

    http_response_code(200);
    echo(json_encode([ "success" => $result, "message" => "" ]));
  }

  public static function Deletar($app, $id) {
    $app->validarUsuario($app, "F", true);

    $result = ModelCliente::Excluir($app->db, $id);

    http_response_code(200);
    echo(json_encode([ "success" => $result, "message" => true === $result ? "Cliente excluído com sucesso" : "Erro ao excluir cliente" ]));
  }

  public static function Alterar($app) {
    $app->validarUsuario($app, "F", true);

    // create put variable
    global $_PUT;
    Aplicacao::ParsePut();

    $cliente = [
      "id" => (int)$_PUT['id'],
      "id_pessoa" => (isset($_PUT['id_pessoa']) ? (int)$_PUT['id_pessoa'] : NULL),
      "endereco" => (!isset($_PUT['endereco']) || '' === $_PUT['endereco']) ? null : $_PUT['endereco'],
      "uf" => (!isset($_PUT['uf']) || $_PUT['uf'] === "nenhuma") ? null : $_PUT['uf'],
      "bairro" => (!isset($_PUT['bairro']) || '' === $_PUT['bairro']) ? null : $_PUT['bairro'],
      "pais" => (!isset($_PUT['pais']) || '' === $_PUT['pais']) ? null : $_PUT['pais'],
      "cidade" => (!isset($_PUT['cidade']) || '' === $_PUT['cidade']) ? null : $_PUT['cidade']
    ];

    if(null === $cliente['id'] || 0 === $cliente['id']){
      http_response_code(400);
      echo(json_encode([ "success" => false, "message" => "Erro grave ao alterar o cliente, atualize a página" ]));
      exit();
    }

    if(null === $cliente['id_pessoa'] || 0 === $cliente['id_pessoa']){
      http_response_code(400);
      echo(json_encode([ "success" => false, "message" => "Falta selecionar a pessoa" ]));
      exit();
    }

    $result = ModelCliente::Alterar($app->db, $cliente);

    http_response_code(200);
    echo(json_encode([ "success" => $result, "message" => "" ]));
  }

  // view routes
  public static function Lista($app){
    $app->validarUsuario($app, "F");

    $dados = [
      'pagina' => 'cliente/lista'
    ];

    ControllerGeral::CarregaTela($app, $dados);
  }

  public static function Cadastro($app) {
    $app->validarUsuario($app, "F");

    $dados = [
      'pagina' => 'cliente/cadastro',
      'acao' => "cadastrar",
      "pessoas" => ModelPessoa::Todas($app->db),
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
    ControllerGeral::CarregaTela($app, $dados);
  }

  public static function Alteracao($app, $id) {
    $app->validarUsuario($app, "F");

    $dados = [
      'pagina' => 'cliente/cadastro',
      'acao' => "alterar",
      "pessoas" => ModelPessoa::Todas($app->db),
      "ufs" => $app->ufs,
      "cliente" => ModelCliente::Um($app->db, $id)
    ];
    ControllerGeral::CarregaTela($app, $dados);
  }
}
?>
