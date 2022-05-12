<?php
require_once('./src/models/Produto.model.php');

class ControllerProduto {
  public function lista($app){
    $app->validarUsuario($app, "F");

    $dados = [
      'pagina' => 'produto/lista'
    ];

    $controllerGeral = new ControllerGeral();
    $controllerGeral->carregaTela($app, $dados);
  }

  public function listajson($app){
    $mdlProduto = new ModelProduto();
    $lista_produtos = $mdlProduto->todos($app->db);

    echo(json_encode($lista_produtos));
  }

  public function deletar($app) {
    $app->validarUsuario($app, "F", true);

    $id = $_GET['id'];

    $mdlVenda = new ModelVenda();
    $lista_vendas = $mdlVenda->todasProduto($app->db, $id);

    if(count($lista_vendas) > 0){
      echo(json_encode([ "success" => false, "message" => "Não é possível excluir o produto pois já existe vendas efetuadas" ]));
      exit();
    }

    $mdlProduto = new ModelProduto();
    $result = $mdlProduto->excluir($app->db, $id);

    echo(json_encode([ "success" => $result, "message" => "" ]));
  }

}
?>
