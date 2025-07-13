<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<script>
    const scrollbarWidth = window.innerWidth - document.documentElement.clientWidth;
    document.documentElement.style.setProperty('--scrollbar-width', `${scrollbarWidth}px`);

    lucide.createIcons();
</script>
<script>
    const sidebar = document.getElementById('sidebar');
    const toggleButton = document.getElementById('toggleSidebarBtn');

    toggleButton.addEventListener('click', () => {
        sidebar.classList.toggle('w-64');
        sidebar.classList.toggle('w-0');
    });

    function switchTheme(themeName) {
        document.documentElement.setAttribute('data-theme', themeName);
        localStorage.setItem('theme', themeName);
    }
</script>
<script>
    var options = {
        series: [76],
        chart: {
            height: 200,
            type: 'radialBar',
            sparkline: {
                enabled: true
            },
        },
        plotOptions: {
            radialBar: {
                startAngle: -135,
                endAngle: 135,
                hollow: {
                    size: '60%'
                },
                track: {
                    background: "#f3f4f6", // Tailwind gray-100
                },
                dataLabels: {
                    name: {
                        show: false
                    },
                    value: {
                        fontSize: '22px',
                        fontWeight: 'bold',
                        show: true,
                        color: '#000'
                    }
                }
            }
        },
        fill: {
            colors: ['#3b82f6'], // Tailwind blue-500
        },
        labels: ['Progress'],
    };

    var chart = new ApexCharts(document.querySelector("#goal-chart"), options);
    chart.render();
</script>