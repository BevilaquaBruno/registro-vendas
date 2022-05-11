<?php
require_once('./src/controllers/Geral.controller.php');
require_once('./src//models/Usuario.model.php');
require_once('./src/general.php');

class ControllerUsuario {
  public function lista($app){
    $app->validarUsuario($app, "A");

    $dados = [
      'pagina' => 'usuario/lista'
    ];

    $controllerGeral = new ControllerGeral();
    $controllerGeral->carregaTela($app, $dados);
  }

  public function listajson($app){
    $app->validarUsuario($app, "A", true);

    $mdlUsuario = new ModelUsuario();
    $lista_usuarios = $mdlUsuario->todos($app->db);

    echo(json_encode($lista_usuarios));
  }

  public function cadastro($app) {
    $app->validarUsuario($app, "A");

    $dados = [
      'pagina' => 'usuario/cadastro',
      'acao' => "cadastrar",
      'usuario' => [
        'id' => 0,
        'nome' => '',
        'email' => '',
        'tipo' => ''
      ]
    ];
    $controllerGeral = new ControllerGeral();
    $controllerGeral->carregaTela($app, $dados);
  }

  public function cadastrar($app) {
    $app->validarUsuario($app, "A", true);

    $usuario = [
      "nome" => $_POST['nome'],
      "email" => $_POST['email'],
      "tipo" => $_POST['tipo'],
      "senha" => $_POST['senha'],
      "repetir_senha" => $_POST['repetir_senha'],
    ];


    if("" === $usuario['nome'] || null === $usuario['nome']) {
      echo(json_encode([ "success" => false, "message" => "Nome é obrigatório" ]));
      exit();
    }

    if("" === $usuario['email'] || null === $usuario['email']){
      echo(json_encode([ "success" => false, "message" => "Email é obrigatório" ]));
      exit();
    }

    if(!validateEmail($usuario['email'])){
      echo(json_encode([ "success" => false, "message" => "Email inválido" ]));
      exit();
    }

    if (false === array_search($usuario['tipo'], $app->tipos_usuarios)) {
      echo(json_encode([ "success" => false, "message" => "Tipo de usuário inválido" ]));
      exit();
    }

    if('' === $usuario['senha'] || '' === $usuario['repetir_senha']){
      echo(json_encode([ "success" => false, "message" => "As duas senhas são obrigatórias" ]));
      exit();
    }

    if($usuario['senha'] !== $usuario['repetir_senha']){
      echo(json_encode([ "success" => false, "message" => "As duas senhas precisam ser iguais" ]));
      exit();
    }

    $mdlUsuario = new ModelUsuario();
    $result = $mdlUsuario->cadastrar($app->db, $usuario);

    echo(json_encode([ "success" => $result, "message" => "" ]));
  }

  public function deletar($app) {
    $app->validarUsuario($app, "A", true);

    $id = $_GET['id'];

    // AQUI FALTA A VALIDACAO SE O USUARIO TIVER VENDAS

    $mdlUsuario = new ModelUsuario();
    $result = $mdlUsuario->excluir($app->db, $id);

    echo(json_encode([ "success" => $result, "message" => "" ]));
  }
}
?>
