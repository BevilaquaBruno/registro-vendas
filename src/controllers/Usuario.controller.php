<?php
require_once('./src/controllers/Geral.controller.php');
require_once('./src//models/Usuario.model.php');
require_once('./src//models/Venda.model.php');
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

    $mdlUsuario = new ModelUsuario();
    $usuarios_email = $mdlUsuario->todosPorEmail($app->db, $usuario['email']);
    if(count($usuarios_email) >= 1){
      echo(json_encode([ "success" => false, "message" => "Já existe um usuário com esse email vinculado" ]));
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

    $result = $mdlUsuario->cadastrar($app->db, $usuario);

    echo(json_encode([ "success" => $result, "message" => "" ]));
  }

  public function deletar($app) {
    $app->validarUsuario($app, "A", true);

    $id = $_GET['id'];

    $mdlVenda = new ModelVenda();
    $vendas_usuario = $mdlVenda->todasUsuario($app->db, $id);

    if(count($vendas_usuario) >= 1){
      echo(json_encode([ "success" => false, "message" => "Não é possível excluir o usuário porque ele está vinculado a vendas" ]));
      exit();
    }

    $mdlUsuario = new ModelUsuario();
    $result = $mdlUsuario->excluir($app->db, $id);

    echo(json_encode([ "success" => $result, "message" => "" ]));
  }

  public function alteracao($app) {
    $app->validarUsuario($app, "A");
    $mdlUsuario = new ModelUsuario();
    $id = $_GET['id'];

    $dados = [
      'pagina' => 'usuario/cadastro',
      'acao' => "alterar",
      'usuario' => $mdlUsuario->um($app->db, $id)
    ];
    $controllerGeral = new ControllerGeral();
    $controllerGeral->carregaTela($app, $dados);
  }

  public function alterar($app) {
    $app->validarUsuario($app, "A", true);

    $usuario = [
      "id" => $_POST['id'],
      "nome" => $_POST['nome'],
      "email" => $_POST['email'],
      "tipo" => $_POST['tipo']
    ];

    if($usuario['id'] == $_SESSION['id_u']){
      $usuario['senha'] = $_POST['senha'];
      $usuario['repetir_senha'] = $_POST['repetir_senha'];

      if('' === $usuario['senha'] || '' === $usuario['repetir_senha']){
        echo(json_encode([ "success" => false, "message" => "As duas senhas são obrigatórias" ]));
        exit();
      }

      if($usuario['senha'] !== $usuario['repetir_senha']){
        echo(json_encode([ "success" => false, "message" => "As duas senhas precisam ser iguais" ]));
        exit();
      }
    }

    if(null === $usuario['id'] || 0 === $usuario['id']){
      echo(json_encode([ "success" => false, "message" => "Erro grave ao alterar o usuário, atualize a página" ]));
      exit();
    }

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

    $mdlUsuario = new ModelUsuario();
    $usuarios_email = $mdlUsuario->todosPorEmail($app->db, $usuario['email'], $usuario['id']);
    if(count($usuarios_email) >= 1){
      echo(json_encode([ "success" => false, "message" => "Já existe um usuário com esse email vinculado" ]));
      exit();
    }

    if (false === array_search($usuario['tipo'], $app->tipos_usuarios)) {
      echo(json_encode([ "success" => false, "message" => "Tipo de usuário inválido" ]));
      exit();
    }

    if($usuario['id'] == $_SESSION['id_u']){
      $result = $mdlUsuario->alterarComSenha($app->db, $usuario);
    }else{
      $result = $mdlUsuario->alterar($app->db, $usuario);
    }

    echo(json_encode([ "success" => $result, "message" => "" ]));
  }
}
