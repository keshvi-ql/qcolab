<div class="content d-flex justify-content-center align-items-center">
    <?= $this->Form->create(null, ['class' => 'login-form needs-validation', 'novalidate']) ?>
    <div class="card mb-0">
        <div class="card-body">
            <div class="text-center mb-3">
                <div
                    class="d-inline-flex bg-primary bg-opacity-10 text-primary lh-1 rounded-pill p-3 mb-3 mt-1">
                    <i class="ph-arrows-counter-clockwise ph-2x"></i>
                </div>
                <h5 class="mb-0">Reset Password</h5>
                <span class="d-block text-muted">Please reset your new password</span>
            </div>

            <div class="mb-3">
                <label class="col-form-label">New Password <span class="text-danger">*</span></label>
                <div class="form-control-feedback form-control-feedback-start">
                    <input type="password" name="password" id="password" class="form-control" required placeholder="Enter New Password">
                    <div class="form-control-feedback-icon">
                        <i class="ph-lock text-muted"></i>
                    </div>
                    <div class="invalid-feedback">Please Enter Password</div>
                </div>
            </div>

            <div class="mb-3">
                <label class="col-form-label">Confirm Password <span class="text-danger">*</span></label>
                <div class="form-control-feedback form-control-feedback-start">
                    <input type="password" name="password_confirm" id="password_confirm" class="form-control" required placeholder="Enter Confirm Password">
                    <div class="form-control-feedback-icon">
                        <i class="ph-lock text-muted"></i>
                    </div>
                    <div class="invalid-feedback">Please Enter Password</div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary w-100">
                <i class="ph-arrow-counter-clockwise me-2"></i>
                Reset password
            </button>
        </div>
    </div>
    <?= $this->Form->end() ?>
</div>