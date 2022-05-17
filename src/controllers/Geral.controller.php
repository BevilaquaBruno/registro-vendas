<?php
require_once('./src/models/Pessoa.model.php');
require_once('./src/models/Funcionario.model.php');
require_once('./src/models/Usuario.model.php');
require_once('./src/models/Produto.model.php');
require_once('./src/models/Cliente.model.php');
require_once('./src/models/Venda.model.php');

class ControllerGeral {
  public function lista($app){
    $mdlPessoa = new ModelPessoa();
    $mdlFuncionario = new ModelFuncionario();
    $mdlUsuario = new ModelUsuario();
    $mdlProduto = new ModelProduto();
    $mdlCliente = new ModelCliente();
    $mdlVenda = new ModelVenda();

    $data_media_salarial = [];
    $media_salarial_valor = 0;
    $media_salarial = $mdlFuncionario->mediaSalarioTodos($app->db);

    for ($i=0; $i < count($media_salarial); $i++) {
      array_push($data_media_salarial, $media_salarial[$i]['salario']);
      if (0 === $i) {
        $media_salarial_valor = $media_salarial[$i]['media'];
      }
    }
    $dados = [
      'quantidade' => [
        'pessoas' => $mdlPessoa->quantidade($app->db),
        'funcionarios' => $mdlFuncionario->quantidade($app->db),
        'usuarios' => $mdlUsuario->quantidade($app->db),
        'produtos' => $mdlUsuario->quantidade($app->db),
        'clientes' => $mdlCliente->quantidade($app->db)
      ],
      'media_salarial' => [
        'data' => $data_media_salarial,
        'media' => $media_salarial_valor
      ],
      'vendas' => $mdlVenda->totalVendas($app->db),
      'pagina' => 'inicial'
    ];
    require('./src/views/principal.php');
  }

  public function carregaTela($app, $dados){
    require('./src/views/principal.php');
  }
}
?>
