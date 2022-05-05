<?php
require_once('./src/models/Pessoa.model.php');
require_once('./src/models/Funcionario.model.php');

class ControllerGeral {
  public function lista($app){
    $mdlPessoa = new ModelPessoa();
    $mdlFuncionario = new ModelFuncionario();
    $dados = [
      'quantidade' => [
        'pessoas' => $mdlPessoa->quantidade($app->db),
        'funcionarios' => $mdlFuncionario->quantidade($app->db)
      ],
      'pagina' => 'inicial'
    ];
    require('./src/views/principal.php');
  }

  public function carregaTela($app, $dados){
    require('./src/views/principal.php');
  }
}
?>
