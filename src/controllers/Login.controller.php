<?php
require_once('./src/controllers/Geral.controller.php');
require_once('./src/models/Login.model.php');

class ControllerLogin {
  public static function Lista($app){
    $dados = [
      'pagina' => 'login/login',
      'acao' => 'logar'
    ];

    ControllerGeral::CarregaTela($app, $dados);
  }

  public static function Logar($app){
    $login = [
      'email' => $_POST['email'],
      'senha' => $_POST['senha']
    ];

    $usuario = ModelLogin::Logar($app->db, $login['email'], $login['senha']);

    if(false === $usuario || 0 === count($usuario)){
      echo(json_encode([ "success" => false, "message" => "Email ou senha incorretos" ]));
      exit();
    }

    if(isset($_SESSION['islogged']) && true === $_SESSION['islogged']){
      $_SESSION['islogged'] = false;
      session_destroy();
    }
    //session_id(md5($usuario['id'].$usuario['nome'].$usuario['email'].$usuario['tipo']));
    $_SESSION['id'] = md5($usuario['id'].$usuario['nome'].$usuario['email'].$usuario['tipo']);
    $_SESSION['id_u'] = $usuario['id'];
    $_SESSION['nome'] = $usuario['nome'];
    $_SESSION['email'] = $usuario['email'];
    $_SESSION['tipo'] = $usuario['tipo'];
    $_SESSION['islogged'] = true;
    echo(json_encode([ "success" => true, "message" => "Usuário logado com sucesso" ]));
  }

  public static function Deslogar($app) {
    if(isset($_SESSION['islogged']) && true === $_SESSION['islogged']){
      $_SESSION['islogged'] = false;
      session_destroy();
    }

    echo(json_encode([ "success" => true, "message" => "Deslogado com sucesso" ]));
  }
}
?>
