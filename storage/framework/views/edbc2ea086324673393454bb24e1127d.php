

<?php $__env->startSection('title', 'Profil'); ?>
<?php $__env->startSection('header', 'Profil'); ?>

<?php $__env->startSection('content'); ?>

    <style>
        .profile-page {
            max-width: 1100px;
            margin: 0 auto;
            padding-bottom: 30px;
        }

        .profile-head {
            margin-bottom: 20px;
        }

        .profile-head h2 {
            margin: 0 0 5px;
            font-size: 22px;
            font-weight: 800;
            color: #172033;
        }

        .profile-head p {
            margin: 0;
            font-size: 12px;
            color: #64748b;
        }

        .profile-success {
            display: flex;
            align-items: center;
            gap: 9px;
            margin-bottom: 18px;
            padding: 13px 16px;
            border: 1px solid #cfc8ff;
            border-radius: 12px;
            background: #f5f3ff;
            color: #6d5dfc;
            font-size: 12px;
            font-weight: 600;
        }

        .profile-hero {
            position: relative;
            display: flex;
            align-items: center;
            gap: 20px;
            min-height: 145px;
            padding: 24px 28px;
            margin-bottom: 18px;
            overflow: hidden;
            background: #ffffff;
            border: 1px solid #e8e5f0;
            border-radius: 20px;
            box-shadow: 0 10px 35px rgba(15, 23, 42, .06);
        }

        .profile-hero::after {
            content: "";
            position: absolute;
            right: -45px;
            top: -55px;
            width: 170px;
            height: 170px;
            border-radius: 50%;
            background: rgba(109, 93, 252, .06);
        }

        .profile-avatar-large {
            position: relative;
            z-index: 2;
            flex: 0 0 92px;
            width: 92px;
            height: 92px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background: linear-gradient(135deg, #6d5dfc, #8175f7);
            border: 4px solid #ffffff;
            box-shadow: 0 7px 20px rgba(109, 93, 252, .22);
            color: #ffffff;
            font-size: 32px;
            font-weight: 800;
            text-transform: uppercase;
        }

        .profile-hero-info {
            position: relative;
            z-index: 2;
        }

        .profile-hero-name {
            margin-bottom: 5px;
            color: #172033;
            font-size: 21px;
            font-weight: 800;
        }

        .profile-hero-role {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            margin-bottom: 8px;
            padding: 5px 10px;
            border-radius: 20px;
            background: #f0edff;
            color: #6d5dfc;
            font-size: 10px;
            font-weight: 700;
        }

        .profile-hero-email {
            color: #667085;
            font-size: 12px;
        }

        .profile-hero-email i {
            margin-right: 6px;
            color: #6d5dfc;
        }

        .profile-card {
            background: #ffffff;
            border: 1px solid #e8e5f0;
            border-radius: 20px;
            padding: 24px;
            margin-bottom: 18px;
            box-shadow: 0 10px 35px rgba(15, 23, 42, .055);
        }

        .profile-card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 15px;
            padding-bottom: 17px;
            margin-bottom: 20px;
            border-bottom: 1px solid #eeeaf6;
        }

        .profile-card-title {
            margin: 0;
            color: #172033;
            font-size: 16px;
            font-weight: 800;
        }

        .profile-card-subtitle {
            margin: 4px 0 0;
            color: #8a94a3;
            font-size: 11px;
        }

        .profile-info-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 14px;
        }

        .profile-info-item {
            padding: 15px;
            border: 1px solid #eeeaf5;
            border-radius: 13px;
            background: #faf9ff;
        }

        .profile-info-label {
            display: flex;
            align-items: center;
            gap: 7px;
            margin-bottom: 8px;
            color: #94a3b8;
            font-size: 9px;
            font-weight: 700;
            letter-spacing: .05em;
            text-transform: uppercase;
        }

        .profile-info-label i {
            color: #6d5dfc;
            font-size: 11px;
        }

        .profile-info-value {
            display: block;
            color: #334155;
            font-size: 12px;
            font-weight: 700;
            word-break: break-word;
        }

        .settings-header {
            margin-top: 26px;
            margin-bottom: 18px;
        }

        .settings-title {
            margin: 0 0 5px;
            color: #172033;
            font-size: 16px;
            font-weight: 800;
        }

        .settings-subtitle {
            margin: 0;
            color: #8a94a3;
            font-size: 11px;
        }

        .settings-card {
            background: #ffffff;
            border: 1px solid #e8e5f0;
            border-radius: 20px;
            padding: 24px;
            box-shadow: 0 10px 35px rgba(15, 23, 42, .055);
        }

        .settings-section {
            padding-bottom: 22px;
            margin-bottom: 22px;
            border-bottom: 1px solid #eeeaf6;
        }

        .settings-section:last-of-type {
            border-bottom: none;
            margin-bottom: 0;
        }

        .settings-section-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 17px;
        }

        .settings-icon {
            width: 38px;
            height: 38px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex: 0 0 38px;
            border-radius: 11px;
            background: #f0edff;
            color: #6d5dfc;
            font-size: 14px;
        }

        .settings-section-header h3 {
            margin: 0 0 3px;
            color: #263238;
            font-size: 14px;
            font-weight: 800;
        }

        .settings-section-header p {
            margin: 0;
            color: #8a94a3;
            font-size: 10px;
        }

        .profile-form-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 16px;
        }

        .profile-form-grid.three {
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }

        .profile-field label {
            display: block;
            margin-bottom: 7px;
            color: #475569;
            font-size: 10px;
            font-weight: 700;
        }

        .profile-input-wrap {
            position: relative;
        }

        .profile-input-icon {
            position: absolute;
            left: 13px;
            top: 50%;
            transform: translateY(-50%);
            color: #a2a7b3;
            font-size: 12px;
            pointer-events: none;
        }

        .profile-field input {
            width: 100%;
            height: 42px;
            padding: 0 13px 0 36px;
            border: 1px solid #dce3ec;
            border-radius: 10px;
            background: #ffffff;
            color: #172033;
            font-size: 12px;
            outline: none;
            box-sizing: border-box;
            transition: .2s ease;
        }

        .profile-field input:focus {
            border-color: #7c6cf4;
            box-shadow: 0 0 0 3px rgba(124, 108, 244, .10);
        }

        .profile-field input::placeholder {
            color: #b1b7c2;
        }

        .profile-error {
            display: block;
            margin-top: 6px;
            color: #dc5b68;
            font-size: 10px;
        }

        .password-note {
            margin-top: 8px;
            color: #94a3b8;
            font-size: 10px;
        }

        .settings-footer {
            display: flex;
            justify-content: flex-end;
            padding-top: 22px;
            border-top: 1px solid #eeeaf6;
        }

        .profile-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            height: 40px;
            padding: 0 19px;
            border: none;
            border-radius: 10px;
            background: #6d5dfc;
            color: #ffffff;
            font-size: 11px;
            font-weight: 700;
            cursor: pointer;
            box-shadow: 0 6px 15px rgba(109, 93, 252, .18);
            transition: .2s ease;
        }

        .profile-btn:hover {
            background: #5d4df0;
            transform: translateY(-1px);
        }

        @media (max-width: 900px) {
            .profile-info-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .profile-form-grid,
            .profile-form-grid.three {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 600px) {
            .profile-hero {
                padding: 20px;
            }

            .profile-avatar-large {
                flex-basis: 72px;
                width: 72px;
                height: 72px;
                font-size: 25px;
            }

            .profile-hero-name {
                font-size: 17px;
            }

            .profile-info-grid {
                grid-template-columns: 1fr;
            }

            .profile-card,
            .settings-card {
                padding: 18px;
            }
        }
    </style>


    <div class="profile-page">

        
        <div class="profile-head">
            <h2>Profil Saya</h2>
        </div>


        
        <?php if(session('success')): ?>
            <div class="profile-success">
                <i class="fa-solid fa-circle-check"></i>
                <span><?php echo e(session('success')); ?></span>
            </div>
        <?php endif; ?>


        
        <div class="profile-hero">

            <div class="profile-avatar-large">
                <?php echo e(strtoupper(substr($user->name, 0, 1))); ?>

            </div>

            <div class="profile-hero-info">

                <div class="profile-hero-name">
                    <?php echo e($user->name); ?>

                </div>

                <div class="profile-hero-role">
                    <i class="fa-solid fa-user"></i>
                    <?php echo e($user->role === 'omd' ? 'OMD' : 'User'); ?>

                </div>

                <div class="profile-hero-email">
                    <i class="fa-solid fa-envelope"></i>
                    <?php echo e($user->email); ?>

                </div>

            </div>

        </div>


        
        <div class="profile-card">

            <div class="profile-card-header">

                <div>
                    <h3 class="profile-card-title">
                        Personal Information
                    </h3>
                </div>

            </div>


            <div class="profile-info-grid">

                <div class="profile-info-item">
                    <div class="profile-info-label">
                        <i class="fa-solid fa-user"></i>
                        Nama
                    </div>

                    <span class="profile-info-value">
                        <?php echo e($user->name); ?>

                    </span>
                </div>


                <div class="profile-info-item">
                    <div class="profile-info-label">
                        <i class="fa-solid fa-envelope"></i>
                        Email
                    </div>

                    <span class="profile-info-value">
                        <?php echo e($user->email); ?>

                    </span>
                </div>


                <div class="profile-info-item">
                    <div class="profile-info-label">
                        <i class="fa-solid fa-shield-halved"></i>
                        Role
                    </div>

                    <span class="profile-info-value">
                        <?php echo e($user->role === 'omd' ? 'OMD' : 'User'); ?>

                    </span>
                </div>


                <div class="profile-info-item">
                    <div class="profile-info-label">
                        <i class="fa-solid fa-industry"></i>
                        Line
                    </div>

                    <span class="profile-info-value">
                        <?php echo e($user->line?->name ?? 'OMD'); ?>

                    </span>
                </div>

            </div>

        </div>


        
        <div class="settings-header">
            <h3 class="settings-title">
                Account Settings
            </h3>
        </div>


        
        <form method="POST" action="<?php echo e(route('profile.update')); ?>" class="settings-card">

            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>


            
            <div class="settings-section">

                <div class="settings-section-header">

                    <div class="settings-icon">
                        <i class="fa-solid fa-user-pen"></i>
                    </div>

                    <div>
                        <h3>Informasi Akun</h3>
                        <p>Ubah nama dan email akun.</p>
                    </div>

                </div>


                <div class="profile-form-grid">

                    
                    <div class="profile-field">

                        <label for="profile_name">
                            Nama
                        </label>

                        <div class="profile-input-wrap">

                            <i class="fa-solid fa-user profile-input-icon"></i>

                            <input id="profile_name" type="text" name="name" value="<?php echo e(old('name', $user->name)); ?>"
                                maxlength="100" placeholder="Masukkan nama" required>

                        </div>

                        <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <span class="profile-error">
                                <?php echo e($message); ?>

                            </span>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

                    </div>


                    
                    <div class="profile-field">

                        <label for="profile_email">
                            Email
                        </label>

                        <div class="profile-input-wrap">

                            <i class="fa-solid fa-envelope profile-input-icon"></i>

                            <input id="profile_email" type="email" name="email"
                                value="<?php echo e(old('email', $user->email)); ?>" maxlength="255" placeholder="Masukkan email"
                                required>

                        </div>

                        <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <span class="profile-error">
                                <?php echo e($message); ?>

                            </span>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

                    </div>

                </div>

            </div>


            
            <div class="settings-section">

                <div class="settings-section-header">

                    <div class="settings-icon">
                        <i class="fa-solid fa-lock"></i>
                    </div>

                    <div>
                        <h3>Keamanan Password</h3>
                        <p>Kosongkan apabila tidak ingin mengubah password.</p>
                    </div>

                </div>


                <div class="profile-form-grid three">

                    
                    <div class="profile-field">

                        <label for="current_password">
                            Password Saat Ini
                        </label>

                        <div class="profile-input-wrap">

                            <i class="fa-solid fa-lock profile-input-icon"></i>

                            <input id="current_password" type="password" name="current_password"
                                placeholder="Password saat ini">

                        </div>

                        <?php $__errorArgs = ['current_password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <span class="profile-error">
                                <?php echo e($message); ?>

                            </span>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

                    </div>


                    
                    <div class="profile-field">

                        <label for="new_password">
                            Password Baru
                        </label>

                        <div class="profile-input-wrap">

                            <i class="fa-solid fa-key profile-input-icon"></i>

                            <input id="new_password" type="password" name="password" placeholder="Minimal 8 karakter">

                        </div>

                        <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <span class="profile-error">
                                <?php echo e($message); ?>

                            </span>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

                    </div>


                    
                    <div class="profile-field">

                        <label for="password_confirmation">
                            Konfirmasi Password
                        </label>

                        <div class="profile-input-wrap">

                            <i class="fa-solid fa-shield-halved profile-input-icon"></i>

                            <input id="password_confirmation" type="password" name="password_confirmation"
                                placeholder="Ulangi password baru">

                        </div>

                    </div>

                </div>

                <div class="password-note">
                    Password baru minimal 8 karakter. Password saat ini wajib diisi apabila password baru ingin diubah.
                </div>

            </div>


            
            <div class="settings-footer">

                <button type="submit" class="profile-btn">
                    <i class="fa-solid fa-floppy-disk"></i>
                    Simpan Perubahan
                </button>

            </div>

        </form>

    </div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\omira\resources\views/profile/index.blade.php ENDPATH**/ ?>