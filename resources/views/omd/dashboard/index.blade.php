@extends('layouts.app')

@section('title', 'Dashboard OMD')
@section('header', 'Dashboard OMD')

@section('content')

    <div class="page-head">
        <div>
            <h2>Monitoring Repair Box</h2>

            <div class="muted">
            </div>
        </div>
    </div>

    <form class="filter" method="GET">
        <div class="field">
            <label>Bulan</label>

            <select name="month">
                @for ($m = 1; $m <= 12; $m++)
                    <option value="{{ $m }}" @selected($month == $m)>
                        {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                    </option>
                @endfor
            </select>
        </div>

        <div class="field">
            <label>Tahun</label>

            <input
                type="number"
                name="year"
                value="{{ $year }}"
            >
        </div>

        <button class="btn btn-primary">
            Terapkan
        </button>
    </form>

    <div class="cards">

        <div class="card">
            <div class="stat-label">Total Order</div>
            <div class="stat-value">
                {{ number_format($totalOrders) }}
            </div>
        </div>

        <div class="card">
            <div class="stat-label">Finish</div>
            <div class="stat-value">
                {{ number_format($finishedOrders) }}
            </div>
        </div>

        <div class="card">
            <div class="stat-label">Scrap</div>
            <div class="stat-value">
                {{ number_format($scrap) }}
            </div>
        </div>

        <div class="card">
            <div class="stat-label">Target</div>
            <div class="stat-value">
                {{ number_format($target) }}
            </div>
        </div>

    </div>

    <div class="grid2">

        <div class="card">
            <div class="page-head" style="margin-bottom:8px">
                <div>
                    <b>Trend Order, Finish & Scrap</b>

                    <div class="muted">
                        {{ $year }}
                    </div>
                </div>
            </div>

            <div class="chartbox">
                <canvas id="trendChart"></canvas>
            </div>
        </div>

        <div class="card">
            <b>Progress terhadap Target</b>

            <div class="chartbox" style="height:260px">
                <canvas id="targetChart"></canvas>
            </div>

            <div style="text-align:center;font-weight:800;font-size:24px">
                {{ $target ? number_format(($finishedOrders / $target) * 100, 1) : 0 }}%
            </div>
        </div>

    </div>

    <script>
        const labels = @json($months);
        const orders = @json($orderSeries);
        const finishes = @json($finishSeries);
        const scraps = @json($scrapSeries);

        new Chart(document.getElementById('trendChart'), {
            type: 'line',

            data: {
                labels,

                datasets: [
                    {
                        label: 'Order',
                        data: orders,
                        tension: .35
                    },
                    {
                        label: 'Finish',
                        data: finishes,
                        tension: .35
                    },
                    {
                        label: 'Scrap',
                        data: scraps,
                        tension: .35
                    }
                ]
            },

            options: {
                responsive: true,
                maintainAspectRatio: false,

                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                },

                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        new Chart(document.getElementById('targetChart'), {
            type: 'doughnut',

            data: {
                labels: ['Finish', 'Sisa'],

                datasets: [
                    {
                        data: [
                            {{ $target ? min($finishedOrders, $target) : 0 }},
                            {{ $target ? max($target - $finishedOrders, 0) : 0 }}
                        ]
                    }
                ]
            },

            options: {
                responsive: true,
                maintainAspectRatio: false,

                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    </script>

@endsection