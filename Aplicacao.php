<?php
class Aplicacao {
  const db = null;
  const tipos_usuarios = ["A", "F"];

  function __construct() {
    $this->createConnetion();
  }

  public function createConnetion() {
    $this->db = new PDO('mysql:host=localhost;dbname=registro_vendas', "bevilaqua", "Vaporwave05");
  }
}

?>