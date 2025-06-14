<div class="navbar navbar-dark navbar-expand-lg navbar-slide-top fixed-top">
    <div class="container-fluid">
        <div class="navbar-brand">
            <a href="<?= $this->Url->build('/') ?>" class="d-inline-flex align-items-center">
                <?= $this->Html->image('/assets/images/logo_icon.svg', ['alt' => 'logo']) ?>
                <?= $this->Html->image('/assets/images/logo_text_light.svg', ['class' => 'd-none d-sm-inline-block h-16px ms-3', 'alt' => 'logo']) ?>
            </a>
        </div>

        <div class="d-lg-none ms-2">
            <button class="navbar-toggler collapsed" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbar-mobile" aria-expanded="false">
                <i class="ph-squares-four"></i>
            </button>
        </div>
    </div>
</div>