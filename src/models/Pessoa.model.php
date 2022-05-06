<?php
class ModelPessoa {
  public function todas(PDO $conexao){
    $pessoas = 0;
    try {
      $sm_query = $conexao->prepare("SELECT id, nome, email, telefone, date_format(data_nascimento, '%d/%m/%Y') as data_nascimento,
        data_nascimento as data_nascimento_original
        FROM pessoa ORDER BY id DESC");

      if($sm_query->execute()){
        $pessoas = $sm_query->fetchall(PDO::FETCH_ASSOC);
      }

    } catch (\Throwable $th) {
      $pessoas = 0;
    }

    return $pessoas;
  }

  public function cadastrar(PDO $conexao, $pessoa){
    $success = false;
    try {
      $sm_query = $conexao->prepare("INSERT INTO pessoa (nome, email, telefone, data_nascimento)
        VALUES (:nome, :email, :telefone, :data_nascimento);");

      $sm_query->bindParam(":nome", $pessoa['nome']);
      $sm_query->bindParam(":email", $pessoa['email']);
      $sm_query->bindParam(":telefone", $pessoa['telefone']);
      $sm_query->bindParam(":data_nascimento", $pessoa['data_nascimento']);

      if($sm_query->execute()){
        $success = true;
      }
    } catch (\Throwable $th) {
      $success = false;
    }

    return $success;
  }

  public function excluir(PDO $conexao, $id) {
    $success = false;
    try {
      $sm_query = $conexao->prepare("DELETE FROM pessoa WHERE id = :id");

      $sm_query->bindParam(":id", $id);

      if($sm_query->execute()){
        $success = true;
      }
    } catch (\Throwable $th) {
      $success = false;
    }

    return $success;
  }

  public function uma(PDO $conexao, $id){
    $pessoa = [];
    try {
      $sm_query = $conexao->prepare("SELECT id, nome, email, telefone, date_format(data_nascimento, '%d/%m/%Y') as data_nascimento,
        data_nascimento as data_nascimento_original
        FROM pessoa
        WHERE id = :id");

      $sm_query->bindParam(":id", $id);

      if($sm_query->execute()){
        $pessoa = $sm_query->fetch(PDO::FETCH_ASSOC);
      }

    } catch (\Throwable $th) {
      $pessoa = [];
    }

    return $pessoa;
  }

  public function alterar(PDO $conexao, $pessoa){
    $success = false;
    try {
      $sm_query = $conexao->prepare("UPDATE pessoa set nome = :nome, email = :email,
          telefone = :telefone, data_nascimento = :data_nascimento
        WHERE id = :id");

      $sm_query->bindParam(":nome", $pessoa['nome']);
      $sm_query->bindParam(":email", $pessoa['email']);
      $sm_query->bindParam(":telefone", $pessoa['telefone']);
      $sm_query->bindParam(":data_nascimento", $pessoa['data_nascimento']);
      $sm_query->bindParam(":id", $pessoa['id']);

      if($sm_query->execute()){
        $success = true;
      }
    } catch (\Throwable $th) {
      $success = false;
    }

    return $success;
  }

  public function quantidade($conexao){
    $quantidade_funcionario = 0;
    try {
      $sm_query = $conexao->prepare("SELECT count(*) as total FROM pessoa");

      if($sm_query->execute()){
        $quantidade_funcionario = $sm_query->fetch(PDO::FETCH_ASSOC);
      }

    } catch (\Throwable $th) {
      $quantidade_funcionario = 0;
    }

    return $quantidade_funcionario;
  }
}

?>