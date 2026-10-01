<?php $__env->startSection('title', 'Dashboard OMD'); ?>
<?php $__env->startSection('header', 'Dashboard OMD'); ?>

<?php $__env->startSection('content'); ?>

    <style>
        .omd-dashboard {
            display: flex;
            flex-direction: column;
            gap: 18px;
        }

        .page-head {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 16px;
        }

        .page-head h2 {
            margin: 0 0 5px;
            font-size: 22px;
            font-weight: 800;
            color: #172033;
        }

        .page-head .muted {
            color: #64748b;
            font-size: 12px;
        }

        .filter {
            display: flex;
            align-items: flex-end;
            gap: 12px;
            padding: 16px;
            background: #fff;
            border: 1px solid #e8edf4;
            border-radius: 14px;
            box-shadow: 0 8px 25px rgba(15, 23, 42, .04);
        }

        .field {
            display: flex;
            flex-direction: column;
            gap: 6px;
            min-width: 150px;
        }

        .field label {
            font-size: 10px;
            font-weight: 800;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: .04em;
        }

        .field select,
        .field input {
            height: 38px;
            padding: 0 11px;
            border: 1px solid #dbe2ea;
            border-radius: 9px;
            background: #fff;
            color: #334155;
            font-size: 12px;
            outline: none;
        }

        .field select:focus,
        .field input:focus {
            border-color: #7c3aed;
            box-shadow: 0 0 0 3px rgba(124, 58, 237, .08);
        }

        .btn {
            height: 38px;
            padding: 0 15px;
            border: none;
            border-radius: 9px;
            font-size: 11px;
            font-weight: 800;
            cursor: pointer;
        }

        .btn-primary {
            background: #5b4ce6;
            color: #fff;
        }

        .btn-primary:hover {
            background: #4f46d7;
        }

        .scrap-warning {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 14px 16px;
            border: 1px solid #fecaca;
            border-radius: 14px;
            background: #fff1f2;
            box-shadow: 0 8px 25px rgba(220, 38, 38, .06);
        }

        .scrap-warning-icon {
            width: 38px;
            height: 38px;
            flex: 0 0 38px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            background: #fee2e2;
            color: #dc2626;
            font-size: 20px;
            font-weight: 900;
        }

        .scrap-warning-title {
            margin: 0 0 3px;
            color: #991b1b;
            font-size: 12px;
            font-weight: 900;
        }

        .scrap-warning-text {
            margin: 0;
            color: #b91c1c;
            font-size: 11px;
            line-height: 1.5;
        }

        .cards {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 14px;
        }

        .card {
            background: #fff;
            border: 1px solid #e8edf4;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(15, 23, 42, .05);
        }

        .stat-card {
            padding: 17px;
            min-height: 112px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .stat-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
        }

        .stat-label {
            font-size: 10px;
            font-weight: 800;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: .04em;
        }

        .stat-icon {
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 9px;
            font-size: 14px;
            font-weight: 900;
        }

        .stat-icon.order {
            background: #eef2ff;
            color: #4f46e5;
        }

        .stat-icon.finish {
            background: #ecfdf3;
            color: #16a34a;
        }

        .stat-icon.scrap {
            background: #fff1f2;
            color: #dc2626;
        }

        .stat-icon.target {
            background: #fff7ed;
            color: #ea580c;
        }

        .stat-value {
            margin-top: 10px;
            color: #172033;
            font-size: 25px;
            font-weight: 900;
            line-height: 1;
        }

        .stat-note {
            margin-top: 7px;
            color: #94a3b8;
            font-size: 9px;
        }

        .dashboard-card {
            padding: 18px;
        }

        .card-head {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 15px;
            margin-bottom: 15px;
        }

        .card-title {
            margin: 0 0 4px;
            color: #172033;
            font-size: 14px;
            font-weight: 900;
        }

        .card-subtitle {
            margin: 0;
            color: #94a3b8;
            font-size: 10px;
        }

        .target-pill {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 28px;
            padding: 0 10px;
            border-radius: 999px;
            background: #fff7ed;
            color: #c2410c;
            font-size: 10px;
            font-weight: 900;
            white-space: nowrap;
        }

        .line-pill {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 28px;
            padding: 0 10px;
            border-radius: 999px;
            background: #eef2ff;
            color: #4f46e5;
            font-size: 10px;
            font-weight: 900;
            white-space: nowrap;
        }

        .head-pills {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 8px;
            flex-wrap: wrap;
        }

        .chartbox {
            position: relative;
            width: 100%;
            height: 360px;
        }

        .grid2 {
            display: grid;
            grid-template-columns: minmax(0, 2fr) minmax(320px, 1fr);
            gap: 18px;
        }

        .progress-wrap {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 280px;
            flex-direction: column;
            gap: 12px;
        }

        .progress-percent {
            color: #172033;
            font-size: 30px;
            font-weight: 900;
        }

        .progress-caption {
            color: #64748b;
            font-size: 10px;
            text-align: center;
        }

        .summary-table-wrap {
            overflow-x: auto;
        }

        .summary-table {
            width: 100%;
            min-width: 760px;
            border-collapse: separate;
            border-spacing: 0;
        }

        .summary-table th,
        .summary-table td {
            padding: 9px 10px;
            border-right: 1px solid #e2e8f0;
            border-bottom: 1px solid #e2e8f0;
            text-align: center;
            white-space: nowrap;
            font-size: 10px;
        }

        .summary-table th:first-child,
        .summary-table td:first-child {
            text-align: left;
            position: sticky;
            left: 0;
            z-index: 1;
            background: #fff;
        }

        .summary-table thead th {
            background: #f8fafc;
            color: #475569;
            font-weight: 900;
        }

        .summary-table tbody td {
            color: #334155;
            font-weight: 700;
        }

        .summary-table .row-target td {
            background: #fff7ed;
            color: #c2410c;
        }

        .summary-table .row-order td:first-child {
            color: #4f46e5;
        }

        .summary-table .row-finish td:first-child {
            color: #16a34a;
        }

        .summary-table .row-scrap td:first-child {
            color: #dc2626;
        }

        .summary-table tr th:last-child,
        .summary-table tr td:last-child {
            border-right: none;
        }

        .summary-table tr:last-child td {
            border-bottom: none;
        }

        .scrap-cell-alert {
            background: #fee2e2 !important;
            color: #b91c1c !important;
            font-weight: 900 !important;
        }

        .abnormality-box {
            display: flex;
            align-items: flex-start;
            gap: 14px;
            padding: 14px 16px;
            border: 1px solid #fed7aa;
            border-radius: 14px;
            background: #fff7ed;
            box-shadow: 0 8px 25px rgba(234, 88, 12, .06);
        }

        .abnormality-icon {
            width: 38px;
            height: 38px;
            flex: 0 0 38px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            background: #ffedd5;
            color: #ea580c;
            font-size: 18px;
            font-weight: 900;
        }

        .abnormality-title {
            margin: 0 0 5px;
            color: #9a3412;
            font-size: 12px;
            font-weight: 900;
        }

        .abnormality-text {
            margin: 0;
            color: #c2410c;
            font-size: 11px;
            line-height: 1.6;
        }

        .abnormality-item {
            display: block;
            margin-bottom: 3px;
        }

        .abnormality-item:last-child {
            margin-bottom: 0;
        }

        .no-target-box {
            padding: 14px 16px;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            background: #f8fafc;
            color: #64748b;
            font-size: 11px;
            line-height: 1.6;
        }

        .no-target-box strong {
            color: #334155;
        }

        @media (max-width: 1050px) {
            .cards {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .grid2 {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 700px) {
            .filter {
                flex-direction: column;
                align-items: stretch;
            }

            .field {
                min-width: 0;
            }

            .cards {
                grid-template-columns: 1fr;
            }

            .page-head {
                flex-direction: column;
            }

            .head-pills {
                justify-content: flex-start;
            }
        }
    </style>

    <?php
        /*
        |--------------------------------------------------------------------------
        | NAMA BULAN
        |--------------------------------------------------------------------------
        */

        $monthNames = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember',
        ];

        /*
        |--------------------------------------------------------------------------
        | LINE TERPILIH
        |--------------------------------------------------------------------------
        */

        $selectedLine = $lines->firstWhere('id', $lineId);

        /*
        |--------------------------------------------------------------------------
        | ABNORMALITY
        |--------------------------------------------------------------------------
        |
        | Muncul apabila Order / Finish / Scrap melebihi target bulan terpilih.
        |
        */

        $abnormalities = [];

        if ($target > 0 && $totalOrders > $target) {
            $abnormalities[] =
                'Order sebesar ' .
                number_format($totalOrders) .
                ' box melebihi target ' .
                number_format($target) .
                ' box sebanyak ' .
                number_format($totalOrders - $target) .
                ' box.';
        }

        if ($target > 0 && $finishedOrders > $target) {
            $abnormalities[] =
                'Finish sebesar ' .
                number_format($finishedOrders) .
                ' box melebihi target ' .
                number_format($target) .
                ' box sebanyak ' .
                number_format($finishedOrders - $target) .
                ' box.';
        }

        if ($target > 0 && $scrap > $target) {
            $abnormalities[] =
                'Scrap sebesar ' .
                number_format($scrap) .
                ' box berada di atas target ' .
                number_format($target) .
                ' box sebanyak ' .
                number_format($scrap - $target) .
                ' box.';
        }

        /*
        |--------------------------------------------------------------------------
        | SCRAP WARNING
        |--------------------------------------------------------------------------
        */

        if ($scrapWarning) {
            $abnormalities[] = 'Scrap mencapai batas peringatan sebesar ' . number_format($scrapLimit) . ' box.';
        }

        $abnormalityExists = count($abnormalities) > 0;

        /*
        |--------------------------------------------------------------------------
        | PERSENTASE FINISH
        |--------------------------------------------------------------------------
        */

        $finishPercentage = $target > 0 ? ($finishedOrders / $target) * 100 : 0;

        /*
        |--------------------------------------------------------------------------
        | DATA FISCAL YEAR UNTUK INFO
        |--------------------------------------------------------------------------
        */

        $fiscalEndYear = $year + 1;
    ?>

    <div class="omd-dashboard">

        
        
        

        <div class="page-head">

            <div>
                <h2>
                    Monitoring Repair Box
                </h2>

                <div class="muted">
                    Summary Repair Box bulanan dan monitoring pencapaian target.
                </div>
            </div>

            <div class="head-pills">

                <?php if($selectedLine): ?>
                    <div class="line-pill">
                        Line: <?php echo e($selectedLine->name); ?>

                    </div>
                <?php else: ?>
                    <div class="line-pill">
                        Semua Line
                    </div>
                <?php endif; ?>

                <div class="target-pill">
                    FY <?php echo e($year); ?> / Apr <?php echo e($year); ?> - Mar <?php echo e($fiscalEndYear); ?>

                </div>

            </div>

        </div>


        
        
        

        <?php if($scrapWarning): ?>
            <div class="scrap-warning">

                <div class="scrap-warning-icon">
                    !
                </div>

                <div>

                    <p class="scrap-warning-title">
                        Peringatan Scrap
                    </p>

                    <p class="scrap-warning-text">
                        Total Scrap pada
                        <strong>
                            <?php echo e($monthNames[$month]); ?>

                        </strong>
                        <?php echo e($selectedDataYear ?? ($month <= 3 ? $year + 1 : $year)); ?>

                        mencapai
                        <strong><?php echo e(number_format($scrap)); ?></strong>
                        box.

                        Batas Scrap:
                        <strong>
                            <?php echo e(number_format($scrapLimit)); ?> box
                        </strong>.
                    </p>

                </div>

            </div>
        <?php endif; ?>


        
        
        

        <?php if($abnormalityExists): ?>
            <div class="abnormality-box">

                <div class="abnormality-icon">
                    !
                </div>

                <div style="flex: 1;">

                    <p class="abnormality-title">
                        ABNORMALITY!!
                    </p>

                    <div class="abnormality-text">

                        <?php $__currentLoopData = $abnormalities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $reason): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <span class="abnormality-item">
                                • <?php echo e($reason); ?>

                            </span>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                    </div>

                </div>

            </div>
        <?php endif; ?>


        
        
        

        <form class="filter" method="GET" action="<?php echo e(request()->url()); ?>">

            <div class="field">

                <label>
                    Bulan
                </label>

                <select name="month">

                    <?php for($m = 1; $m <= 12; $m++): ?>
                        <option value="<?php echo e($m); ?>" <?php if($month == $m): echo 'selected'; endif; ?>>
                            <?php echo e($monthNames[$m]); ?>

                        </option>
                    <?php endfor; ?>

                </select>

            </div>


            <div class="field">

                <label>
                    Tahun FY
                </label>

                <input type="number" name="year" value="<?php echo e($year); ?>" min="2020" max="2100">

            </div>


            <div class="field">

                <label>
                    Line
                </label>

                <select name="line_id">

                    <option value="">
                        Semua Line
                    </option>

                    <?php $__currentLoopData = $lines; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $line): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($line->id); ?>" <?php if((string) $lineId === (string) $line->id): echo 'selected'; endif; ?>>
                            <?php echo e($line->name); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                </select>

            </div>


            <button type="submit" class="btn btn-primary">
                Terapkan
            </button>

        </form>


        
        
        

        <div class="cards">

            
            <div class="card stat-card">

                <div class="stat-top">

                    <div class="stat-label">
                        Total Order
                    </div>

                    <div class="stat-icon order">
                        O
                    </div>

                </div>

                <div class="stat-value">
                    <?php echo e(number_format($totalOrders)); ?>

                </div>

                <div class="stat-note">

                    Grand Total NG
                    <?php echo e($monthNames[$month]); ?>


                    <?php if($selectedLine): ?>
                        • <?php echo e($selectedLine->name); ?>

                    <?php endif; ?>

                </div>

            </div>


            
            <div class="card stat-card">

                <div class="stat-top">

                    <div class="stat-label">
                        Finish
                    </div>

                    <div class="stat-icon finish">
                        ✓
                    </div>

                </div>

                <div class="stat-value">
                    <?php echo e(number_format($finishedOrders)); ?>

                </div>

                <div class="stat-note">
                    Total OK hasil repair
                </div>

            </div>


            
            <div class="card stat-card">

                <div class="stat-top">

                    <div class="stat-label">
                        Scrap
                    </div>

                    <div class="stat-icon scrap">
                        !
                    </div>

                </div>

                <div class="stat-value">
                    <?php echo e(number_format($scrap)); ?>

                </div>

                <div class="stat-note">
                    Ambang peringatan:
                    <?php echo e(number_format($scrapLimit)); ?>

                </div>

            </div>


            
            <div class="card stat-card">

                <div class="stat-top">

                    <div class="stat-label">
                        Target
                    </div>

                    <div class="stat-icon target">
                        T
                    </div>

                </div>

                <div class="stat-value">
                    <?php echo e(number_format($target)); ?>

                </div>

                <div class="stat-note">

                    Target
                    <?php echo e($monthNames[$month]); ?>

                    FY <?php echo e($year); ?>


                    <?php if($selectedLine): ?>
                        • <?php echo e($selectedLine->name); ?>

                    <?php endif; ?>

                </div>

            </div>

        </div>


        
        
        

        <div class="card dashboard-card">

            <div class="card-head">

                <div>

                    <h3 class="card-title">
                        SUMMARY REPAIR BOX BULANAN
                    </h3>

                    <p class="card-subtitle">
                        Order, Finish, Scrap, dan garis Target FY <?php echo e($year); ?>

                    </p>

                </div>

                <div class="head-pills">

                    <?php if($selectedLine): ?>
                        <div class="line-pill">
                            <?php echo e($selectedLine->name); ?>

                        </div>
                    <?php endif; ?>

                    <div class="target-pill">
                        Target <?php echo e(number_format($target)); ?>

                        / <?php echo e($monthNames[$month]); ?>

                    </div>

                </div>

            </div>

            <div class="chartbox">

                <canvas id="trendChart"></canvas>

            </div>

        </div>


        
        
        

        <div class="grid2">

            
            
            

            <div class="card dashboard-card">

                <div class="card-head">

                    <div>

                        <h3 class="card-title">
                            Rekap Bulanan
                        </h3>

                        <p class="card-subtitle">
                            Angka yang menjadi sumber grafik dashboard.
                        </p>

                    </div>

                </div>


                <div class="summary-table-wrap">

                    <table class="summary-table">

                        <thead>

                            <tr>

                                <th>
                                    Summary
                                </th>

                                <?php $__currentLoopData = $months; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $monthLabel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <th>
                                        <?php echo e($monthLabel); ?>

                                    </th>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                            </tr>

                        </thead>


                        <tbody>

                            
                            <tr class="row-target">

                                <td>
                                    Target FY <?php echo e($year); ?>

                                </td>

                                <?php $__currentLoopData = $targetSeries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <td>
                                        <?php echo e(number_format($value)); ?>

                                    </td>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                            </tr>


                            
                            <tr class="row-order">

                                <td>
                                    Order
                                </td>

                                <?php $__currentLoopData = $orderSeries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <td>
                                        <?php echo e(number_format($value)); ?>

                                    </td>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                            </tr>


                            
                            <tr class="row-finish">

                                <td>
                                    Finish
                                </td>

                                <?php $__currentLoopData = $finishSeries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <td>
                                        <?php echo e(number_format($value)); ?>

                                    </td>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                            </tr>


                            
                            <tr class="row-scrap">

                                <td>
                                    Scrap
                                </td>

                                <?php $__currentLoopData = $scrapSeries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php
                                        $monthlyLimit = $scrapLimitSeries[$index] ?? 20;
                                    ?>

                                    <td class="<?php echo e($value >= $monthlyLimit ? 'scrap-cell-alert' : ''); ?>">
                                        <?php echo e(number_format($value)); ?>

                                    </td>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                            </tr>

                        </tbody>

                    </table>

                </div>

            </div>


            
            
            

            <div class="card dashboard-card">

                <div class="card-head">

                    <div>

                        <h3 class="card-title">
                            Progress terhadap Target
                        </h3>

                        <p class="card-subtitle">
                            Perbandingan Finish dengan target bulan terpilih.
                        </p>

                    </div>

                </div>


                <div class="progress-wrap">

                    <div class="chartbox" style="height:220px;width:100%;">

                        <canvas id="targetChart"></canvas>

                    </div>


                    <div class="progress-percent">

                        <?php echo e(number_format($finishPercentage, 1)); ?>%

                    </div>


                    <div class="progress-caption">

                        <?php echo e(number_format($finishedOrders)); ?>


                        Finish dari target

                        <?php echo e(number_format($target)); ?>


                    </div>

                </div>

            </div>

        </div>


        
        
        

        <?php if(!$lineId): ?>
            <div class="no-target-box">

                Dashboard sedang menampilkan data
                <strong>Semua Line</strong>.

                Pilih Line tertentu untuk melihat
                target berdasarkan Line.

            </div>
        <?php elseif($target <= 0): ?>
            <div class="no-target-box">

                Target untuk
                <strong><?php echo e($selectedLine?->name); ?></strong>
                pada bulan
                <strong><?php echo e($monthNames[$month]); ?></strong>
                FY <?php echo e($year); ?>

                belum diatur.

                Silakan isi melalui halaman
                <strong>Pengaturan Target</strong>.

            </div>
        <?php endif; ?>

    </div>


    
    
    

    <script>
        /*
            |--------------------------------------------------------------------------
            | DATA DARI CONTROLLER
            |--------------------------------------------------------------------------
            */

        const labels = <?php echo json_encode($months, 15, 512) ?>;

        const orders = <?php echo json_encode($orderSeries, 15, 512) ?>;

        const finishes = <?php echo json_encode($finishSeries, 15, 512) ?>;

        const scraps = <?php echo json_encode($scrapSeries, 15, 512) ?>;

        const targets = <?php echo json_encode($targetSeries, 15, 512) ?>;


        /*
        |--------------------------------------------------------------------------
        | TREND CHART
        |--------------------------------------------------------------------------
        */

        new Chart(
            document.getElementById('trendChart'), {
                data: {

                    labels: labels,

                    datasets: [

                        {
                            type: 'bar',

                            label: 'Order',

                            data: orders,

                            backgroundColor: 'rgba(79, 70, 229, .78)',

                            borderRadius: 5,

                            maxBarThickness: 28
                        },

                        {
                            type: 'bar',

                            label: 'Finish',

                            data: finishes,

                            backgroundColor: 'rgba(22, 163, 74, .78)',

                            borderRadius: 5,

                            maxBarThickness: 28
                        },

                        {
                            type: 'bar',

                            label: 'Scrap',

                            data: scraps,

                            backgroundColor: 'rgba(220, 38, 38, .78)',

                            borderRadius: 5,

                            maxBarThickness: 28
                        },

                        {
                            type: 'line',

                            label: 'Target FY <?php echo e($year); ?>',

                            data: targets,

                            borderColor: '#f97316',

                            backgroundColor: '#f97316',

                            borderWidth: 3,

                            pointRadius: 3,

                            pointHoverRadius: 5,

                            tension: 0,

                            fill: false
                        }

                    ]
                },


                options: {

                    responsive: true,

                    maintainAspectRatio: false,


                    interaction: {

                        mode: 'index',

                        intersect: false

                    },


                    plugins: {

                        legend: {

                            position: 'bottom',

                            labels: {

                                usePointStyle: true,

                                padding: 18

                            }

                        },


                        tooltip: {

                            callbacks: {

                                label: function(context) {

                                    return (
                                        context.dataset.label +
                                        ': ' +
                                        new Intl.NumberFormat(
                                            'id-ID'
                                        ).format(
                                            context.parsed.y
                                        )
                                    );

                                }

                            }

                        }

                    },


                    scales: {

                        x: {

                            grid: {

                                display: false

                            }

                        },


                        y: {

                            beginAtZero: true,

                            ticks: {

                                precision: 0

                            }

                        }

                    }

                }

            }
        );


        /*
        |--------------------------------------------------------------------------
        | TARGET PROGRESS CHART
        |--------------------------------------------------------------------------
        */

        const finishValue =
            <?php echo e($target > 0 ? min($finishedOrders, $target) : 0); ?>;

        const remainingValue =
            <?php echo e($target > 0 ? max($target - $finishedOrders, 0) : 0); ?>;


        new Chart(
            document.getElementById('targetChart'), {
                type: 'doughnut',

                data: {

                    labels: [
                        'Finish',
                        'Sisa Target'
                    ],

                    datasets: [

                        {

                            data: [
                                finishValue,
                                remainingValue
                            ],

                            backgroundColor: [
                                '#16a34a',
                                '#e2e8f0'
                            ],

                            borderWidth: 0

                        }

                    ]

                },


                options: {

                    responsive: true,

                    maintainAspectRatio: false,

                    cutout: '72%',


                    plugins: {

                        legend: {

                            position: 'bottom',

                            labels: {

                                usePointStyle: true,

                                padding: 15

                            }

                        }

                    }

                }

            }
        );
    </script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\omira\resources\views/omd/dashboard/index.blade.php ENDPATH**/ ?>