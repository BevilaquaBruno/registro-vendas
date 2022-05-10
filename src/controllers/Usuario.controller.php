<?php
require_once('./src/controllers/Geral.controller.php');

class ControllerUsuario {
  public function lista($app){
    $app->validarUsuario($app, "A");

    $dados = [
      'pagina' => 'usuario/lista'
    ];

    $controllerGeral = new ControllerGeral();
    $controllerGeral->carregaTela($app, $dados);
  }
}
?>
