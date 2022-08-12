<?php
require_once('./src/models/Pessoa.model.php');
require_once('./src/models/Funcionario.model.php');
require_once('./src/controllers/Geral.controller.php');
require_once('./src/general.php');

class ControllerFuncionario {
  // api routes
  public static function Todos($app){
    $lista_funcionario = ModelFuncionario::Todas($app->db);

    echo(json_encode($lista_funcionario));
  }

  public static function Um($app, $id){
    $funcionario = ModelFuncionario::Um($app->db, $id);

    echo(json_encode($funcionario));
  }

  public static function Cadastrar($app) {
    $app->validarUsuario($app, "F", true);

    $funcionario = [
      "id_pessoa" => (int)$_POST['id_pessoa'],
      "data_admissao" => $_POST['data_admissao'],
      "salario" => brlCurrencyToDb($_POST['salario'])
    ];

    if(null === $funcionario['id_pessoa'] || 0 === $funcionario['id_pessoa']){
      echo(json_encode([ "success" => false, "message" => "Falta selecionar a pessoa" ]));
      exit();
    }

    if("" === $funcionario['data_admissao']){
      echo(json_encode([ "success" => false, "message" => "Data de admissão não pode ser vazia" ]));
      exit();
    }

    if(date_create($funcionario['data_admissao']) > date_create('now')){
      echo(json_encode([ "success" => false, "message" => "Data de admissão não pode ser depois de hoje" ]));
      exit();
    }

    if((float)0 === $funcionario['salario']){
      echo(json_encode([ "success" => false, "message" => "Salário não pode ser 0(zero)" ]));
      exit();
    }

    $result = ModelFuncionario::Cadastrar($app->db, $funcionario);

    echo(json_encode([ "success" => $result, "message" => "" ]));
  }

  public static function Deletar($app, $id) {
    $app->validarUsuario($app, "F", true);

    $result = ModelFuncionario::Excluir($app->db, $id);

    echo(json_encode([ "success" => $result, "message" => true === $result ? "Funcionário excluído com sucesso" : "Erro ao excluir funcionário" ]));
  }

  public static function Alterar($app) {
    $app->validarUsuario($app, "F", true);

    // create put variable
    global $_PUT;
    Aplicacao::ParsePut();

    $funcionario = [
      "id" => (int)$_PUT['id'],
      "id_pessoa" => $_PUT['id_pessoa'],
      "data_admissao" => $_PUT['data_admissao'],
      "salario" => brlCurrencyToDb($_PUT['salario'])
    ];

    if(null === $funcionario['id'] || 0 === $funcionario['id']){
      echo(json_encode([ "success" => false, "message" => "Erro grave ao alterar o funcionário, atualize a página" ]));
      exit();
    }

    if(null === $funcionario['id_pessoa'] || 0 === $funcionario['id_pessoa']){
      echo(json_encode([ "success" => false, "message" => "Falta selecionar a pessoa" ]));
      exit();
    }

    if("" === $funcionario['data_admissao']){
      echo(json_encode([ "success" => false, "message" => "Data de admissão não pode ser vazia" ]));
      exit();
    }

    if(date_create($funcionario['data_admissao']) > date_create('now')){
      echo(json_encode([ "success" => false, "message" => "Data de admissão não pode ser depois de hoje" ]));
      exit();
    }

    if((float)0 === $funcionario['salario']){
      echo(json_encode([ "success" => false, "message" => "Salário não pode ser 0(zero)" ]));
      exit();
    }

    $result = ModelFuncionario::Alterar($app->db, $funcionario);

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
