<?php
class ModelProduto {
  public function todos(PDO $conexao){
    $produtos = 0;
    try {
      $sm_query = $conexao->prepare("SELECT p.id, p.descricao, p.quantidade, p.valor_venda, p.valor_compra, p.unidade_medida
        FROM produto p");

      if($sm_query->execute()){
        $produtos = $sm_query->fetchall(PDO::FETCH_ASSOC);
      }

    } catch (\Throwable $th) {
      $produtos = 0;
    }

    return $produtos;
  }

  public function cadastrar(PDO $conexao, $produto){
    $success = false;
    try {
      $sm_query = $conexao->prepare("INSERT INTO produto (descricao, quantidade, valor_venda, valor_compra, unidade_medida)
        VALUES (:descricao, :quantidade, :valor_venda, :valor_compra, :unidade_medida);");

      $sm_query->bindParam(":descricao", $produto['descricao']);
      $sm_query->bindParam(":quantidade", $produto['quantidade']);
      $sm_query->bindParam(":valor_venda", $produto['valor_venda']);
      $sm_query->bindParam(":valor_compra", $produto['valor_compra']);
      $sm_query->bindParam(":unidade_medida", $produto['unidade_medida']);

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
      $sm_query = $conexao->prepare("DELETE FROM produto WHERE id = :id");

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
    $pessoa = 0;
    try {
      $sm_query = $conexao->prepare("SELECT p.id, p.descricao, p.quantidade, p.valor_venda, p.valor_compra, p.unidade_medida
        FROM produto p WHERE p.id = :id");

      $sm_query->bindParam(":id", $id);

      if($sm_query->execute()){
        $pessoa = $sm_query->fetch(PDO::FETCH_ASSOC);
      }

    } catch (\Throwable $th) {
      $pessoa = 0;
    }

    return $pessoa;
  }

  public function alterar(PDO $conexao, $produto){
    $success = false;
    try {
      $sm_query = $conexao->prepare("UPDATE produto
        set descricao = :descricao, quantidade = :quantidade, valor_venda = :valor_venda,
          valor_compra = :valor_compra, unidade_medida = :unidade_medida
        WHERE id = :id");

      $sm_query->bindParam(":descricao", $produto['descricao']);
      $sm_query->bindParam(":quantidade", $produto['quantidade']);
      $sm_query->bindParam(":valor_venda", $produto['valor_venda']);
      $sm_query->bindParam(":valor_compra", $produto['valor_compra']);
      $sm_query->bindParam(":unidade_medida", $produto['unidade_medida']);
      $sm_query->bindParam(":id", $produto['id']);

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
    $quantidade_pessoa = 0;
    try {
      $sm_query = $conexao->prepare("SELECT count(*) as total FROM produto");

      if($sm_query->execute()){
        $quantidade_pessoa = $sm_query->fetch(PDO::FETCH_ASSOC);
      }

    } catch (\Throwable $th) {
      $quantidade_pessoa = 0;
    }

    return $quantidade_pessoa;
  }
}

?>