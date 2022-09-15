<?php
require_once('./src/models/Pessoa.model.php');
require_once('./src/models/Funcionario.model.php');
require_once('./src/controllers/Geral.controller.php');
require_once('./src/general.php');

class ControllerFuncionario {
  // api routes
  public static function Todos($app){
    $app->validarUsuario($app, "F", true);
    $lista_funcionario = ModelFuncionario::Todas($app->db);

    http_response_code(200);
    echo(json_encode($lista_funcionario));
  }

  public static function Um($app, $id){
    $app->validarUsuario($app, "F", true);
    $funcionario = ModelFuncionario::Um($app->db, $id);

    http_response_code(200);
    echo(json_encode($funcionario));
  }

  public static function Cadastrar($app) {
    $app->validarUsuario($app, "F", true);

    $funcionario = [
      "id_pessoa" => (isset($_POST['id_pessoa']) ? (int)$_POST['id_pessoa'] : NULL),
      "data_admissao" => (!isset($_POST['data_admissao']) || '' === $_POST['data_admissao']) ? null : $_POST['data_admissao'],
      "salario" => (!isset($_POST['salario']) || '' === $_POST['salario']) ? null : brlCurrencyToDb($_POST['salario'])
    ];

    if(null === $funcionario['id_pessoa'] || 0 === $funcionario['id_pessoa']){
      http_response_code(400);
      echo(json_encode([ "success" => false, "message" => "Falta selecionar a pessoa" ]));
      exit();
    }

    if("" === $funcionario['data_admissao']){
      http_response_code(400);
      echo(json_encode([ "success" => false, "message" => "Data de admissão não pode ser vazia" ]));
      exit();
    }

    if(date_create($funcionario['data_admissao']) > date_create('now')){
      http_response_code(400);
      echo(json_encode([ "success" => false, "message" => "Data de admissão não pode ser depois de hoje" ]));
      exit();
    }

    if((float)0 === $funcionario['salario']){
      http_response_code(400);
      echo(json_encode([ "success" => false, "message" => "Salário não pode ser 0(zero)" ]));
      exit();
    }

    $result = ModelFuncionario::Cadastrar($app->db, $funcionario);

    http_response_code(200);
    echo(json_encode([ "success" => $result, "message" => "" ]));
  }

  public static function Deletar($app, $id) {
    $app->validarUsuario($app, "F", true);

    $result = ModelFuncionario::Excluir($app->db, $id);

    http_response_code(200);
    echo(json_encode([ "success" => $result, "message" => true === $result ? "Funcionário excluído com sucesso" : "Erro ao excluir funcionário" ]));
  }

  public static function Alterar($app) {
    $app->validarUsuario($app, "F", true);

    // create put variable
    global $_PUT;
    Aplicacao::ParsePut();

    $funcionario = [
      "id" => (int)$_PUT['id'],
      "id_pessoa" => (isset($_PUT['id_pessoa']) ? (int)$_PUT['id_pessoa'] : NULL),
      "data_admissao" => (!isset($_PUT['data_admissao']) || '' === $_PUT['data_admissao']) ? null : $_PUT['data_admissao'],
      "salario" => (!isset($_PUT['salario']) || '' === $_PUT['salario']) ? null : brlCurrencyToDb($_PUT['salario'])
    ];

    if(null === $funcionario['id'] || 0 === $funcionario['id']){
      http_response_code(400);
      echo(json_encode([ "success" => false, "message" => "Erro grave ao alterar o funcionário, atualize a página" ]));
      exit();
    }

    if(null === $funcionario['id_pessoa'] || 0 === $funcionario['id_pessoa']){
      http_response_code(400);
      echo(json_encode([ "success" => false, "message" => "Falta selecionar a pessoa" ]));
      exit();
    }

    if("" === $funcionario['data_admissao']){
      http_response_code(400);
      echo(json_encode([ "success" => false, "message" => "Data de admissão não pode ser vazia" ]));
      exit();
    }

    if(date_create($funcionario['data_admissao']) > date_create('now')){
      http_response_code(400);
      echo(json_encode([ "success" => false, "message" => "Data de admissão não pode ser depois de hoje" ]));
      exit();
    }

    if((float)0 === $funcionario['salario']){
      http_response_code(400);
      echo(json_encode([ "success" => false, "message" => "Salário não pode ser 0(zero)" ]));
      exit();
    }

    $result = ModelFuncionario::Alterar($app->db, $funcionario);

    http_response_code(200);
    echo(json_encode([ "success" => $result, "message" => "" ]));
  }

  // view routes
  public static function Lista($app){
    $app->validarUsuario($app, "F");

    $dados = [
      'pagina' => 'funcionario/lista'
    ];

    ControllerGeral::CarregaTela($app, $dados);
  }

  public static function Cadastro($app) {
    $app->validarUsuario($app, "F");

    $dados = [
      'pagina' => 'funcionario/cadastro',
      'acao' => "cadastrar",
      "pessoas" => ModelPessoa::Todas($app->db),
      'funcionario' => [
        'id' => 0,
        'id_pessoa' => '',
        'data_admissao' => '',
        'data_admissao_original' => '',
        'salario' => ''
      ]
    ];
    ControllerGeral::CarregaTela($app, $dados);
  }

  public static function Alteracao($app, $id) {
    $app->validarUsuario($app, "F");

    $dados = [
      'pagina' => 'funcionario/cadastro',
      'acao' => "alterar",
      "pessoas" => ModelPessoa::Todas($app->db),
      "funcionario" => ModelFuncionario::Um($app->db, $id)
    ];
    ControllerGeral::CarregaTela($app, $dados);
  }
}
?>
