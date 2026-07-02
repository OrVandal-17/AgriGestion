
  const ctx = document.getElementById('monDonut').getContext('2d');
  const config = {
    type: 'pie',
    data: data,
    }
  
  new Chart(ctx, {
    type: 'doughnut', // Type de graphique
    data: {
      labels: ['Rouge', 'Bleu', 'Jaune'],
      datasets: [{
        label: 'Mes Votes',
        data:[300, 50, 100], // Vos données
        backgroundColor: [
          'rgba(255, 99, 132, 0.8)',
          'rgba(54, 162, 235, 0.8)',
          'rgba(255, 206, 86, 0.8)'
        ],
        hoverOffset: 4
      }]
    },
    options: {
      responsive: true,
      plugins: {
        legend: {
          position: '',
        }
      }
    }
  
  });


