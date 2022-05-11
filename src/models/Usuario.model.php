<?php
class ModelUsuario {
  public function todos(PDO $conexao){
    $pessoas = 0;
    try {
      $sm_query = $conexao->prepare("SELECT u.id, u.nome, u.tipo, u.email
        FROM usuario u ORDER BY id DESC");

      if($sm_query->execute()){
        $pessoas = $sm_query->fetchall(PDO::FETCH_ASSOC);
      }

    } catch (\Throwable $th) {
      $pessoas = 0;
    }

    return $pessoas;
  }

  public function cadastrar(PDO $conexao, $usuario){
    $success = false;
    try {
      $sm_query = $conexao->prepare("INSERT INTO usuario (nome, email, tipo, senha)
        VALUES (:nome, :email, :tipo, :senha);");

      $sm_query->bindParam(":nome", $usuario['nome']);
      $sm_query->bindParam(":email", $usuario['email']);
      $sm_query->bindParam(":tipo", $usuario['tipo']);
      $usuario['senha'] = md5($usuario['senha']);
      $sm_query->bindParam(":senha", $usuario['senha']);

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
      $sm_query = $conexao->prepare("DELETE FROM usuario WHERE id = :id");

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
    $usuario = [];
    try {
      $sm_query = $conexao->prepare("SELECT u.id, u.nome, u.email, u.tipo
        FROM usuario u
        WHERE u.id = :id");

      $sm_query->bindParam(":id", $id);

      if($sm_query->execute()){
        $usuario = $sm_query->fetch(PDO::FETCH_ASSOC);
      }

    } catch (\Throwable $th) {
      $usuario = [];
    }

    return $usuario;
  }

  public function alterar(PDO $conexao, $pessoa){
    $success = false;
    try {
      $sm_query = $conexao->prepare("UPDATE usuario set nome = :nome,
          email = :email, tipo = :tipo
        WHERE id = :id");

      $sm_query->bindParam(":nome", $pessoa['nome']);
      $sm_query->bindParam(":email", $pessoa['email']);
      $sm_query->bindParam(":tipo", $pessoa['tipo']);
      $sm_query->bindParam(":id", $pessoa['id']);

      if($sm_query->execute()){
        $success = true;
      }
    } catch (\Throwable $th) {
      $success = false;
    }

    return $success;
  }
}

?>