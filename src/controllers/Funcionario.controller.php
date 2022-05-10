<?php
require_once('./src/models/Pessoa.model.php');
require_once('./src/models/Funcionario.model.php');
require_once('./src/controllers/Geral.controller.php');
require_once('./src/general.php');

class ControllerFuncionario {
  public function lista($app){
    $app->validarUsuario($app, "F");

    $dados = [
      'pagina' => 'funcionario/lista'
    ];

    $controllerGeral = new ControllerGeral();
    $controllerGeral->carregaTela($app, $dados);
  }

  public function listajson($app){
    $mdlFuncionario = new ModelFuncionario();
    $lista_funcionario = $mdlFuncionario->todas($app->db);

    echo(json_encode($lista_funcionario));
  }

  public function cadastro($app) {
    $app->validarUsuario($app, "F");

    $mdlPessoa = new ModelPessoa();
    $dados = [
      'pagina' => 'funcionario/cadastro',
      'acao' => "cadastrar",
      "pessoas" => $mdlPessoa->todas($app->db),
      'funcionario' => [
        'id' => 0,
        'id_pessoa' => '',
        'data_admissao' => '',
        'data_admissao_original' => '',
        'salario' => ''
      ]
    ];
    $controllerGeral = new ControllerGeral();
    $controllerGeral->carregaTela($app, $dados);
  }

  public function cadastrar($app) {
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

    $mdlFuncionario = new ModelFuncionario();
    $result = $mdlFuncionario->cadastrar($app->db, $funcionario);

    echo(json_encode([ "success" => $result, "message" => "" ]));
  }

  public function deletar($app) {
    $app->validarUsuario($app, "F", true);

    $id = $_GET['id'];

    $mdlFuncionario = new ModelFuncionario();
    $result = $mdlFuncionario->excluir($app->db, $id);

    echo(json_encode([ "success" => $result, "message" => true === $result ? "Funcionário excluído com sucesso" : "Erro ao excluir funcionário" ]));
  }

  public function alteracao($app) {
    $app->validarUsuario($app, "F");

    $mdlPessoa = new ModelPessoa();
    $mdlFuncionario = new ModelFuncionario();
    $id = $_GET['id'];

    $dados = [
      'pagina' => 'funcionario/cadastro',
      'acao' => "alterar",
      "pessoas" => $mdlPessoa->todas($app->db),
      "funcionario" => $mdlFuncionario->um($app->db, $id)
    ];
    $controllerGeral = new ControllerGeral();
    $controllerGeral->carregaTela($app, $dados);
  }

  public function alterar($app) {
    $app->validarUsuario($app, "F", true);

    $funcionario = [
      "id" => (int)$_POST['id'],
      "id_pessoa" => $_POST['id_pessoa'],
      "data_admissao" => $_POST['data_admissao'],
      "salario" => brlCurrencyToDb($_POST['salario'])
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

    $mdlFuncionario = new ModelFuncionario();
    $result = $mdlFuncionario->alterar($app->db, $funcionario);

    echo(json_encode([ "success" => $result, "message" => "" ]));
  }
}
?>
