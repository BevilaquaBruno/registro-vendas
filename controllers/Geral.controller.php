<?php
class ControllerGeral {
  public function lista($app){
    require_once('./models/Pessoa.model.php');
    require_once('./models/Funcionario.model.php');
    $mdlPessoa = new ModelPessoa();
    $mdlFuncionario = new ModelFuncionario();
    $dados = [
      'quantidade' => [
        'pessoas' => $mdlPessoa->quantidade($app->db),
        'funcionarios' => $mdlFuncionario->quantidade($app->db)
      ],
      'pagina' => 'inicial'
    ];
    require('./pages/principal.php');
  }

  public function carregaTela($app, $dados){
    require('./pages/principal.php');
  }
}
?>
