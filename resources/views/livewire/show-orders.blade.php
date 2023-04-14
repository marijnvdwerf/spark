<div>
    <select wire:model="location">
        <option value="0">Nederland</option>
        @foreach($locations as $location)
            <option value="{{ $location->id }}">{{ $location->name }}</option>
        @endforeach
    </select>

    <canvas wire:ignore id="canvas"></canvas>

    <script src="https://cdn.jsdelivr.net/npm/chart.js@~4.1.1"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@next"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-chart-geo@4.1.2/build/index.umd.min.js"></script>
    <script>
        document.addEventListener('livewire:load', function () {
            let chart;

            Chart.register(ChartDataLabels);
            Promise.all([
                fetch('https://raw.githubusercontent.com/markmarkoh/datamaps/master/src/js/data/nld.topo.json').then((r) => r.json()),
            ]).then(([nl]) => {
                const provinces = ChartGeo.topojson.feature(nl, nl.objects.nld).features;
                // Remove BES islands
                provinces.splice(5, 3);

                data = @js($stats);

                chart = new Chart(document.getElementById('canvas').getContext('2d'), {
                    type: 'bubbleMap',
                    data: {
                        datasets: [
                            {
                                outline: provinces,
                                showOutline: true,
                                backgroundColor: 'steelblue',
                                data: data,
                            },
                        ],
                    },
                    options: {
                        plugins: {
                            legend: {
                                display: false,
                            },
                            datalabels: {
                                align: 'top',
                                formatter: (v) => {
                                    return v.description;
                                },
                            },
                        },
                        scales: {
                            projection: {
                                axis: 'x',
                                projection: 'mercator',
                                padding: 10,
                            },
                            size: {
                                axis: 'x',
                                range: [0, 20],
                                ticks: {
                                    display: true,
                                },
                            },
                        },
                    },
                });
            });

            Livewire.on('updateChart', data => {
                chart.data.datasets[0].data = data;
                chart.update();
            });
        });
    </script>
    <input type="range" wire:model="week" min="0" max="{{ count($weeks) - 1 }}" value="{{$week}}">
    <span>{{ $weeks[$week] }}</span>
</div>
