<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="styles/menu.css">
  <link rel="stylesheet" href="styles/table.css">
  <link rel="stylesheet" href="styles/button.css">
  <link rel="stylesheet" href="styles/form.css">
  <link rel="stylesheet" href="styles/awesome_notifications.min.css">
  <link rel="stylesheet" href="styles/vanilla-dataTables.min.css">
  <script src="javascripts/menu.js"></script>
  <script src="javascripts/axios.min.js"></script>
  <script src="javascripts/awesome_notifications.min.js"></script>
  <script src="javascripts/vanilla-dataTables.min.js"></script>
  <title>M1 Max</title>
</head>
<body>
    <script type="text/javascript">
      //VARIABLE INITIALIZATION
      let notifier = new AWN({ labels: { alert: 'Erro', confirm: 'Confirmação' }}) // where options is an object with your custom values
    </script>
    <?php require('menu.php'); ?>
    <?php require('./pages/'.$dados['pagina'].'.php'); ?>
</body>
</html>