<?php $__env->startSection('title', 'Manajemen Akun'); ?>
<?php $__env->startSection('header', 'Manajemen Akun'); ?>

<?php $__env->startSection('content'); ?>

    <div class="page-head">
        <div>
            <h2>Data Akun User</h2>
            <div class="muted">
                Kelola akun user berdasarkan Plant dan Line.
            </div>
        </div>

        <a href="<?php echo e(route('omd.users.create')); ?>" class="btn btn-primary">
            + Tambah Akun
        </a>
    </div>

    <div class="card">

        <div class="table-wrap">

            <table class="table">

                <thead>
                    <tr>
                        <th style="width:70px;">No</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th style="width:150px;">Plant</th>
                        <th style="width:160px;">Line</th>
                        <th style="width:160px;">Aksi</th>
                    </tr>
                </thead>

                <tbody>

                    <?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>

                        <tr>

                            <td>
                                <?php echo e($index + 1); ?>

                            </td>

                            <td>
                                <b><?php echo e($user->name); ?></b>
                            </td>

                            <td>
                                <?php echo e($user->email); ?>

                            </td>

                            <td>
                                <span class="badge">
                                    <?php echo e($user->line?->plant?->name ?? '-'); ?>

                                </span>
                            </td>

                            <td>
                                <span class="badge">
                                    <?php echo e($user->line?->name ?? '-'); ?>

                                </span>
                            </td>

                            <td>

                                <div style="display:flex; gap:8px;">

                                    <a href="<?php echo e(route('omd.users.edit', $user)); ?>"
                                        class="btn btn-secondary">
                                        Edit
                                    </a>

                                    <form method="POST"
                                        action="<?php echo e(route('omd.users.destroy', $user)); ?>"
                                        onsubmit="return confirm('Hapus akun ini?')">

                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>

                                        <button type="submit" class="btn btn-danger">
                                            Hapus
                                        </button>

                                    </form>

                                </div>

                            </td>

                        </tr>

                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>

                        <tr>
                            <td colspan="6" class="empty">
                                Belum ada akun user.
                            </td>
                        </tr>

                    <?php endif; ?>

                </tbody>

            </table>

        </div>

    </div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\omira\resources\views/omd/users/index.blade.php ENDPATH**/ ?>