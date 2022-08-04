<?php
class ModelUsuario {
  public static function Todos(PDO $conexao){
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

  public static function Cadastrar(PDO $conexao, $usuario){
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

  public static function Excluir(PDO $conexao, $id) {
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

  public static function Um(PDO $conexao, $id){
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

  public static function TodosPorEmail(PDO $conexao, $email, $id = 0){
    $usuario = [];
    try {
      if(0 === $id){
        $sm_query = $conexao->prepare("SELECT u.id, u.nome, u.email, u.tipo
          FROM usuario u
          WHERE u.email = :email");
      }else{
        $sm_query = $conexao->prepare("SELECT u.id, u.nome, u.email, u.tipo
          FROM usuario u
          WHERE u.email = :email and u.id <> :id");
        $sm_query->bindParam(":id", $id);
      }

      $sm_query->bindParam(":email", $email);

      if($sm_query->execute()){
        $usuario = $sm_query->fetchall(PDO::FETCH_ASSOC);
      }

    } catch (\Throwable $th) {
      $usuario = [];
    }

    return $usuario;
  }

  public static function Alterar(PDO $conexao, $usuario){
    $success = false;
    try {
      $sm_query = $conexao->prepare("UPDATE usuario set nome = :nome,
          email = :email, tipo = :tipo
        WHERE id = :id");

      $sm_query->bindParam(":nome", $usuario['nome']);
      $sm_query->bindParam(":email", $usuario['email']);
      $sm_query->bindParam(":tipo", $usuario['tipo']);
      $sm_query->bindParam(":id", $usuario['id']);

      if($sm_query->execute()){
        $success = true;
      }
    } catch (\Throwable $th) {
      $success = false;
    }

    return $success;
  }

  public static function AlterarComSenha(PDO $conexao, $usuario){
    $success = false;
    try {
      $sm_query = $conexao->prepare("UPDATE usuario set nome = :nome,
          email = :email, tipo = :tipo, senha = :senha
        WHERE id = :id");

      $sm_query->bindParam(":nome", $usuario['nome']);
      $sm_query->bindParam(":email", $usuario['email']);
      $sm_query->bindParam(":tipo", $usuario['tipo']);
      $usuario['senha'] = md5($usuario['senha']);
      $sm_query->bindParam(":senha", $usuario['senha']);
      $sm_query->bindParam(":id", $usuario['id']);

      if($sm_query->execute()){
        $success = true;
      }
    } catch (\Throwable $th) {
      $success = false;
    }

    return $success;
  }

  public static function Quantidade(PDO $conexao){
    $quantidade_usuario = 0;
    try {
      $sm_query = $conexao->prepare("SELECT count(*) as total FROM usuario");

      if($sm_query->execute()){
        $quantidade_usuario = $sm_query->fetch(PDO::FETCH_ASSOC);
      }

    } catch (\Throwable $th) {
      $quantidade_usuario = 0;
    }

    return $quantidade_usuario;
  }
}

?>