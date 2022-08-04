<?php
require_once('./src/models/Pessoa.model.php');
require_once('./src/models/Cliente.model.php');

class ControllerCliente {
  public static function Lista($app){
    $app->validarUsuario($app, "F");

    $dados = [
      'pagina' => 'cliente/lista'
    ];

    ControllerGeral::CarregaTela($app, $dados);
  }

  public static function ListaJson($app){
    $lista_cliente = ModelCliente::Todos($app->db);

    echo(json_encode($lista_cliente));
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

  public static function Cadastrar($app) {
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

    $result = ModelCliente::Cadastrar($app->db, $cliente);

    echo(json_encode([ "success" => $result, "message" => "" ]));
  }

  public static function Deletar($app) {
    $app->validarUsuario($app, "F", true);

    $id = $_GET['id'];

    $result = ModelCliente::Excluir($app->db, $id);

    echo(json_encode([ "success" => $result, "message" => true === $result ? "Cliente excluído com sucesso" : "Erro ao excluir cliente" ]));
  }

  public static function Alteracao($app) {
    $app->validarUsuario($app, "F");

    $id = $_GET['id'];

    $dados = [
      'pagina' => 'cliente/cadastro',
      'acao' => "alterar",
      "pessoas" => ModelPessoa::Todas($app->db),
      "ufs" => $app->ufs,
      "cliente" => ModelCliente::Um($app->db, $id)
    ];
    ControllerGeral::CarregaTela($app, $dados);
  }

  public static function Alterar($app) {
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

    $result = ModelCliente::Alterar($app->db, $cliente);

    echo(json_encode([ "success" => $result, "message" => "" ]));
  }
}
?>
