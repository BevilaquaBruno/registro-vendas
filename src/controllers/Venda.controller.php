<?php
require_once('./src/controllers/Geral.controller.php');
require_once('./src/models/Venda.model.php');
require_once('./src/models/Cliente.model.php');
require_once('./src/models/Funcionario.model.php');
require_once('./src/models/Produto.model.php');

class ControllerVenda {
  public static function Lista($app){
    $app->validarUsuario($app, "F");

    $dados = [
      'pagina' => 'venda/lista'
    ];

    ControllerGeral::CarregaTela($app, $dados);
  }

  public static function ListaJson($app){
    $lista = ModelVenda::Todas($app->db);

    echo(json_encode($lista));
  }

  public static function Deletar($app) {
    $app->validarUsuario($app, "F", true);

    $id = $_GET['id'];

    $result = ModelVenda::Excluir($app->db, $id);

    echo(json_encode([ "success" => $result, "message" => "" ]));
  }

  public static function Cadastro($app) {
    $app->validarUsuario($app, "F");

    $dados = [
      'pagina' => 'venda/cadastro',
      'acao' => "cadastrar",
      'clientes' => ModelCliente::Todos($app->db),
      'funcionarios' => ModelFuncionario::Todas($app->db),
      'produtos' => ModelProduto::Todos($app->db),
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
    ControllerGeral::CarregaTela($app, $dados);
  }

  public static function Cadastrar($app) {
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

    $result = ModelVenda::Cadastrar($app->db, $venda);

    echo(json_encode([ "success" => $result, "message" => "" ]));
  }

  public static function Alteracao($app) {
    $app->validarUsuario($app, "F");

    $id = $_GET['id'];
    $venda = ModelVenda::Uma($app->db, $id);
    $venda['venda_produto'] = ModelVenda::TodasVendaProduto($app->db, $venda['id']);

    $dados = [
      'pagina' => 'venda/cadastro',
      'acao' => "alterar",
      'clientes' => ModelCliente::Todos($app->db),
      'funcionarios' => ModelFuncionario::Todas($app->db),
      'produtos' => ModelProduto::Todos($app->db),
      'venda' => $venda,
    ];

    ControllerGeral::CarregaTela($app, $dados);
  }

  public static function Alterar($app) {
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

    $result = ModelVenda::Alterar($app->db, $venda);

    echo(json_encode([ "success" => $result, "message" => "" ]));
  }
}
?>