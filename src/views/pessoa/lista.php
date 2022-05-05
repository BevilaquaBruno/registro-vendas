<table class="myTable">
  <thead>
    <tr>
      <th>id</th>
      <th>Nome</th>
      <th>email</th>
      <th>Telefone</th>
      <th>Nascimento</th>
      <th>Ações</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($dados['pessoas'] as $pessoa) { ?>
      <tr>
        <td><?=$pessoa['id']?></td>
        <td><?=$pessoa['nome']?></td>
        <td><?=$pessoa['email']?></td>
        <td><?=$pessoa['telefone']?></td>
        <td><?=$pessoa['data_nascimento']?></td>
        <td>
          <button class="myButton myButtonBlue">
            <a href="index.php?m=pessoa&a=alteracao&id=<?=$pessoa['id']?>">Editar</a>
          </button>
          <button class="myButton myButtonRed">
            <a href="index.php?m=pessoa&a=deletar&id=<?=$pessoa['id']?>">Excluir</a>
          </button>
        </td>
      </tr>
    <?php } ?>
  </tbody>
</table>
<button class="myButton myButtonGreen">
  <a href="index.php?m=pessoa&a=cadastro">Cadastrar</a>
</button>
