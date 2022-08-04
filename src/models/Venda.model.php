<?php
class ModelVenda {
  public static function Todas(PDO $conexao){
    $pessoas = 0;
    try {
      $sm_query = $conexao->prepare("SELECT v.id, v.data_venda as data_venda_original,
          date_format(v.data_venda, '%d/%m/%Y') as data_venda,
          v.observacao, v.id_cliente, v.id_usuario, v.id_funcionario,
          c.endereco AS cliente_endereco, c.uf AS cliente_uf, c.cidade AS cliente_cidade,
          c.pais AS cliente_pais, c.bairro AS cliente_bairro, pc.id AS cliente_id_pessoa, pc.nome AS cliente_nome,
          pc.email AS cliente_email, pc.telefone AS cliente_telefone, pc.data_nascimento AS cliente_data_nascimento,
          pf.id AS funcionario_id_pessoa, pf.nome AS funcionario_nome,
          pf.email AS funcionario_email, pf.telefone AS funcionario_telefone, pf.data_nascimento AS funcionario_data_nascimento,
          (SELECT sum(vp.quantidade) FROM venda_produto vp WHERE vp.id_venda = v.id) as qtd_produtos,
          (SELECT sum(vp.quantidade * vp.valor_unitario) FROM venda_produto vp WHERE vp.id_venda = v.id) as valor_total
        FROM venda v
          LEFT JOIN cliente c ON c.id = v.id_cliente
          LEFT JOIN pessoa pc ON pc.id = c.id_pessoa
          LEFT JOIN funcionario f ON f.id = v.id_funcionario
          LEFT JOIN pessoa pf ON pf.id = f.id_pessoa
      ");

      if($sm_query->execute()){
        $pessoas = $sm_query->fetchall(PDO::FETCH_ASSOC);
      }

    } catch (\Throwable $th) {
      $pessoas = 0;
    }

    return $pessoas;
  }

  public static function TodasUsuario(PDO $conexao, $id_usuario){
    $pessoas = 0;
    try {
      $sm_query = $conexao->prepare("SELECT v.id, v.data_venda as data_venda_original,
          date_format(v.data_venda, '%d/%m/%Y') as data_venda,
          v.observacao, v.id_cliente, v.id_usuario, v.id_funcionario
        FROM venda v
          WHERE v.id_usuario = :id_usuario");

      $sm_query->bindParam(':id_usuario', $id_usuario);

      if($sm_query->execute()){
        $pessoas = $sm_query->fetchall(PDO::FETCH_ASSOC);
      }

    } catch (\Throwable $th) {
      $pessoas = 0;
    }

    return $pessoas;
  }

  public static function TodasProduto(PDO $conexao, $id_produto){
    $pessoas = 0;
    try {
      $sm_query = $conexao->prepare("SELECT v.id, v.data_venda as data_venda_original,
          date_format(v.data_venda, '%d/%m/%Y') as data_venda,
          v.observacao, v.id_cliente, v.id_usuario, v.id_funcionario
        FROM venda v
          LEFT JOIN venda_produto vp on vp.id_venda = v.id
          WHERE vp.id_produto = :id_produto");

      $sm_query->bindParam(':id_produto', $id_produto);

      if($sm_query->execute()){
        $pessoas = $sm_query->fetchall(PDO::FETCH_ASSOC);
      }

    } catch (\Throwable $th) {
      $pessoas = 0;
    }

    return $pessoas;
  }

  public static function TodasCliente(PDO $conexao, $id_cliente){
    $pessoas = 0;
    try {
      $sm_query = $conexao->prepare("SELECT v.id, v.data_venda as data_venda_original,
          date_format(v.data_venda, '%d/%m/%Y') as data_venda,
          v.observacao, v.id_cliente, v.id_usuario, v.id_funcionario
        FROM venda v
          WHERE v.id_cliente = :id_cliente");

      $sm_query->bindParam(':id_cliente', $id_cliente);

      if($sm_query->execute()){
        $pessoas = $sm_query->fetchall(PDO::FETCH_ASSOC);
      }

    } catch (\Throwable $th) {
      $pessoas = 0;
    }

    return $pessoas;
  }

  public static function Excluir(PDO $conexao, int $id) {
    $success = false;
    try {
      $sm_query = $conexao->prepare("DELETE FROM venda WHERE id = :id");

      $sm_query->bindParam(":id", $id);

      if($sm_query->execute()){
        $success = true;
      }
    } catch (\Throwable $th) {
      $success = false;
    }

    return $success;
  }

  public static function Cadastrar(PDO $conexao, Array $venda) {
    try {
      $conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $conexao->beginTransaction();

      $sm_query = $conexao->prepare("INSERT INTO venda(data_venda, observacao, id_cliente, id_usuario, id_funcionario)
        VALUES (:data_venda, :observacao, :id_cliente, :id_usuario, :id_funcionario)");

      $sm_query->bindParam(':data_venda', $venda['data_venda']);
      $sm_query->bindParam(':observacao', $venda['observacao']);
      $sm_query->bindParam(':id_cliente', $venda['id_cliente']);
      $sm_query->bindParam(':id_usuario', $_SESSION['id_u']);
      $sm_query->bindParam(':id_funcionario', $venda['id_funcionario']);

      $sm_query->execute();
      $id_venda = $conexao->lastInsertId();

      foreach ($venda['venda_produto'] as $v) {
        $sm_query2 = $conexao->prepare('INSERT INTO venda_produto(quantidade, valor_unitario, id_venda, id_produto)
          VALUES(:quantidade, :valor_unitario, :id_venda, :id_produto)');
        $sm_query2->bindParam(':quantidade', $v->quantidade);
        $sm_query2->bindParam(':valor_unitario', $v->valor_unitario);
        $sm_query2->bindParam(':id_venda', $id_venda);
        $sm_query2->bindParam(':id_produto', $v->id_produto);

        $sm_query2->execute();
      }

      $conexao->commit();
      return true;
    } catch (Exception $e) {
      var_dump($e);
      exit();
      $conexao->rollBack();
      return false;
    }
  }

  public static function Uma(PDO $conexao, int $id){
    $venda = 0;
    try {
      $sm_query = $conexao->prepare("SELECT v.id, v.data_venda as data_venda_original,
          date_format(v.data_venda, '%d/%m/%Y') as data_venda,
          v.observacao, v.id_cliente, v.id_usuario, v.id_funcionario,
          c.endereco AS cliente_endereco, c.uf AS cliente_uf, c.cidade AS cliente_cidade,
          c.pais AS cliente_pais, c.bairro AS cliente_bairro, pc.id AS cliente_id_pessoa, pc.nome AS cliente_nome,
          pc.email AS cliente_email, pc.telefone AS cliente_telefone, pc.data_nascimento AS cliente_data_nascimento,
          pf.id AS funcionario_id_pessoa, pf.nome AS funcionario_nome,
          pf.email AS funcionario_email, pf.telefone AS funcionario_telefone, pf.data_nascimento AS funcionario_data_nascimento,
          (SELECT sum(vp.quantidade) FROM venda_produto vp WHERE vp.id_venda = v.id) as qtd_produtos,
          (SELECT sum(vp.quantidade * vp.valor_unitario) FROM venda_produto vp WHERE vp.id_venda = v.id) as valor_total
        FROM venda v
          LEFT JOIN cliente c ON c.id = v.id_cliente
          LEFT JOIN pessoa pc ON pc.id = c.id_pessoa
          LEFT JOIN funcionario f ON f.id = v.id_funcionario
          LEFT JOIN pessoa pf ON pf.id = f.id_pessoa
        WHERE v.id = :id
      ");
      $sm_query->bindParam(':id', $id);

      if($sm_query->execute()){
        $venda = $sm_query->fetch(PDO::FETCH_ASSOC);
      }

    } catch (\Throwable $th) {
      $venda = 0;
    }

    return $venda;
  }

  public static function TodasVendaProduto(PDO $conexao, int $id_venda){
    $pessoas = 0;
    try {
      $sm_query = $conexao->prepare("
        SELECT vp.id, vp.quantidade, vp.valor_unitario, vp.id_venda, vp.id_produto, p.descricao AS descricao_produto
          FROM venda_produto vp
          LEFT JOIN produto p ON p.id = vp.id_produto
        WHERE vp.id_venda = :id_venda");
      $sm_query->bindParam(':id_venda', $id_venda);

      if($sm_query->execute()){
        $pessoas = $sm_query->fetchall(PDO::FETCH_ASSOC);
      }

    } catch (\Throwable $th) {
      $pessoas = 0;
    }

    return $pessoas;
  }

  public static function Alterar(PDO $conexao, Array $venda) {
    try {
      $conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $conexao->beginTransaction();

      $sm_query = $conexao->prepare("UPDATE venda SET data_venda = :data_venda, observacao = :observacao,
        id_cliente = :id_cliente, id_usuario = :id_usuario, id_funcionario = :id_funcionario
        WHERE id = :id");

      $sm_query->bindParam(':data_venda', $venda['data_venda']);
      $sm_query->bindParam(':observacao', $venda['observacao']);
      $sm_query->bindParam(':id_cliente', $venda['id_cliente']);
      $sm_query->bindParam(':id_usuario', $_SESSION['id_u']);
      $sm_query->bindParam(':id_funcionario', $venda['id_funcionario']);
      $sm_query->bindParam(':id', $venda['id']);

      $sm_query->execute();

      foreach ($venda['venda_produto'] as $v) {
        if(false !== strpos($v->id, '-remover')){
          $id = str_replace('-remover', '', $v->id);
          $sm_query2 = $conexao->prepare('DELETE FROM venda_produto WHERE id = :id');
          $sm_query2->bindParam(':id', $id);

          $sm_query2->execute();
        }else if('0' == $v->id){
          $sm_query2 = $conexao->prepare('INSERT INTO venda_produto(quantidade, valor_unitario, id_venda, id_produto)
            VALUES(:quantidade, :valor_unitario, :id_venda, :id_produto)');
          $sm_query2->bindParam(':quantidade', $v->quantidade);
          $sm_query2->bindParam(':valor_unitario', $v->valor_unitario);
          $sm_query2->bindParam(':id_venda', $venda['id']);
          $sm_query2->bindParam(':id_produto', $v->id_produto);

          $sm_query2->execute();
        }else{
          $sm_query2 = $conexao->prepare('UPDATE venda_produto SET quantidade = :quantidade, valor_unitario = :valor_unitario,
            id_venda = :id_venda, id_produto = :id_produto
            WHERE id = :id');
          $sm_query2->bindParam(':quantidade', $v->quantidade);
          $sm_query2->bindParam(':valor_unitario', $v->valor_unitario);
          $sm_query2->bindParam(':id_venda', $venda['id']);
          $sm_query2->bindParam(':id_produto', $v->id_produto);
          $sm_query2->bindParam(':id', $v->id);

          $sm_query2->execute();
        }
      }

      $conexao->commit();
      return true;
    } catch (Exception $e) {
      var_dump($e);
      exit();
      $conexao->rollBack();
      return false;
    }
  }

  public static function TotalVendas($conexao) {
    $venda = 0;
    try {
      $sm_query = $conexao->prepare("SELECT sum(vp.quantidade * vp.valor_unitario) as total FROM venda_produto vp");

      if($sm_query->execute()){
        $venda = $sm_query->fetch(PDO::FETCH_ASSOC);
      }

    } catch (\Throwable $th) {
      $venda = 0;
    }

    return $venda;
  }
}

?>