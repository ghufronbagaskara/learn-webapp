<div class="grid grid-cols-1 gap-4 lg:grid-cols-2">
    <div class="card bg-base-100 shadow">
        <div class="card-body">
            <h2 class="card-title">Jumlah Penduduk per Pekerjaan</h2>
            <div class="h-80" wire:ignore>
                <canvas id="penduduk-pekerjaan-chart"></canvas>
            </div>
        </div>
    </div>

    <div class="card bg-base-100 shadow">
        <div class="card-body">
            <h2 class="card-title">Distribusi Kelompok Usia</h2>
            <div class="h-80" wire:ignore>
                <canvas id="penduduk-usia-chart"></canvas>
            </div>
        </div>
    </div>

    @script
        <script>
            (() => {
                const pekerjaanLabels = @js($pekerjaanLabels);
                const pekerjaanTotals = @js($pekerjaanTotals);
                const usiaLabels = @js($usiaLabels);
                const usiaTotals = @js($usiaTotals);

                window.pendudukCharts = window.pendudukCharts || {};

                const buildChart = (key, canvasId, config) => {
                    if (window.pendudukCharts[key]) {
                        window.pendudukCharts[key].destroy();
                    }

                    const canvas = document.getElementById(canvasId);

                    if (!canvas) {
                        return;
                    }

                    window.pendudukCharts[key] = new Chart(canvas, config);
                };

                buildChart('pekerjaan', 'penduduk-pekerjaan-chart', {
                    type: 'bar',
                    data: {
                        labels: pekerjaanLabels,
                        datasets: [
                            {
                                label: 'Jumlah Penduduk',
                                data: pekerjaanTotals,
                                backgroundColor: '#2563eb',
                                borderRadius: 6,
                            },
                        ],
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    precision: 0,
                                },
                            },
                        },
                    },
                });

                buildChart('usia', 'penduduk-usia-chart', {
                    type: 'doughnut',
                    data: {
                        labels: usiaLabels,
                        datasets: [
                            {
                                data: usiaTotals,
                                backgroundColor: ['#0ea5e9', '#22c55e', '#f59e0b', '#ef4444'],
                            },
                        ],
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                    },
                });
            })();
        </script>
    @endscript
</div>
