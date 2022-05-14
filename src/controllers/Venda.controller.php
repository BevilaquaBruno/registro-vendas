<?php
require_once('./src/controllers/Geral.controller.php');
require_once('./src/models/Venda.model.php');

class ControllerVenda {
  public function lista($app){
    $app->validarUsuario($app, "F");

    $dados = [
      'pagina' => 'venda/lista'
    ];

    $controllerGeral = new ControllerGeral();
    $controllerGeral->carregaTela($app, $dados);
  }

  public function listajson($app){
    $mdlVenda = new ModelVenda();
    $lista = $mdlVenda->todas($app->db);

    echo(json_encode($lista));
  }

  public function deletar($app) {
    $app->validarUsuario($app, "F", true);

    $id = $_GET['id'];

    $mdlVenda = new ModelVenda();
    $result = $mdlVenda->excluir($app->db, $id);

    echo(json_encode([ "success" => $result, "message" => "" ]));
  }
}
?>