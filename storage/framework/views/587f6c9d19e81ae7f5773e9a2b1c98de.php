<table class="table">


    <thead>

        <tr>

            <th width="80">
                No
            </th>

            <th>
                Line
            </th>

            <th>
                Aksi
            </th>

        </tr>

    </thead>



    <tbody>


        <?php $__empty_1 = true; $__currentLoopData = $lines; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $line): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr>


                <td>
                    <?php echo e($loop->iteration); ?>

                </td>



                <td>

                    <strong>
                        <?php echo e($line->name); ?>

                    </strong>

                </td>

                <td>


                    <button class="btn btn-warning btn-sm"
                        onclick="editLine(
<?php echo e($line->id); ?>,
'<?php echo e($line->name); ?>'
)">

                        Edit

                    </button>



                    <form method="POST" action="<?php echo e(route('omd.master.line.destroy', $line->id)); ?>" style="display:inline">


                        <?php echo csrf_field(); ?>

                        <?php echo method_field('DELETE'); ?>


                        <button class="btn btn-danger btn-sm">

                            Hapus

                        </button>


                    </form>



                </td>


            </tr>



        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>


            <tr>

                <td colspan="4" class="text-center">

                    Belum ada Line

                </td>

            </tr>
        <?php endif; ?>



    </tbody>


</table>
<?php /**PATH C:\laragon\www\omira\resources\views/omd/master/partials/line_table.blade.php ENDPATH**/ ?>