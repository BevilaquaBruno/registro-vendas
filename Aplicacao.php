<?php
require_once('./src/controllers/Geral.controller.php');
class Aplicacao {
  const db = null;
  public $tipos_usuarios = ["A", "F"];
  public $unidades_medida = ["UN", "KG", "CX", "L"];

  function __construct() {
    $this->createConnetion();
  }

  public function validarUsuario($app, String $needed = "", $json = false){
    if(!$this->permissoesUsuarios($needed)){
      if($json){
        echo(json_encode(['success' => false, 'message' => 'Não autorizado']));
      }else{
        $controllerGeral = new ControllerGeral();
        $controllerGeral->carregaTela($app, [
          'pagina' => 'geral/401'
        ]);
      }
      exit();
    }
  }

  public function permissoesUsuarios(String $needed){
    $result = true;
    if(!isset($_SESSION['islogged']) || false === $_SESSION['islogged']){
      $result = false;
    }else if("A" !== $_SESSION['tipo']){
      if($needed !== $_SESSION['tipo'])
        $result = false;
    }

    return $result;
  }

  public function createConnetion() {
    $this->db = new PDO('mysql:host=localhost;dbname=registro_vendas', "bevilaqua", "Vaporwave05");
  }
}

?>