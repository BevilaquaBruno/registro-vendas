<?php require_once('./src/general.php'); ?>
<script src="public/javascripts/chart.min.js"></script>
<div class="pure-u-1-3"></div>
<div class="pure-u-1-3">
  <h2>Total de vendas: <span id="valor_total_vendas"></span></h2>
</div>
<div>
  <div class="pure-u-2-5">
    <canvas id="chartCadastros"></canvas>
  </div>
  <div class="pure-u-2-5">
    <canvas id="chartSalarios"></canvas>
  </div>
</div>

<script type="text/javascript">
  var chartCadastros = null;
  var chartSalarios = null;

  const configDoughutCadastros = {
    type: 'doughnut',
    data: {
      labels: [ "Pessoas", "Funcionários", "Usuários", "Produtos", "Clientes" ],
      datasets: [{
        label: 'Cadastros',
        backgroundColor: [
          'rgba(255, 99, 132, 0.2)',
          'rgba(54, 162, 235, 0.2)',
          'rgba(255, 206, 86, 0.2)',
          'rgba(75, 192, 192, 0.2)',
          'rgba(153, 102, 255, 0.2)',
          'rgba(255, 159, 64, 0.2)'
        ],
        borderColor: [
          'rgba(255, 99, 132, 1)',
          'rgba(54, 162, 235, 1)',
          'rgba(255, 206, 86, 1)',
          'rgba(75, 192, 192, 1)',
          'rgba(153, 102, 255, 1)',
          'rgba(255, 159, 64, 1)'
        ],
        data: [
          <?=$dados['quantidade']['pessoas']['total']?>,
          <?=$dados['quantidade']['funcionarios']['total']?>,
          <?=$dados['quantidade']['usuarios']['total']?>,
          <?=$dados['quantidade']['produtos']['total']?>,
          <?=$dados['quantidade']['clientes']['total']?>
        ]
      }]
    },
    options: {
      responsive: true,
      plugins: {
        legend: {
          position: 'top',
        },
        title: {
          display: true,
          text: 'Quantidade de cadastros'
        }
      }
    },
  };

  const configBarSalarios = {
    type: 'bar',
    data: {
      labels: <?=json_encode($dados['media_salarial']['data'])?>,
      datasets: [
        {
          type: 'line',
          label: 'Média',
          backgroundColor: 'rgb(0, 0, 0)',
          borderColor: 'rgb(0, 0, 0)',
          data: [<?=str_repeat($dados['media_salarial']['media'].', ', count($dados['media_salarial']['data']))?>],
        },{
          label: 'Salário',
          backgroundColor: 'rgb(255, 99, 132)',
          borderColor: 'rgb(255, 99, 132)',
          data: <?=json_encode($dados['media_salarial']['data'])?>,
        }
      ]
    },
    options: {
      plugins: {
        title: {
          display: true,
          text: 'Salários acima e abaixo da média'
        }
      }
    }
  };

  function generateCharts() {
    chartCadastros = new Chart(
      document.getElementById('chartCadastros'),
      configDoughutCadastros
    );

    chartSalarios = new Chart(
      document.getElementById('chartSalarios'),
      configBarSalarios
    );
  }

  window.addEventListener("load", function () {
    generateCharts();
    document.getElementById('valor_total_vendas').innerText = returnBrazilianCurrency('<?=$dados['vendas']['total']?>');
  });
</script>