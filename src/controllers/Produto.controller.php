<?php
require_once('./src/models/Produto.model.php');
require_once('./src/models/Venda.model.php');
require_once('./src/general.php');

class ControllerProduto
{
  // api routes
  public static function Todos($app) {
    $app->validarUsuario($app, "F", true);
    $lista_produtos = ModelProduto::Todos($app->db);

    http_response_code(200);
    echo (json_encode($lista_produtos));
  }

  public static function Um($app, $id){
    $app->validarUsuario($app, "F", true);
    $produto = ModelProduto::Um($app->db, $id);

    http_response_code(200);
    echo(json_encode($produto));
  }

  public static function Deletar($app, $id) {
    $app->validarUsuario($app, "F", true);

    $lista_vendas = ModelVenda::TodasProduto($app->db, $id);

    if (count($lista_vendas) > 0) {
      http_response_code(400);
      echo (json_encode(["success" => false, "message" => "Não é possível excluir o produto pois já existe vendas efetuadas"]));
      exit();
    }

    $result = ModelProduto::Excluir($app->db, $id);

    http_response_code(200);
    echo (json_encode(["success" => $result, "message" => ""]));
  }

  public static function Cadastrar($app) {
    $app->validarUsuario($app, "F", true);

    $produto = [
      "descricao" => (!isset($_POST['descricao']) || $_POST['descricao'] === "") ? null : $_POST['descricao'],
      "quantidade" => (!isset($_POST['quantidade']) ) ? null : brlCurrencyToDb($_POST['quantidade']),
      "unidade_medida" => (!isset($_POST['unidade_medida'])) ? null : $_POST['unidade_medida'],
      "valor_venda" => (!isset($_POST['valor_venda'])) ? null : brlCurrencyToDb($_POST['valor_venda']),
      "valor_compra" => (!isset($_POST['valor_compra'])) ? null : brlCurrencyToDb($_POST['valor_compra'])
    ];

    if ("" === $produto['descricao']) {
      http_response_code(400);
      echo (json_encode(["success" => false, "message" => "Descrição é obrigatória"]));
      exit();
    }

    if (false === array_search($produto['unidade_medida'], $app->unidades_medida)) {
      http_response_code(400);
      echo (json_encode(["success" => false, "message" => "Falta selecionar a unidade de medida"]));
      exit();
    }

    $result = ModelProduto::Cadastrar($app->db, $produto);

    http_response_code(200);
    echo (json_encode(["success" => $result, "message" => ""]));
  }

  public static function Alterar($app) {
    $app->validarUsuario($app, "F", true);

    // create put variable
    global $_PUT;
    Aplicacao::ParsePut();

    $produto = [
      "id" => (!isset($_PUT['id'])) ? null : $_PUT['id'],
      "descricao" => (!isset($_PUT['descricao']) || $_PUT['descricao'] === "") ? null : $_PUT['descricao'],
      "quantidade" => (!isset($_PUT['quantidade']) ) ? null : brlCurrencyToDb($_PUT['quantidade']),
      "unidade_medida" => (!isset($_PUT['unidade_medida'])) ? null : $_PUT['unidade_medida'],
      "valor_venda" => (!isset($_PUT['valor_venda'])) ? null : brlCurrencyToDb($_PUT['valor_venda']),
      "valor_compra" => (!isset($_PUT['valor_compra'])) ? null : brlCurrencyToDb($_PUT['valor_compra'])
    ];

    if (null === $produto['id'] || 0 === $produto['id']) {
      http_response_code(400);
      echo (json_encode(["success" => false, "message" => "Erro grave ao alterar o produto, atualize a página"]));
      exit();
    }

    if ("" === $produto['descricao']) {
      http_response_code(400);
      echo (json_encode(["success" => false, "message" => "Descrição é obrigatória"]));
      exit();
    }

    if (false === array_search($produto['unidade_medida'], $app->unidades_medida)) {
      http_response_code(400);
      echo (json_encode(["success" => false, "message" => "Falta selecionar a unidade de medida"]));
      exit();
    }

    $result = ModelProduto::Alterar($app->db, $produto);

    http_response_code(200);
    echo (json_encode(["success" => $result, "message" => ""]));
  }

  // view routes

  public static function Alteracao($app, $id) {
    $app->validarUsuario($app, "F");

    $dados = [
      'pagina' => 'produto/cadastro',
      'acao' => "alterar",
      "unidades_medida" => $app->unidades_medida,
      'produto' => ModelProduto::Um($app->db, $id)
    ];
    ControllerGeral::CarregaTela($app, $dados);
  }

  public static function Lista($app) {
    $app->validarUsuario($app, "F");

    $dados = [
      'pagina' => 'produto/lista'
    ];

    ControllerGeral::CarregaTela($app, $dados);
  }

  public static function Cadastro($app) {
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
    ControllerGeral::CarregaTela($app, $dados);
  }
}
