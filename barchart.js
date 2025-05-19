Chart.register({
    id: 'centerText',
    beforeDraw(chart) {
        const { ctx, width, height } = chart;
        ctx.save();

        
        const fontSize = Math.round(height / 10);
        ctx.font = `bold ${fontSize}px sans-serif`;
        ctx.textBaseline = 'middle';
        ctx.textAlign = 'center';

        const text = ``;
        const textX = width / 2;
        const textY = height / 2 + -20;

        ctx.fillStyle = '#f8f6f1';
        ctx.fillText(text, textX, textY);

        ctx.restore();
    }
});

document.addEventListener('DOMContentLoaded', function() {
    const canvas = document.getElementById('barChart');
    const ctx = canvas.getContext('2d');

    if (window.barChartCategory.data.length === 0) {
        const { width, height } = canvas.getBoundingClientRect();
        canvas.width = width;
        canvas.height = height;

       
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        ctx.font = '24px Arial';
        ctx.textBaseline = 'middle';
        ctx.textAlign = 'center';
        ctx.fillStyle = '#f8f6f1';
        ctx.fillText('No data', canvas.width / 2, canvas.height / 2);
    } else {
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: window.barChartCategory.labels,
                datasets: [{
                    label: 'Expenses per Category',
                    data: window.barChartCategory.data,
                    backgroundColor: [
                        '#1c3f3f','#2c596d','#4d7989','#7ab8a0','#91d4d2'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                indexAxis: 'y',
                plugins: {
                    title: {
                        display: true,
                        text: 'Expenses per Category'
                    },
                    legend: {
                        position: 'bottom',
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(tooltipItem) {
                                return `₱ ${tooltipItem.raw}`;
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        beginAtZero: true,
                    },
                }
            }
        });
    }
});
