document.addEventListener('DOMContentLoaded', function () {
    const canvas = document.getElementById('monthlyExpensesChart');
    const ctx = canvas.getContext('2d');

    const hasData = window.monthly_category_count?.data?.length > 0;

    const { width, height } = canvas.getBoundingClientRect();
    canvas.width = width;
    canvas.height = height;
     ctx.clearRect(0, 0, canvas.width, canvas.height); 

    if (!hasData) { 
        ctx.font = '24px Arial';
        ctx.textBaseline = 'middle';
        ctx.textAlign = 'center';
        ctx.fillStyle = '#f8f6f1';
        ctx.fillText('No data', canvas.width / 2, canvas.height / 2);
    } else {
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: window.monthly_category_count.labels,
                datasets: [{
                    label: 'Number of Categories Used',
                    data: window.monthly_category_count.data,
                    backgroundColor: ['#1c3f3f', '#2c596d', '#4d7989', '#7ab8a0', '#91d4d2'],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: ''
                        }
                    }
                },
                plugins: {
                    title: {
                        display: true,
                        text: 'No. of Categories Used per Month'
                    },
                    legend:{
                        display: false
                    }
                }
            }
        });
    }
});
