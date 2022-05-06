<?php
class Aplicacao {
  const db = null;

  function __construct() {
    $this->createConnetion();
  }

  public function createConnetion() {
    $this->db = new PDO('mysql:host=localhost;dbname=bd_funcionarios', "bevilaqua", "Vaporwave05");
  }
}

?>