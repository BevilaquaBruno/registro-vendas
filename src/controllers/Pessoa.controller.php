<?php
require_once('./src/models/Pessoa.model.php');
require_once('./src/models/Funcionario.model.php');
require_once('./src/controllers/Geral.controller.php');
require_once('./src/general.php');

require_once('./Aplicacao.php');

class ControllerPessoa {
  // api routes
  public static function Cadastrar($app) {
    $app->validarUsuario($app, "F", true);

    $pessoa = [
      "nome" => (!isset($_POST['nome'])) ? null : $_POST['nome'],
      "email" => !isset($_POST['email']) || $_POST['email'] == "" ? null : $_POST['email'],
      "telefone" => !isset($_POST['telefone']) || $_POST['telefone'] == "" ? null :  $_POST['telefone'],
      "data_nascimento" => !isset($_POST['data_nascimento']) || $_POST['data_nascimento'] == "" ? null : $_POST['data_nascimento']
    ];

    if("" === $pessoa['nome'] || null === $pessoa['nome']) {
      http_response_code(400);
      echo(json_encode([ "success" => false, "message" => "Nome é obrigatório" ]));
      exit();
    }

    if("" === $pessoa['email'] || null === $pessoa['email']){
      http_response_code(400);
      echo(json_encode([ "success" => false, "message" => "Email é obrigatório" ]));
      exit();
    }

    if(!validateEmail($pessoa['email'])){
      http_response_code(400);
      echo(json_encode([ "success" => false, "message" => "Email inválido" ]));
      exit();
    }

    if("" !== $pessoa['data_nascimento'] && null !== $pessoa['data_nascimento']){
      if(date_create($pessoa['data_nascimento']) > date_create('now')){
        http_response_code(400);
        echo(json_encode([ "success" => false, "message" => "Data de nascimento não pode ser depois de hoje" ]));
        exit();
      }
    }

    $result = ModelPessoa::Cadastrar($app->db, $pessoa);

    http_response_code(200);
    echo(json_encode([ "success" => $result, "message" => "" ]));
  }

  public static function Deletar($app, $id) {
    $app->validarUsuario($app, "F", true);

    $lista_funcionarios = ModelFuncionario::TodosIdPessoa($app->db, $id);

    if(count($lista_funcionarios) > 0){
      http_response_code(400);
      echo(json_encode([ "success" => false, "message" => "Não é possível excluir a pessoa pois já existe vínculos" ]));
      exit();
    }

    $result = ModelPessoa::Excluir($app->db, $id);

    http_response_code(200);
    echo(json_encode([ "success" => $result, "message" => "" ]));
  }

  public static function Alterar($app) {
    $app->validarUsuario($app, "F", true);

    // create put variable
    global $_PUT;
    Aplicacao::ParsePut();

    $pessoa = [
      "id" => (!isset($_PUT['id'])) ? null : $_PUT['id'],
      "nome" => (!isset($_PUT['nome'])) ? null : $_PUT['nome'],
      "email" => !isset($_PUT['email']) || $_PUT['email'] == "" ? null : $_PUT['email'],
      "telefone" => !isset($_PUT['telefone']) || $_PUT['telefone'] == "" ? null :  $_PUT['telefone'],
      "data_nascimento" => !isset($_PUT['data_nascimento']) || $_PUT['data_nascimento'] == "" ? null : $_PUT['data_nascimento']
    ];

    if(null === $pessoa['id'] || 0 === $pessoa['id']){
      http_response_code(400);
      echo(json_encode([ "success" => false, "message" => "Erro grave ao alterar a pessoa, atualize a página" ]));
      exit();
    }

    if("" === $pessoa['nome'] || null === $pessoa['nome']) {
      http_response_code(400);
      echo(json_encode([ "success" => false, "message" => "Nome é obrigatório" ]));
      exit();
    }

    if("" === $pessoa['email'] || null === $pessoa['email']){
      http_response_code(400);
      echo(json_encode([ "success" => false, "message" => "Email é obrigatório" ]));
      exit();
    }

    if(!validateEmail($pessoa['email'])){
      http_response_code(400);
      echo(json_encode([ "success" => false, "message" => "Email inválido" ]));
      exit();
    }

    if("" !== $pessoa['data_nascimento'] && null !== $pessoa['data_nascimento']){
      if(date_create($pessoa['data_nascimento']) > date_create('now')){
        http_response_code(400);
        echo(json_encode([ "success" => false, "message" => "Data de nascimento não pode ser depois de hoje" ]));
        exit();
      }
    }

    $result = ModelPessoa::Alterar($app->db, $pessoa);

    http_response_code(200);
    echo(json_encode([ "success" => $result, "message" => "" ]));

  }

  public static function Todas($app) {
    $app->validarUsuario($app, "F", true);
    $lista_pessoa = ModelPessoa::Todas($app->db);

    http_response_code(200);
    echo(json_encode($lista_pessoa));
  }

  public static function Uma($app, $id){
    $app->validarUsuario($app, "F", true);
    $pessoa = ModelPessoa::Uma($app->db, $id);

    http_response_code(200);
    echo(json_encode($pessoa));
  }

  // view routes
  public static function Lista($app){
    $app->validarUsuario($app, "F");

    $lista_pessoa = ModelPessoa::Todas($app->db);

    $dados = [
      'pagina' => 'pessoa/lista',
      'pessoas' => $lista_pessoa
    ];
    ControllerGeral::CarregaTela($app, $dados);
  }

  public static function Cadastro($app) {
    $app->validarUsuario($app, "F");

    $dados = [
      'pagina' => 'pessoa/cadastro',
      'acao' => "cadastrar",
      'pessoa' => [
        "id" => 0,
        "nome" => "",
        "email" => "",
        "telefone" => "",
        "data_nascimento" => "",
        "data_nascimento_original" => ""
      ]
    ];
    ControllerGeral::CarregaTela($app, $dados);
  }

  public static function Alteracao($app, $id) {
    $app->validarUsuario($app, "F");

    $dados = [
      'pagina' => 'pessoa/cadastro',
      'acao' => "alterar",
      'pessoa' => ModelPessoa::Uma($app->db, $id)
    ];
    ControllerGeral::CarregaTela($app, $dados);
  }
}
?>
