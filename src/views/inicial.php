<script src="public/javascripts/chart.min.js"></script>
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
      labels: [ "Pessoas", "Funcionários" ],
      datasets: [{
        label: 'Cadastros',
        backgroundColor: [
          'rgba(255, 99, 132, 0.2)',
          'rgba(54, 162, 235, 0.2)'
        ],
        borderColor: [
          'rgba(255, 99, 132, 1)',
          'rgba(54, 162, 235, 1)'
        ],
        data: [<?=$dados['quantidade']['pessoas']['total']?>, <?=$dados['quantidade']['funcionarios']['total']?>]
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
  });
</script>