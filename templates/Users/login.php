<div class="content d-flex justify-content-center align-items-center">
    <!-- Login form -->
    <?= $this->Form->create(null, ['class' => 'login-form needs-validation', 'novalidate']) ?>
    <div class="card mb-0">
        <div class="card-body">
            <div class="text-center mb-3">
                <div class="d-inline-flex align-items-center justify-content-center mb-4 mt-2">
                    <img src="assets/images/logo_icon.svg" class="h-48px" alt="">
                </div>
                <h5 class="mb-0">Login to your account</h5>
                <span class="d-block text-muted">Enter your credentials below</span>
            </div>

            <div class="mb-3">
                <label class="col-form-label col-lg-3">Username <span class="text-danger">*</span></label>
                <div class="form-control-feedback form-control-feedback-start">
                    <input type="email" name="email" id="email" class="form-control" placeholder="john@doe.com" required>
                    <div class="form-control-feedback-icon">
                        <i class="ph-user-circle text-muted"></i>
                    </div>
                    <div class="invalid-feedback">Please Enter Email</div>
                </div>
            </div>

            <div class="mb-3">
                <label class="col-form-label col-lg-3">Password <span class="text-danger">*</span></label>
                <div class="form-control-feedback form-control-feedback-start">
                    <input type="password" name="password" id="password" class="form-control" required placeholder="••••••••">
                    <div class="form-control-feedback-icon">
                        <i class="ph-lock text-muted"></i>
                    </div>
                    <div class="invalid-feedback">Please Enter Password</div>
                </div>
            </div>

            <!-- Add Remember Me Checkbox -->
            <div class="mb-3 form-check">
                <input type="checkbox" name="remember_me" id="remember_me" class="form-check-input">
                <label class="form-check-label" for="remember_me">Remember Me</label>
            </div>

            <div class="mb-3">
                <button type="submit" class="btn btn-primary w-100">Sign in</button>
            </div>

            <div class="text-center">
                <a href="<?= $this->Url->build(['controller' => 'Users', 'action' => 'forgot_password']) ?>" class="">Forgot Password?</a>
            </div>
        </div>
    </div>
    <?= $this->Form->end() ?>

</div>