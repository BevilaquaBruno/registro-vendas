<?php
class ModelFuncionario {
  public function todas(PDO $conexao){
    $pessoas = 0;
    try {
      $sm_query = $conexao->prepare("SELECT f.id, f.id_pessoa, f.salario, p.nome, p.email, p.telefone,
        f.data_admissao as data_admissao_original, DATE_FORMAT(f.data_admissao, '%d/%m/%Y') as data_admissao,
        DATE_FORMAT(p.data_nascimento, '%d/%m/%Y') as data_nascimento, p.data_nascimento as data_nascimento_original
          FROM funcionario f
          INNER JOIN pessoa p ON p.id = f.id_pessoa
          ORDER BY id DESC");

      if($sm_query->execute()){
        $pessoas = $sm_query->fetchall(PDO::FETCH_ASSOC);
      }

    } catch (\Throwable $th) {
      $pessoas = 0;
    }

    return $pessoas;
  }

  public function mediaSalarioTodos(PDO $conexao){
    $pessoas = 0;
    try {
      $sm_query = $conexao->prepare("SELECT f.salario, (SELECT AVG(f2.salario) FROM funcionario f2) AS media FROM funcionario f");

      if($sm_query->execute()){
        $pessoas = $sm_query->fetchall(PDO::FETCH_ASSOC);
      }

    } catch (\Throwable $th) {
      $pessoas = 0;
    }

    return $pessoas;
  }

  public function todosIdPessoa(PDO $conexao, int $id_pessoa){
    $pessoas = 0;
    try {
      $sm_query = $conexao->prepare("SELECT f.id, f.id_pessoa, f.salario, p.nome, p.email, p.telefone,
        f.data_admissao as data_admissao_original, DATE_FORMAT(f.data_admissao, '%d/%m/%Y') as data_admissao,
        DATE_FORMAT(p.data_nascimento, '%d/%m/%Y') as data_nascimento, p.data_nascimento as data_nascimento_original
          FROM funcionario f
          INNER JOIN pessoa p ON p.id = f.id_pessoa
          WHERE f.id_pessoa = :id_pessoa
          ORDER BY id DESC");
      $sm_query->bindParam(':id_pessoa', $id_pessoa);

      if($sm_query->execute()){
        $pessoas = $sm_query->fetchall(PDO::FETCH_ASSOC);
      }

    } catch (\Throwable $th) {
      $pessoas = 0;
    }

    return $pessoas;
  }

  public function cadastrar(PDO $conexao, $funcionario){
    $success = false;
    try {
      $sm_query = $conexao->prepare("INSERT INTO funcionario (id_pessoa, data_admissao, salario)
        VALUES (:id_pessoa, :data_admissao, :salario);");

      $sm_query->bindParam(":id_pessoa", $funcionario['id_pessoa']);
      $sm_query->bindParam(":data_admissao", $funcionario['data_admissao']);
      $sm_query->bindParam(":salario", $funcionario['salario']);

      if($sm_query->execute()){
        $success = true;
      }
    } catch (\Throwable $th) {
      $success = false;
    }

    return $success;
  }

  public function excluir(PDO $conexao, int $id) {
    $success = false;
    try {
      $sm_query = $conexao->prepare("DELETE FROM funcionario WHERE id = :id");

      $sm_query->bindParam(":id", $id);

      if($sm_query->execute()){
        $success = true;
      }
    } catch (\Throwable $th) {
      $success = false;
    }

    return $success;
  }

  public function um(PDO $conexao, int $id){
    $pessoa = 0;
    try {
      $sm_query = $conexao->prepare("SELECT f.id, f.id_pessoa, f.salario, p.nome, p.email, p.telefone,
        f.data_admissao as data_admissao_original, DATE_FORMAT(f.data_admissao, '%d/%m/%Y') as data_admissao,
        DATE_FORMAT(p.data_nascimento, '%d/%m/%Y') as data_nascimento, p.data_nascimento as data_nascimento_original
          FROM funcionario f
          INNER JOIN pessoa p ON p.id = f.id_pessoa
          WHERE f.id = :id");

      $sm_query->bindParam(":id", $id);

      if($sm_query->execute()){
        $pessoa = $sm_query->fetch(PDO::FETCH_ASSOC);
      }

    } catch (\Throwable $th) {
      $pessoa = 0;
    }

    return $pessoa;
  }

  public function alterar(PDO $conexao, $funcionario){
    $success = false;
    try {
      $sm_query = $conexao->prepare("UPDATE funcionario
        set id_pessoa = :id_pessoa, data_admissao = :data_admissao, salario = :salario
        WHERE id = :id");

      $sm_query->bindParam(":id_pessoa", $funcionario['id_pessoa']);
      $sm_query->bindParam(":data_admissao", $funcionario['data_admissao']);
      $sm_query->bindParam(":salario", $funcionario['salario']);
      $sm_query->bindParam(":id", $funcionario['id']);

      if($sm_query->execute()){
        $success = true;
      }
    } catch (\Throwable $th) {
      var_dump($th);
      $success = false;
    }

    return $success;
  }

  public function quantidade(PDO $conexao){
    $quantidade_pessoa = 0;
    try {
      $sm_query = $conexao->prepare("SELECT count(*) as total FROM funcionario");

      if($sm_query->execute()){
        $quantidade_pessoa = $sm_query->fetch(PDO::FETCH_ASSOC);
      }

    } catch (\Throwable $th) {
      $quantidade_pessoa = 0;
    }

    return $quantidade_pessoa;
  }
}

?>