<?php
require_once('./src/models/Pessoa.model.php');
require_once('./src/models/Funcionario.model.php');
require_once('./src/models/Usuario.model.php');
require_once('./src/models/Produto.model.php');
require_once('./src/models/Cliente.model.php');
require_once('./src/models/Venda.model.php');

class ControllerGeral {
  public static function Lista($app){
    $data_media_salarial = [];
    $media_salarial_valor = 0;
    $media_salarial = ModelFuncionario::MediaSalarioTodos($app->db);

    for ($i=0; $i < count($media_salarial); $i++) {
      array_push($data_media_salarial, $media_salarial[$i]['salario']);
      if (0 === $i) {
        $media_salarial_valor = $media_salarial[$i]['media'];
      }
    }
    $dados = [
      'quantidade' => [
        'pessoas' => ModelPessoa::Quantidade($app->db),
        'funcionarios' => ModelFuncionario::Quantidade($app->db),
        'usuarios' => ModelUsuario::Quantidade($app->db),
        'produtos' => ModelProduto::Quantidade($app->db),
        'clientes' => ModelCliente::Quantidade($app->db)
      ],
      'media_salarial' => [
        'data' => $data_media_salarial,
        'media' => $media_salarial_valor
      ],
      'vendas' => ModelVenda::TotalVendas($app->db),
      'pagina' => 'inicial'
    ];
    require('./src/views/principal.php');
  }

  public static function CarregaTela($app, $dados){
    require('./src/views/principal.php');
  }
}
?>
