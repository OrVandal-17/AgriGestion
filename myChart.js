const ctx = document.getElementById('myChart').getContext('2d');

                new Chart(ctx, {
              type: 'bar',
                data: {
                 labels: ['Sorgoh', 'Maïs', 'blé', 'Riz', 'Manioc', 'Arachide', 'coton'],
                 datasets: [{
                     label: '# Récolte par culture',
                     data: [12, 19, 3, 14, 2, 3, 6,],
                        borderWidth: 2
                  }]
                
                },
                options: {
                     scales: {
                              y: {
                             beginAtZero: true
                              }

                            }
                     }
                });