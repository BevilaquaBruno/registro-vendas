<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- Simple Mask Money - https://github.com/codermarcos/simple-mask-money -->
  <script src="public/javascripts/simple-mask-money.min.js"></script>
  <!-- Axios - https://axios-http.com/ptbr/docs/intro -->
  <script src="public/javascripts/axios.min.js"></script>
  <!-- Notifications - https://f3oall.github.io/awesome-notifications/docs/ -->
  <link rel="stylesheet" href="public/styles/awesome_notifications.min.css">
  <script src="public/javascripts/awesome_notifications.min.js"></script>
  <!-- PureCSS - https://purecss.io/ -->
  <link rel="stylesheet" href="public/styles/pure-min.css">
  <!-- PureMask - https://romulobrasil.com/puremask-js/ -->
  <script src="public/javascripts/puremask.min.js"></script>
  <!-- Vanilla DataTable - https://www.cssscript.com/lightweight-vanilla-data-table-component/ -->
  <link rel="stylesheet" href="public/styles/vanilla-dataTables.min.css">
  <script src="public/javascripts/vanilla-dataTables.min.js"></script>
  <!-- Mine -->
  <link rel="stylesheet" href="public/styles/menu.css">
  <link rel="stylesheet" href="public/styles/button.css">
  <link rel="stylesheet" href="public/styles/general.css">
  <script src="public/javascripts/general.js"></script>
  <title>
    <?=(isset($_SESSION['islogged']) && true === $_SESSION['islogged']) ? $_SESSION['nome'] : 'Software Loja' ?>
  </title>
</head>
<body>
    <script type="text/javascript">
      //VARIABLE INITIALIZATION
      let notifier = new AWN({
        labels: {
          alert: 'Erro',
          confirm: 'Confirmação',
          success: 'Sucesso',
          confirmOk: 'Sim',
          confirmCancel: 'Não'
        }
      }); // where options is an object with your custom values

      var patternForDatatable = {
        sortable: true,
        searchable: true,
        labels: {
          placeholder: "Procurando...", // The search input placeholder
          perPage: "{select} registros por página", // per-page dropdown label
          noRows: "Nenhum registro encontrado", // Message shown when there are no search results
          info: "Mostrando de {start} até {end} de {rows} registros" //
        }
      }
    </script>
    <?php require('menu.php'); ?>
    <?php require('./src/views/'.$dados['pagina'].'.php'); ?>
</body>
</html>