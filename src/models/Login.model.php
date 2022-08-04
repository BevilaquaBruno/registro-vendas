<?php
class ModelLogin {
  public static function Logar(PDO $conexao, String $email, String $senha){
    $usuario = 0;
    try {
      $sm_query = $conexao->prepare("SELECT u.id, u.nome, u.email, u.tipo FROM usuario u WHERE u.email = :email AND u.senha = :senha");

      $sm_query->bindParam(":email", $email);
      $cripted_password = md5($senha);
      $sm_query->bindParam(":senha", $cripted_password);

      if($sm_query->execute()){
        $usuario = $sm_query->fetch(PDO::FETCH_ASSOC);
      }

    } catch (\Throwable $th) {
      $usuario = 0;
    }

    return $usuario;
  }
}
?>