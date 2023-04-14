<div class="w-[960px] mx-auto pt-8">
    <h1 class="text-3xl font-bold mb-4">Campageoverzicht</h1>
    <div class="grid grid-cols-3 gap-4">
        <div class="col-span-2">
            <h1 class="text-xl font-bold mb-4">"Heatmap"</h1>
            <div class="aspect-square relative">
                <canvas wire:ignore id="canvas" class="absolute inset-0"></canvas>
            </div>

            <div class="flex justify-center gap-4 align-items-center">
                <span class="text-sm text-slate-400">{{ reset($weeks) }}</span>
                <div>
                    <input type="range" wire:model="week" min="0" max="{{ count($weeks) - 1 }}" value="{{$week}}">
                    <span class="block text-center">{{ $weeks[$week] }}</span>
                </div>
                <span class="text-sm text-slate-400">{{ last($weeks) }}</span>
            </div>
        </div>

        <div>
            <h1 class="text-xl font-bold mb-4">Statistieken voor
                <select wire:model="location">
                    <option value="0">Heel Nederland</option>
                    @foreach($locations as $location)
                        <option value="{{ $location->id }}">{{ $location->name }}</option>
                    @endforeach
                </select>
            </h1>

            <table>
                <thead>
                <tr>
                    <th>Product</th>
                    <th>Aantal</th>
                    @if($location)
                        <th>Heel Nederland</th>
                    @endif
                </tr>
                </thead>
                <tbody>
                @foreach($productStats as $stat)
                    <tr>
                        <td>{{ $stat['product'] }}</td>
                        <td>{{ $stat['value'] }}</td>
                        @if($location)
                            <td>{{ $stat['total'] }}</td>
                        @endif
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

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
                        aspectRatio: 1,
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
</div>
