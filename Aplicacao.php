<?php
require_once('./src/controllers/Geral.controller.php');
require_once('./src/models/Usuario.model.php');

class Aplicacao {
  const db = null;
  public $tipos_usuarios = ["A", "F"];
  public $unidades_medida = ["UN", "KG", "CX", "L"];
  public $ufs = [
    'AC','AL','AP','AM','BA','CE','DF',
    'ES','GO','MA','MT','MS','MG','PA',
    'PB','PR','PE','PI','RJ','RN','RS',
    'RO','RR','SC','SP','SE','TO'
  ];

  function __construct() {
    $this->createConnetion();
  }

  public function validarUsuario($app, String $needed = "", $json = false){
    if(!$this->permissoesUsuarios($needed, $app->db)){
      if($json){
        http_response_code(401);
        echo(json_encode(['success' => false, 'message' => 'Não autorizado']));
      }else{
        ControllerGeral::CarregaTela($app, [
          'pagina' => 'geral/401'
        ]);
      }
      exit();
    }
  }

  public function permissoesUsuarios(String $needed, $db){
    $result = true;
    if(isset($_SERVER['HTTP_API_TOKEN']) && "" !== $_SERVER['HTTP_API_TOKEN']){
      $user = ModelUsuario::GetUserByToken($db, $_SERVER['HTTP_API_TOKEN']);
      if(!isset($user['nome']) && !isset($user['id'])){
        return false;
      }
    }else{
      if(!isset($_SESSION['islogged']) || false === $_SESSION['islogged']){
        $result = false;
      }else if("A" !== $_SESSION['tipo']){
        if($needed !== $_SESSION['tipo'])
          $result = false;
      }
    }

    return $result;
  }

  public function createConnetion() {
    $this->db = new PDO('mysql:host=localhost;dbname=registro_vendas', "bevilaqua", "Vaporwave05");
  }

  public static function ParsePut() {
    global $_PUT;

    /* PUT data comes in on the stdin stream */
    $putdata = fopen("php://input", "r");

    /* Open a file for writing */
    // $fp = fopen("myputfile.ext", "w");

    $raw_data = '';

    /* Read the data 1 KB at a time
      and write to the file */
    while ($chunk = fread($putdata, 1024))
      $raw_data .= $chunk;

    /* Close the streams */
    fclose($putdata);

    // Fetch content and determine boundary
    $boundary = substr($raw_data, 0, strpos($raw_data, "\r\n"));

    if(empty($boundary)){
      parse_str($raw_data,$data);
      $GLOBALS[ '_PUT' ] = $data;
      return;
    }

    // Fetch each part
    $parts = array_slice(explode($boundary, $raw_data), 1);
    $data = array();

    foreach ($parts as $part) {
      // If this is the last part, break
      if ($part == "--\r\n") break;

      // Separate content from headers
      $part = ltrim($part, "\r\n");
      list($raw_headers, $body) = explode("\r\n\r\n", $part, 2);

      // Parse the headers list
      $raw_headers = explode("\r\n", $raw_headers);
      $headers = array();
      foreach ($raw_headers as $header) {
        list($name, $value) = explode(':', $header);
        $headers[strtolower($name)] = ltrim($value, ' ');
      }

      // Parse the Content-Disposition to get the field name, etc.
      if (isset($headers['content-disposition'])) {
        $filename = null;
        $tmp_name = null;
        preg_match(
          '/^(.+); *name="([^"]+)"(; *filename="([^"]+)")?/',
          $headers['content-disposition'],
          $matches
        );
        list(, $type, $name) = $matches;

        //Parse File
        if( isset($matches[4]) ) {
          //if labeled the same as previous, skip
          if( isset( $_FILES[ $matches[ 2 ] ] ) )
          {
              continue;
          }

          //get filename
          $filename = $matches[4];

          //get tmp name
          $filename_parts = pathinfo( $filename );
          $tmp_name = tempnam( ini_get('upload_tmp_dir'), $filename_parts['filename']);

          //populate $_FILES with information, size may be off in multibyte situation
          $_FILES[ $matches[ 2 ] ] = array(
              'error'=>0,
              'name'=>$filename,
              'tmp_name'=>$tmp_name,
              'size'=>strlen( $body ),
              'type'=>$value
          );

          //place in temporary directory
          file_put_contents($tmp_name, $body);
        } else {
          //Parse Field
          $data[$name] = substr($body, 0, strlen($body) - 2);
        }
      }
    }
    $GLOBALS[ '_PUT' ] = $data;
    return;
  }
}
