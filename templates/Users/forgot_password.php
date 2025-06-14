<style>
    .spinner-border-sm {
        width: 1rem;
        height: 1rem;
        border-width: 0.15em;
    }
</style>
<div class="content d-flex justify-content-center align-items-center">
    <?= $this->Form->create(null, ['class' => 'login-form needs-validation', 'novalidate']) ?>
    <div class="card mb-0">
        <div class="card-body">
            <div class="text-center mb-3">
                <div
                    class="d-inline-flex bg-primary bg-opacity-10 text-primary lh-1 rounded-pill p-3 mb-3 mt-1">
                    <i class="ph-arrows-counter-clockwise ph-2x"></i>
                </div>
                <h5 class="mb-0">Password recovery</h5>
                <span class="d-block text-muted">We'll send you instructions in email</span>
            </div>

            <div class="mb-3">
                <label class="form-label">Email <span class="text-danger">*</span></label>
                <div class="form-control-feedback form-control-feedback-start">
                    <input type="email" name="email" id="email" class="form-control" required placeholder="john@doe.com">
                    <div class="form-control-feedback-icon">
                        <i class="ph-user-circle text-muted"></i>
                    </div>
                    <div class="invalid-feedback">Please Enter Email</div>
                </div>
            </div>

            <div class="mb-3">
                <button type="submit" id="reset-btn" class="btn btn-primary w-100">
                    <span id="reset-btn-icon">
                        <i class="ph-arrow-counter-clockwise me-2"></i>
                    </span>
                    <span id="reset-btn-text">Reset password</span>
                </button>
            </div>

            <div class="text-center">
                <a href="<?= $this->Url->build(['controller' => 'Users', 'action' => 'login']) ?>" class="">Back to Login</a>
            </div>
        </div>
    </div>
    <?= $this->Form->end() ?>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('.login-form');
        const submitBtn = document.getElementById('reset-btn');
        const btnIcon = document.getElementById('reset-btn-icon');
        const btnText = document.getElementById('reset-btn-text');

        form.addEventListener('submit', function() {
            // Disable the button
            submitBtn.disabled = true;

            // Replace icon with spinner
            btnIcon.innerHTML = `<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>`;
            btnText.textContent = 'Sending...';
        });
    });
</script>