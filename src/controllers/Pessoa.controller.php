<?php
require_once('./src/models/Pessoa.model.php');
require_once('./src/models/Funcionario.model.php');
require_once('./src/controllers/Geral.controller.php');
require_once('./src/general.php');

class ControllerPessoa {
  // api routes
  public static function Cadastrar($app) {
    $app->validarUsuario($app, "F", true);

    $pessoa = [
      "nome" => $_POST['nome'],
      "email" => $_POST['email'] == "" ? null : $_POST['email'],
      "telefone" => $_POST['telefone'] == "" ? null :  $_POST['telefone'],
      "data_nascimento" => $_POST['data_nascimento'] == "" ? null : $_POST['data_nascimento']
    ];

    if("" === $pessoa['nome'] || null === $pessoa['nome']) {
      echo(json_encode([ "success" => false, "message" => "Nome é obrigatório" ]));
      exit();
    }

    if("" === $pessoa['email'] || null === $pessoa['email']){
      echo(json_encode([ "success" => false, "message" => "Email é obrigatório" ]));
      exit();
    }

    if(!validateEmail($pessoa['email'])){
      echo(json_encode([ "success" => false, "message" => "Email inválido" ]));
      exit();
    }

    if("" !== $pessoa['data_nascimento'] && null !== $pessoa['data_nascimento']){
      if(date_create($pessoa['data_nascimento']) > date_create('now')){
        echo(json_encode([ "success" => false, "message" => "Data de nascimento não pode ser depois de hoje" ]));
        exit();
      }
    }

    $result = ModelPessoa::Cadastrar($app->db, $pessoa);

    echo(json_encode([ "success" => $result, "message" => "" ]));
  }

  public static function Deletar($app, $id) {
    $app->validarUsuario($app, "F", true);

    $lista_funcionarios = ModelFuncionario::TodosIdPessoa($app->db, $id);

    if(count($lista_funcionarios) > 0){
      echo(json_encode([ "success" => false, "message" => "Não é possível excluir a pessoa pois já existe vínculos" ]));
      exit();
    }

    $result = ModelPessoa::Excluir($app->db, $id);

    echo(json_encode([ "success" => $result, "message" => "" ]));
  }

  public static function Alterar($app) {

    $app->validarUsuario($app, "F", true);

    $pessoa = [
      "id" => $_POST['id'],
      "nome" => $_POST['nome'],
      "email" => $_POST['email'] == "" ? null : $_POST['email'],
      "telefone" => $_POST['telefone'] == "" ? null :  $_POST['telefone'],
      "data_nascimento" => $_POST['data_nascimento'] == "" ? null : $_POST['data_nascimento']
    ];

    if(null === $pessoa['id'] || 0 === $pessoa['id']){
      echo(json_encode([ "success" => false, "message" => "Erro grave ao alterar a pessoa, atualize a página" ]));
      exit();
    }

    if("" === $pessoa['nome'] || null === $pessoa['nome']) {
      echo(json_encode([ "success" => false, "message" => "Nome é obrigatório" ]));
      exit();
    }

    if("" === $pessoa['email'] || null === $pessoa['email']){
      echo(json_encode([ "success" => false, "message" => "Email é obrigatório" ]));
      exit();
    }

    if(!validateEmail($pessoa['email'])){
      echo(json_encode([ "success" => false, "message" => "Email inválido" ]));
      exit();
    }

    if("" !== $pessoa['data_nascimento'] && null !== $pessoa['data_nascimento']){
      if(date_create($pessoa['data_nascimento']) > date_create('now')){
        echo(json_encode([ "success" => false, "message" => "Data de nascimento não pode ser depois de hoje" ]));
        exit();
      }
    }

    $result = ModelPessoa::Alterar($app->db, $pessoa);

    echo(json_encode([ "success" => $result, "message" => "" ]));

  }

  public static function Todos($app) {
    $lista_pessoa = ModelPessoa::Todas($app->db);

    echo(json_encode($lista_pessoa));
  }

  public static function Um($app, $id){
    $pessoa = ModelPessoa::Uma($app->db, $id);

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
