<?php $__env->startSection('title', 'Dashboard OMD'); ?>
<?php $__env->startSection('header', 'Dashboard OMD'); ?>

<?php $__env->startSection('content'); ?>

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
                <?php for($m = 1; $m <= 12; $m++): ?>
                    <option value="<?php echo e($m); ?>" <?php if($month == $m): echo 'selected'; endif; ?>>
                        <?php echo e(\Carbon\Carbon::create()->month($m)->translatedFormat('F')); ?>

                    </option>
                <?php endfor; ?>
            </select>
        </div>

        <div class="field">
            <label>Tahun</label>

            <input
                type="number"
                name="year"
                value="<?php echo e($year); ?>"
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
                <?php echo e(number_format($totalOrders)); ?>

            </div>
        </div>

        <div class="card">
            <div class="stat-label">Finish</div>
            <div class="stat-value">
                <?php echo e(number_format($finishedOrders)); ?>

            </div>
        </div>

        <div class="card">
            <div class="stat-label">Scrap</div>
            <div class="stat-value">
                <?php echo e(number_format($scrap)); ?>

            </div>
        </div>

        <div class="card">
            <div class="stat-label">Target</div>
            <div class="stat-value">
                <?php echo e(number_format($target)); ?>

            </div>
        </div>

    </div>

    <div class="grid2">

        <div class="card">
            <div class="page-head" style="margin-bottom:8px">
                <div>
                    <b>Trend Order, Finish & Scrap</b>

                    <div class="muted">
                        <?php echo e($year); ?>

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
                <?php echo e($target ? number_format(($finishedOrders / $target) * 100, 1) : 0); ?>%
            </div>
        </div>

    </div>

    <script>
        const labels = <?php echo json_encode($months, 15, 512) ?>;
        const orders = <?php echo json_encode($orderSeries, 15, 512) ?>;
        const finishes = <?php echo json_encode($finishSeries, 15, 512) ?>;
        const scraps = <?php echo json_encode($scrapSeries, 15, 512) ?>;

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
                            <?php echo e($target ? min($finishedOrders, $target) : 0); ?>,
                            <?php echo e($target ? max($target - $finishedOrders, 0) : 0); ?>

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

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\omira\resources\views/omd/dashboard/index.blade.php ENDPATH**/ ?>