<?php
require_once('./src/controllers/Geral.controller.php');
require_once('./src/models/Venda.model.php');
require_once('./src/models/Cliente.model.php');
require_once('./src/models/Funcionario.model.php');
require_once('./src/models/Produto.model.php');

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

  public function cadastro($app) {
    $app->validarUsuario($app, "F");
    $mdlCliente = new ModelCliente();
    $mdlFuncionario = new ModelFuncionario();
    $mdlProduto = new ModelProduto();

    $dados = [
      'pagina' => 'venda/cadastro',
      'acao' => "cadastrar",
      'clientes' => $mdlCliente->todos($app->db),
      'funcionarios' => $mdlFuncionario->todas($app->db),
      'produtos' => $mdlProduto->todos($app->db),
      'venda' => [
        'id' => 0,
        'data_venda' => date('d/m/Y'),
        'data_venda_original' => date('Y-m-d'),
        'observacao' => '',
        'id_cliente' => 0,
        'id_usuario' => 0,
        'id_funcionario' => 0,
        'venda_produto' => []
      ],
    ];
    $controllerGeral = new ControllerGeral();
    $controllerGeral->carregaTela($app, $dados);
  }

  public function cadastrar($app) {
    $venda = [
      'id_cliente' => $_POST['id_cliente'],
      'id_funcionario' => $_POST['id_funcionario'],
      'data_venda' => $_POST['data_venda'],
      'observacao' => $_POST['observacao'],
      'venda_produto' => json_decode($_POST['venda_produtos'])
    ];

    if(null === $venda['id_cliente'] || '' === $venda['id_cliente']){
      echo(json_encode([ "success" => false, "message" => "Cliente é obrigatório" ]));
      exit();
    }

    if(null === $venda['id_funcionario'] || '' === $venda['id_funcionario']){
      echo(json_encode([ "success" => false, "message" => "Vendedor é obrigatório" ]));
      exit();
    }

    if(null === $venda['venda_produto'] || 0 === count($venda['venda_produto'])){
      echo(json_encode([ "success" => false, "message" => "É obrigatório ter pelo menos um produto!" ]));
      exit();
    }

    if("" == $venda['data_venda'] && null == $venda['data_venda']){
      echo(json_encode([ "success" => false, "message" => "Data da venda é obrigatória" ]));
      exit();
    }

    if(date_create($venda['data_venda']) > date_create('now')){
      echo(json_encode([ "success" => false, "message" => "Data da venda não pode ser maior que hoje" ]));
      exit();
    }

    $mdlVenda = new ModelVenda();
    $result = $mdlVenda->cadastrar($app->db, $venda);

    echo(json_encode([ "success" => $result, "message" => "" ]));
  }

  public function alteracao($app) {
    $app->validarUsuario($app, "F");
    $mdlCliente = new ModelCliente();
    $mdlFuncionario = new ModelFuncionario();
    $mdlProduto = new ModelProduto();
    $mdlVenda = new ModelVenda();

    $id = $_GET['id'];
    $venda = $mdlVenda->uma($app->db, $id);
    $venda['venda_produto'] = $mdlVenda->todasVendaProduto($app->db, $venda['id']);

    $dados = [
      'pagina' => 'venda/cadastro',
      'acao' => "alterar",
      'clientes' => $mdlCliente->todos($app->db),
      'funcionarios' => $mdlFuncionario->todas($app->db),
      'produtos' => $mdlProduto->todos($app->db),
      'venda' => $venda,
    ];

    $controllerGeral = new ControllerGeral();
    $controllerGeral->carregaTela($app, $dados);
  }

  public function alterar($app) {
    $venda = [
      'id' => $_POST['id'],
      'id_cliente' => $_POST['id_cliente'],
      'id_funcionario' => $_POST['id_funcionario'],
      'data_venda' => $_POST['data_venda'],
      'observacao' => $_POST['observacao'],
      'venda_produto' => json_decode($_POST['venda_produtos'])
    ];

    if(null === $venda['id_cliente'] || '' === $venda['id_cliente']){
      echo(json_encode([ "success" => false, "message" => "Cliente é obrigatório" ]));
      exit();
    }

    if(null === $venda['id_funcionario'] || '' === $venda['id_funcionario']){
      echo(json_encode([ "success" => false, "message" => "Vendedor é obrigatório" ]));
      exit();
    }

    if(null === $venda['venda_produto'] || 0 === count($venda['venda_produto'])){
      echo(json_encode([ "success" => false, "message" => "É obrigatório ter pelo menos um produto!" ]));
      exit();
    }

    if("" == $venda['data_venda'] && null == $venda['data_venda']){
      echo(json_encode([ "success" => false, "message" => "Data da venda é obrigatória" ]));
      exit();
    }

    if(date_create($venda['data_venda']) > date_create('now')){
      echo(json_encode([ "success" => false, "message" => "Data da venda não pode ser maior que hoje" ]));
      exit();
    }

    $mdlVenda = new ModelVenda();
    $result = $mdlVenda->alterar($app->db, $venda);

    echo(json_encode([ "success" => $result, "message" => "" ]));
  }
}
?>