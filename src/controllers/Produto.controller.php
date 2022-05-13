<?php
require_once('./src/models/Produto.model.php');
require_once('./src/models/Venda.model.php');
require_once('./src/general.php');

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

  public function cadastro($app) {
    $app->validarUsuario($app, "F");

    $dados = [
      'pagina' => 'produto/cadastro',
      'acao' => "cadastrar",
      "unidades_medida" => $app->unidades_medida,
      'produto' => [
        'id' => 0,
        'descricao' => '',
        'quantidade' => '',
        'valor_venda' => '',
        'unidade_medida' => '',
        'valor_compra' => ''
      ]
    ];
    $controllerGeral = new ControllerGeral();
    $controllerGeral->carregaTela($app, $dados);
  }

  public function cadastrar($app) {
    $app->validarUsuario($app, "F", true);

    $produto = [
      "descricao" => $_POST['descricao'],
      "quantidade" => brlCurrencyToDb($_POST['quantidade']),
      "unidade_medida" => $_POST['unidade_medida'],
      "valor_venda" => brlCurrencyToDb($_POST['valor_venda']),
      "valor_compra" => brlCurrencyToDb($_POST['valor_compra'])
    ];

    if("" === $produto['descricao']){
      echo(json_encode([ "success" => false, "message" => "Descrição é obrigatória" ]));
      exit();
    }

    if(false === array_search($produto['unidade_medida'], $app->unidades_medida)){
      echo(json_encode([ "success" => false, "message" => "Falta selecionar a unidade de medida" ]));
      exit();
    }

    $mdlProduto = new ModelProduto();
    $result = $mdlProduto->cadastrar($app->db, $produto);

    echo(json_encode([ "success" => $result, "message" => "" ]));
  }

  public function alteracao($app) {
    $app->validarUsuario($app, "F");
    $mdlProduto = new ModelProduto();
    $id = $_GET['id'];

    $dados = [
      'pagina' => 'produto/cadastro',
      'acao' => "alterar",
      "unidades_medida" => $app->unidades_medida,
      'produto' => $mdlProduto->um($app->db, $id)
    ];
    $controllerGeral = new ControllerGeral();
    $controllerGeral->carregaTela($app, $dados);
  }

  public function alterar($app) {
    $app->validarUsuario($app, "F", true);

    $produto = [
      "id" => $_POST['id'],
      "descricao" => $_POST['descricao'],
      "quantidade" => brlCurrencyToDb($_POST['quantidade']),
      "unidade_medida" => $_POST['unidade_medida'],
      "valor_venda" => brlCurrencyToDb($_POST['valor_venda']),
      "valor_compra" => brlCurrencyToDb($_POST['valor_compra'])
    ];

    if(null === $produto['id'] || 0 === $produto['id']){
      echo(json_encode([ "success" => false, "message" => "Erro grave ao alterar o produto, atualize a página" ]));
      exit();
    }

    if("" === $produto['descricao']){
      echo(json_encode([ "success" => false, "message" => "Descrição é obrigatória" ]));
      exit();
    }

    if(false === array_search($produto['unidade_medida'], $app->unidades_medida)){
      echo(json_encode([ "success" => false, "message" => "Falta selecionar a unidade de medida" ]));
      exit();
    }

    $mdlProduto = new ModelProduto();
    $result = $mdlProduto->alterar($app->db, $produto);

    echo(json_encode([ "success" => $result, "message" => "" ]));
  }

}
?>
