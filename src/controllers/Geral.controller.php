<?php
require_once('./src/models/Pessoa.model.php');
require_once('./src/models/Funcionario.model.php');
require_once('./src/models/Usuario.model.php');
require_once('./src/models/Produto.model.php');
require_once('./src/models/Cliente.model.php');
require_once('./src/models/Venda.model.php');

class ControllerGeral {
  public static function InserirWilhyam($app){
    $dados = json_decode(file_get_contents("http://192.168.0.101/ws_fire/cliente.php"));
    foreach ($dados as $cliente) {
      ModelPessoa::Cadastrar($app->db, [
        "nome" => $cliente->nome,
        "email" => $cliente->email,
        "telefone" => $cliente->telefone,
        "data_nascimento" => null
      ]);
    }
  }

  public static function InserirMaria($app){
    $dados = json_decode(file_get_contents("http://192.168.0.104:3001/api/clientes"));
    $dados = $dados->result;
    foreach ($dados as $cliente) {
      $dt = explode("/",$cliente->nasc_cli);
      ModelPessoa::Cadastrar($app->db, [
        "nome" => $cliente->nome_cli,
        "email" => null,
        "telefone" => $cliente->tel_cli,
        "data_nascimento" => $dt[2]."/".$dt[1]."/".$dt[0]
      ]);
    }
  }

  public static function InserirGabriel($app){
    $dados = json_decode(file_get_contents("http://192.168.0.107/WSHospital/src/controllers/clientscontroller.php"));
    var_dump($dados);
    foreach ($dados as $cliente) {
      $dt = explode("/",$cliente->nasc_cli);
      ModelPessoa::Cadastrar($app->db, [
        "nome" => $cliente->nome,
        "email" => $cliente->cidade,
        "telefone" => null,
        "data_nascimento" => null
      ]);
    }
  }

  public static function Lista($app){
    $data_media_salarial = [];
    $media_salarial_valor = 0;
    $media_salarial = ModelFuncionario::MediaSalarioTodos($app->db);

    for ($i=0; $i < count($media_salarial); $i++) {
      array_push($data_media_salarial, $media_salarial[$i]['salario']);
      if (0 === $i) {
        $media_salarial_valor = $media_salarial[$i]['media'];
      }
    }
    $dados = [
      'quantidade' => [
        'pessoas' => ModelPessoa::Quantidade($app->db),
        'funcionarios' => ModelFuncionario::Quantidade($app->db),
        'usuarios' => ModelUsuario::Quantidade($app->db),
        'produtos' => ModelProduto::Quantidade($app->db),
        'clientes' => ModelCliente::Quantidade($app->db)
      ],
      'media_salarial' => [
        'data' => $data_media_salarial,
        'media' => $media_salarial_valor
      ],
      'vendas' => ModelVenda::TotalVendas($app->db),
      'pagina' => 'inicial'
    ];
    require('./src/views/principal.php');
  }

  public static function Developer($app) {
    $dados = [
      'pagina' => 'developer'
    ];

    require('./src/views/principal.php');
  }

  public static function CriarContaPage($app) {
    $dados = [
      'pagina' => 'usuario/cadastro',
      'acao' => "signup",
      'usuario' => [
        'id' => 0,
        'nome' => '',
        'email' => '',
        'tipo' => ''
      ]
    ];

    require('./src/views/principal.php');
  }

  public static function CriarConta($app) {
    $usuario = [
      "nome" => $_POST['nome'],
      "email" => $_POST['email'],
      "tipo" => "W",
      "senha" => $_POST['senha'],
      "repetir_senha" => $_POST['repetir_senha'],
      "token" => hash("ripemd160", $_POST['email'])
    ];


    if("" === $usuario['nome'] || null === $usuario['nome']) {
      http_response_code(400);
      echo(json_encode([ "success" => false, "message" => "Nome é obrigatório" ]));
      exit();
    }

    if("" === $usuario['email'] || null === $usuario['email']){
      http_response_code(400);
      echo(json_encode([ "success" => false, "message" => "Email é obrigatório" ]));
      exit();
    }

    if(!validateEmail($usuario['email'])){
      http_response_code(400);
      echo(json_encode([ "success" => false, "message" => "Email inválido" ]));
      exit();
    }

    $usuarios_email = ModelUsuario::TodosPorEmail($app->db, $usuario['email']);
    if(count($usuarios_email) >= 1){
      http_response_code(400);
      echo(json_encode([ "success" => false, "message" => "Já existe um usuário com esse email vinculado" ]));
      exit();
    }

    if('' === $usuario['senha'] || '' === $usuario['repetir_senha']){
      http_response_code(400);
      echo(json_encode([ "success" => false, "message" => "As duas senhas são obrigatórias" ]));
      exit();
    }

    if($usuario['senha'] !== $usuario['repetir_senha']){
      http_response_code(400);
      echo(json_encode([ "success" => false, "message" => "As duas senhas precisam ser iguais" ]));
      exit();
    }

    $result = ModelUsuario::Cadastrar($app->db, $usuario);

    http_response_code(200);
    echo(json_encode([ "success" => $result, "message" => "" ]));
  }

  public static function CarregaTela($app, $dados){
    require('./src/views/principal.php');
  }
}
?>
