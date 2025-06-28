function initLineChart() {
    if (typeof lineChartLabels === 'undefined' || typeof lineChartData === 'undefined') {
        console.log("En attente des données du graphique en ligne...");
        setTimeout(initLineChart, 100);
        return;
    }

    var canvas = document.getElementById('linechart');
    if (!canvas) {
        console.error("Canvas 'linechart' introuvable");
        return;
    }

    var lineChartCanvas = canvas.getContext('2d');

    var lineChartConfig = {
        type: 'line',
        data: {
            labels: lineChartLabels,
            datasets: [{
                label: 'Nombre de Visites',
                backgroundColor: 'rgba(153, 102, 255, 0.2)',
                borderColor: 'rgba(153, 102, 255, 1)',
                borderWidth: 1,
                fill: false,
                data: lineChartData
            }]
        },
        options: {
            responsive: true,
            scales: {
                x: {
                    type: 'category'
                },
                y: {
                    beginAtZero: true
                }
            }
        }
    };

    new Chart(lineChartCanvas, lineChartConfig);
    console.log("Graphique en ligne initialisé avec succès");
}

document.addEventListener('DOMContentLoaded', initLineChart);