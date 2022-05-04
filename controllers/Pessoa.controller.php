<?php
require_once('./models/Pessoa.model.php');
require_once('./controllers/Geral.controller.php');

class ControllerPessoa {
  public function lista($app){
    $mdlPessoa = new ModelPessoa();
    $lista_pessoa = $mdlPessoa->todas($app->db);

    $dados = [
      'pagina' => 'pessoa/lista',
      'pessoas' => $lista_pessoa
    ];
    $controllerGeral = new ControllerGeral();
    $controllerGeral->carregaTela($app, $dados);
  }

  public function cadastro($app) {
    $dados = [
      'pagina' => 'pessoa/cadastro',
      'acao' => "cadastrar",
      'pessoa' => [
        "id" => 0,
        "nome" => "",
        "email" => "",
        "telefone" => "",
        "data_nascimento" => ""
      ]
    ];
    $controllerGeral = new ControllerGeral();
    $controllerGeral->carregaTela($app, $dados);
  }

  public function cadastrar($app) {
    $pessoa = [
      "nome" => $_POST['nome'],
      "email" => $_POST['email'] == "" ? null : $_POST['email'],
      "telefone" => $_POST['telefone'] == "" ? null :  $_POST['telefone'],
      "data_nascimento" => $_POST['data_nascimento'] == "" ? null : $_POST['data_nascimento']
    ];

    $mdlPessoa = new ModelPessoa();
    $mdlPessoa->cadastrar($app->db, $pessoa);

    //redirecionamento em php
    header('Location: index.php?m=pessoa&a=lista');
  }

  public function deletar($app) {
    $id = $_GET['id'];

    $mdlPessoa = new ModelPessoa();
    $mdlPessoa->excluir($app->db, $id);

    //redirecionamento em php
    header('Location: index.php?m=pessoa&a=lista');
  }

  public function alteracao($app) {
    $mdlPessoa = new ModelPessoa();
    $id = $_GET['id'];

    $dados = [
      'pagina' => 'pessoa/cadastro',
      'acao' => "alterar",
      'pessoa' => $mdlPessoa->uma($app->db, $id)
    ];
    $controllerGeral = new ControllerGeral();
    $controllerGeral->carregaTela($app, $dados);
  }

  public function alterar($app) {
    $pessoa = [
      "id" => $_POST['id'],
      "nome" => $_POST['nome'],
      "email" => $_POST['email'] == "" ? null : $_POST['email'],
      "telefone" => $_POST['telefone'] == "" ? null :  $_POST['telefone'],
      "data_nascimento" => $_POST['data_nascimento'] == "" ? null : $_POST['data_nascimento']
    ];

    $mdlPessoa = new ModelPessoa();
    $mdlPessoa->alterar($app->db, $pessoa);

    //redirecionamento em php
    header('Location: index.php?m=pessoa&a=lista');
  }
}
?>
