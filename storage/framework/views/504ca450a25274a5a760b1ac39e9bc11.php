<?php $__env->startSection('title', 'Tambah Akun'); ?>
<?php $__env->startSection('header', 'Tambah Akun'); ?>

<?php $__env->startSection('content'); ?>

    <div class="page-head">
        <div>
            <h2>Tambah Akun User</h2>

            <div class="muted">
                Tambahkan akun User berdasarkan Plant dan Line.
            </div>
        </div>

        <a href="<?php echo e(route('omd.users.index')); ?>" class="btn btn-secondary">
            Kembali
        </a>
    </div>


    <div class="card">

        <form method="POST" action="<?php echo e(route('omd.users.store')); ?>">
            <?php echo csrf_field(); ?>

            <div class="field">

                <label for="name">
                    Nama
                </label>

                <input
                    id="name"
                    type="text"
                    name="name"
                    value="<?php echo e(old('name')); ?>"
                    placeholder="Nama user"
                    required
                >

                <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <small class="text-danger"><?php echo e($message); ?></small>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

            </div>


            <div class="field" style="margin-top:16px;">

                <label for="email">
                    Email
                </label>

                <input
                    id="email"
                    type="email"
                    name="email"
                    value="<?php echo e(old('email')); ?>"
                    placeholder="contoh@omd.com"
                    required
                >

                <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <small class="text-danger"><?php echo e($message); ?></small>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

            </div>


            <div class="field" style="margin-top:16px;">

                <label for="plant_id">
                    Plant
                </label>

                <select
                    id="plant_id"
                    name="plant_id"
                    required
                >

                    <option value="">
                        Pilih Plant
                    </option>

                    <?php $__currentLoopData = $plants; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $plant): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                        <option
                            value="<?php echo e($plant->id); ?>"
                            <?php if(old('plant_id') == $plant->id): echo 'selected'; endif; ?>
                        >
                            <?php echo e($plant->name); ?>

                        </option>

                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                </select>

                <?php $__errorArgs = ['plant_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <small class="text-danger"><?php echo e($message); ?></small>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

            </div>


            <div class="field" style="margin-top:16px;">

                <label for="line_id">
                    Line
                </label>

                <select
                    id="line_id"
                    name="line_id"
                    required
                    disabled
                >

                    <option value="">
                        Pilih Plant terlebih dahulu
                    </option>

                </select>

                <?php $__errorArgs = ['line_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <small class="text-danger"><?php echo e($message); ?></small>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

            </div>


            <div class="field" style="margin-top:16px;">

                <label for="password">
                    Password
                </label>

                <input
                    id="password"
                    type="password"
                    name="password"
                    placeholder="Minimal 8 karakter"
                    required
                >

                <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <small class="text-danger"><?php echo e($message); ?></small>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

            </div>


            <div class="field" style="margin-top:16px;">

                <label for="password_confirmation">
                    Konfirmasi Password
                </label>

                <input
                    id="password_confirmation"
                    type="password"
                    name="password_confirmation"
                    placeholder="Ulangi password"
                    required
                >

            </div>


            <div style="margin-top:24px; display:flex; gap:10px;">

                <a
                    href="<?php echo e(route('omd.users.index')); ?>"
                    class="btn btn-secondary"
                >
                    Batal
                </a>

                <button
                    type="submit"
                    class="btn btn-primary"
                >
                    Simpan Akun
                </button>

            </div>

        </form>

    </div>


    <script>

        var plants = <?php echo json_encode($plants, 15, 512) ?>;

        var plantSelect =
            document.getElementById('plant_id');

        var lineSelect =
            document.getElementById('line_id');

        var oldLineId =
            "<?php echo e(old('line_id')); ?>";


        function loadLines(plantId)
        {
            lineSelect.innerHTML = '';

            if (!plantId) {

                lineSelect.innerHTML =
                    '<option value="">Pilih Plant terlebih dahulu</option>';

                lineSelect.disabled = true;

                return;
            }


            var plant = null;

            for (var i = 0; i < plants.length; i++) {

                if (
                    String(plants[i].id) ===
                    String(plantId)
                ) {

                    plant = plants[i];

                    break;
                }
            }


            if (
                !plant ||
                !plant.lines ||
                plant.lines.length === 0
            ) {

                lineSelect.innerHTML =
                    '<option value="">Tidak ada Line tersedia</option>';

                lineSelect.disabled = true;

                return;
            }


            lineSelect.disabled = false;

            lineSelect.innerHTML =
                '<option value="">Pilih Line</option>';


            for (
                var j = 0;
                j < plant.lines.length;
                j++
            ) {

                var line =
                    plant.lines[j];

                var option =
                    document.createElement('option');


                option.value =
                    line.id;

                option.textContent =
                    line.name;


                if (
                    String(oldLineId) ===
                    String(line.id)
                ) {

                    option.selected = true;

                }


                lineSelect.appendChild(option);

            }
        }


        plantSelect.addEventListener(
            'change',
            function ()
            {
                oldLineId = '';

                loadLines(
                    this.value
                );
            }
        );


        if (plantSelect.value) {

            loadLines(
                plantSelect.value
            );

        }

    </script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\omira\resources\views/omd/users/create.blade.php ENDPATH**/ ?>