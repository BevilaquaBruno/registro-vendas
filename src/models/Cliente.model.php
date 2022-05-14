<?php

class ModelCliente {
  public function todos(PDO $conexao){
    $clientes = 0;
    try {
      $sm_query = $conexao->prepare("SELECT c.id, c.endereco, c.uf, c.cidade, c.pais, c.bairro, c.id_pessoa,
        p.nome, p.email, p.telefone, p.data_nascimento, (select count(v.id) from venda v where v.id_cliente = c.id) as qtd_vendas
        FROM cliente c
        LEFT JOIN pessoa p ON p.id = c.id_pessoa");

      if($sm_query->execute()){
        $clientes = $sm_query->fetchall(PDO::FETCH_ASSOC);
      }

    } catch (\Throwable $th) {
      $clientes = 0;
    }

    return $clientes;
  }

  public function cadastrar(PDO $conexao, $cliente){
    $success = false;
    try {
      $sm_query = $conexao->prepare("INSERT INTO cliente (endereco, uf, cidade, pais, bairro, id_pessoa)
        VALUES (:endereco, :uf, :cidade, :pais, :bairro, :id_pessoa);");

      $sm_query->bindParam(":endereco", $cliente['endereco']);
      $sm_query->bindParam(":uf", $cliente['uf']);
      $sm_query->bindParam(":cidade", $cliente['cidade']);
      $sm_query->bindParam(":pais", $cliente['pais']);
      $sm_query->bindParam(":bairro", $cliente['bairro']);
      $sm_query->bindParam(":id_pessoa", $cliente['id_pessoa']);

      if($sm_query->execute()){
        $success = true;
      }
    } catch (\Throwable $th) {
      $success = false;
    }

    return $success;
  }

  public function excluir(PDO $conexao, int $id) {
    $success = false;
    try {
      $sm_query = $conexao->prepare("DELETE FROM cliente WHERE id = :id");

      $sm_query->bindParam(":id", $id);

      if($sm_query->execute()){
        $success = true;
      }
    } catch (\Throwable $th) {
      $success = false;
    }

    return $success;
  }

  public function um(PDO $conexao, int $id){
    $cliente = 0;
    try {
      $sm_query = $conexao->prepare("SELECT c.id, c.endereco, c.uf, c.cidade, c.pais, c.bairro, c.id_pessoa,
        p.nome, p.email, p.telefone, p.data_nascimento, (select count(v.id) from venda v where v.id_cliente = c.id) as qtd_vendas
        FROM cliente c
        LEFT JOIN pessoa p ON p.id = c.id_pessoa
        WHERE c.id = :id");

      $sm_query->bindParam(":id", $id);

      if($sm_query->execute()){
        $cliente = $sm_query->fetch(PDO::FETCH_ASSOC);
      }

    } catch (\Throwable $th) {
      $cliente = 0;
    }

    return $cliente;
  }

  public function alterar(PDO $conexao, $cliente){
    $success = false;
    try {
      $sm_query = $conexao->prepare("UPDATE cliente
        set endereco = :endereco, uf = :uf, cidade = :cidade, pais = :pais,
          bairro = :bairro, id_pessoa = :id_pessoa
        WHERE id = :id");

      $sm_query->bindParam(":endereco", $cliente['endereco']);
      $sm_query->bindParam(":uf", $cliente['uf']);
      $sm_query->bindParam(":cidade", $cliente['cidade']);
      $sm_query->bindParam(":pais", $cliente['pais']);
      $sm_query->bindParam(":bairro", $cliente['bairro']);
      $sm_query->bindParam(":id_pessoa", $cliente['id_pessoa']);
      $sm_query->bindParam(":id", $cliente['id']);

      if($sm_query->execute()){
        $success = true;
      }
    } catch (\Throwable $th) {
      var_dump($th);
      $success = false;
    }

    return $success;
  }

  public function quantidade(PDO $conexao){
    $quantidade_cliente = 0;
    try {
      $sm_query = $conexao->prepare("SELECT count(*) as total FROM cliente");

      if($sm_query->execute()){
        $quantidade_cliente = $sm_query->fetch(PDO::FETCH_ASSOC);
      }

    } catch (\Throwable $th) {
      $quantidade_cliente = 0;
    }

    return $quantidade_cliente;
  }
}


?>