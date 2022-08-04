<?php
require_once('./src/controllers/Geral.controller.php');
class Aplicacao {
  const db = null;
  public $tipos_usuarios = ["A", "F"];
  public $unidades_medida = ["UN", "KG", "CX", "L"];
  public $ufs = [
    'AC','AL','AP','AM','BA','CE','DF',
    'ES','GO','MA','MT','MS','MG','PA',
    'PB','PR','PE','PI','RJ','RN','RS',
    'RO','RR','SC','SP','SE','TO'
  ];

  function __construct() {
    $this->createConnetion();
  }

  public function validarUsuario($app, String $needed = "", $json = false){
    if(!$this->permissoesUsuarios($needed)){
      if($json){
        echo(json_encode(['success' => false, 'message' => 'Não autorizado']));
      }else{
        ControllerGeral::CarregaTela($app, [
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