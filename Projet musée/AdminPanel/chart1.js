function initBarChart() {
    if (typeof barChartLabels === 'undefined' || typeof barChartData === 'undefined') {
        console.log("En attente des données du graphique en barres...");
        setTimeout(initBarChart, 100);
        return;
    }

    var canvas = document.getElementById('barchart');
    if (!canvas) {
        console.error("Canvas 'barchart' introuvable");
        return;
    }

    var barChartCanvas = canvas.getContext('2d');

    var barChartConfig = {
        type: 'bar',
        data: {
            labels: barChartLabels,
            datasets: [{
                label: 'Nombre d\'Entrées',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1,
                data: barChartData
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    };

    new Chart(barChartCanvas, barChartConfig);
    console.log("Graphique en barres initialisé avec succès");
}

document.addEventListener('DOMContentLoaded', initBarChart);