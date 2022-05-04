<?php
require_once('./models/Pessoa.model.php');
require_once('./models/Funcionario.model.php');
require_once('./controllers/Geral.controller.php');

class ControllerFuncionario {
  public function lista($app){
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
    $mdlPessoa = new ModelPessoa();
    $dados = [
      'pagina' => 'funcionario/cadastro',
      'acao' => "cadastrar",
      "pessoas" => $mdlPessoa->todas($app->db),
      'funcionario' => [
        'id' => 0,
        'id_pessoa' => '',
        'data_admissao' => '',
        'salario' => ''
      ]
    ];
    $controllerGeral = new ControllerGeral();
    $controllerGeral->carregaTela($app, $dados);
  }

  public function cadastrar($app) {
    $funcionario = [
      "id_pessoa" => $_POST['id_pessoa'],
      "data_admissao" => $_POST['data_admissao'],
      "salario" => $_POST['salario']
    ];

    $mdlFuncionario = new ModelFuncionario();
    $result = $mdlFuncionario->cadastrar($app->db, $funcionario);

    echo(json_encode([ "success" => $result, "message" => "" ]));
  }

  public function deletar($app) {
    $id = $_GET['id'];

    $mdlFuncionario = new ModelFuncionario();
    $result = $mdlFuncionario->excluir($app->db, $id);

    echo(json_encode([ "success" => $result, "message" => true === $result ? "Funcionário excluído com sucesso" : "Erro ao excluir funcionário" ]));
  }

  public function alteracao($app) {
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
    $funcionario = [
      "id" => $_POST['id'],
      "id_pessoa" => $_POST['id_pessoa'],
      "data_admissao" => $_POST['data_admissao'],
      "salario" => $_POST['salario']
    ];

    $mdlFuncionario = new ModelFuncionario();
    $mdlFuncionario->alterar($app->db, $funcionario);

    //redirecionamento em php
    header('Location: index.php?m=funcionario&a=lista');
  }
}
?>
