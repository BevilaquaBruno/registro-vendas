<?php
class ModelVenda {
  public function todas(PDO $conexao){
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

  public function todasUsuario(PDO $conexao, $id_usuario){
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

  public function todasProduto(PDO $conexao, $id_produto){
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

  public function todasCliente(PDO $conexao, $id_cliente){
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

  public function excluir(PDO $conexao, int $id) {
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
}

?>