<?php
require_once('./src/controllers/Geral.controller.php');
require_once('./src//models/Usuario.model.php');
require_once('./src//models/Venda.model.php');
require_once('./src/general.php');

class ControllerUsuario {
  //api routes
  public static function Todos($app){
    $app->validarUsuario($app, "A", true);

    $lista_usuarios = ModelUsuario::Todos($app->db);

    echo(json_encode($lista_usuarios));
  }

  public static function Um($app, $id){
    $usuario = ModelUsuario::Um($app->db, $id);

    echo(json_encode($usuario));
  }

  public static function Cadastrar($app) {
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

    $usuarios_email = ModelUsuario::TodosPorEmail($app->db, $usuario['email']);
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

    $result = ModelUsuario::Cadastrar($app->db, $usuario);

    echo(json_encode([ "success" => $result, "message" => "" ]));
  }

  public static function Deletar($app, $id) {
    $app->validarUsuario($app, "A", true);

    $vendas_usuario = ModelVenda::TodasUsuario($app->db, $id);

    if(count($vendas_usuario) >= 1){
      echo(json_encode([ "success" => false, "message" => "Não é possível excluir o usuário porque ele está vinculado a vendas" ]));
      exit();
    }

    $result = ModelUsuario::Excluir($app->db, $id);

    echo(json_encode([ "success" => $result, "message" => "" ]));
  }

  public static function Alterar($app) {
    $app->validarUsuario($app, "A", true);

    // create put variable
    global $_PUT;
    Aplicacao::ParsePut();

    $usuario = [
      "id" => $_PUT['id'],
      "nome" => $_PUT['nome'],
      "email" => $_PUT['email'],
      "tipo" => $_PUT['tipo']
    ];

    if($usuario['id'] == $_SESSION['id_u']){
      $usuario['senha'] = $_PUT['senha'];
      $usuario['repetir_senha'] = $_PUT['repetir_senha'];

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

    $usuarios_email = ModelUsuario::TodosPorEmail($app->db, $usuario['email'], $usuario['id']);
    if(count($usuarios_email) >= 1){
      echo(json_encode([ "success" => false, "message" => "Já existe um usuário com esse email vinculado" ]));
      exit();
    }

    if (false === array_search($usuario['tipo'], $app->tipos_usuarios)) {
      echo(json_encode([ "success" => false, "message" => "Tipo de usuário inválido" ]));
      exit();
    }

    if($usuario['id'] == $_SESSION['id_u']){
      $result = ModelUsuario::AlterarComSenha($app->db, $usuario);
    }else{
      $result = ModelUsuario::Alterar($app->db, $usuario);
    }

    echo(json_encode([ "success" => $result, "message" => "" ]));
  }

  //view routes
  public static function Lista($app){
    $app->validarUsuario($app, "A");

    $dados = [
      'pagina' => 'usuario/lista'
    ];

    ControllerGeral::CarregaTela($app, $dados);
  }

  public static function Cadastro($app) {
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
    ControllerGeral::CarregaTela($app, $dados);
  }

  public static function Alteracao($app, $id) {
    $app->validarUsuario($app, "A");

    $dados = [
      'pagina' => 'usuario/cadastro',
      'acao' => "alterar",
      'usuario' => ModelUsuario::Um($app->db, $id)
    ];
    ControllerGeral::CarregaTela($app, $dados);
  }
}
