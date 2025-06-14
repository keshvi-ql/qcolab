<div class="content">
    <?= $this->Form->create(null, ['class' => 'needs-validation', 'novalidate', 'id' => 'settings_form', 'name' => 'settings_form']) ?>
    <div class="row">
        <div class="col-lg-2">
            <?= $this->element('inner-sidebar'); ?>
        </div>
        <div class="col-lg-10">
            <div class="card">
                <div class="card-header d-sm-flex align-items-sm-center py-sm-0">
                    <h5 class="py-sm-2 my-sm-1">General Settings</h5>
                </div> 

                <div class="card-body">
                    <div class="d-lg-flex">
                        <div class="col-4">
                            <ul class="nav nav-tabs nav-tabs-vertical nav-tabs-vertical-start wmin-lg-200 me-lg-3 mb-3 mb-lg-0">
                                <li class="nav-item">
                                    <a href="#vertical-left-tab1" class="nav-link active" data-bs-toggle="tab">
                                        <i class="ph-briefcase me-2"></i>
                                        Company Information
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#vertical-left-tab2" class="nav-link" data-bs-toggle="tab">
                                        <i class="ph-clock me-2"></i>
                                        Date & Time
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#vertical-left-tab3" class="nav-link" data-bs-toggle="tab">
                                        <i class="ph-envelope me-2"></i>
                                        Email
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#vertical-left-tab4" class="nav-link" data-bs-toggle="tab">
                                        <i class="ph-gear me-2"></i>
                                        General
                                    </a>
                                </li>
                            </ul>
                        </div>

                        <div class="col-8">
                            <div class="tab-content flex-lg-fill">
                                <div class="tab-pane fade show active" id="vertical-left-tab1">
                                    <div class="mb-3">
                                        <label class="col-form-label col-lg-3">Company Name</label>
                                        <div class="col-lg-9">
                                            <input type="text" name="company_name" id="company_name" class="form-control" placeholder="XYZ"
                                                value="<?= h($settingsArray['company_name'] ?? '') ?>">
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="col-form-label col-lg-3">Company Email</label>
                                        <div class="col-lg-9">
                                            <input type="email" name="company_email" id="company_email" class="form-control" placeholder="john@doe.com" value="<?= h($settingsArray['company_email'] ?? '') ?>">
                                        </div>
                                    </div>
                                </div>

                                <div class="tab-pane fade" id="vertical-left-tab2">
                                    <div class="mb-3">
                                        <label class="col-form-label col-lg-3">Date Format: </label>
                                        <div class="col-lg-9">
                                            <select class="form-select" name="date_format">
                                                <option value="">Please select</option>
                                                <option value="j-M-Y" <?= isset($settingsArray['date_format']) && $settingsArray['date_format'] === 'j-M-Y' ? 'selected' : '' ?>>
                                                    <?= date("j-M-Y"); ?>
                                                </option>
                                                <option value="j-m-Y" <?= isset($settingsArray['date_format']) && $settingsArray['date_format'] === 'j-m-Y' ? 'selected' : '' ?>>
                                                    <?= date("j-m-Y"); ?>
                                                </option>
                                                <option value="jS F, Y" <?= isset($settingsArray['date_format']) && $settingsArray['date_format'] === 'jS F, Y' ? 'selected' : '' ?>>
                                                    <?= date("jS F, Y"); ?>
                                                </option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="col-form-label col-lg-3">Time Format: </label>
                                        <div class="col-lg-9">
                                            <select class="form-select" name="time_format">
                                                <option value="">Please select</option>
                                                <option value="h:i A" <?= isset($settingsArray['time_format']) && $settingsArray['time_format'] === 'h:i A' ? 'selected' : '' ?>>
                                                    02:30 PM (12 hours)
                                                </option>
                                                <option value="H:i" <?= isset($settingsArray['time_format']) && $settingsArray['time_format'] === 'H:i' ? 'selected' : '' ?>>
                                                    14:30 (24 hours)
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="tab-pane fade" id="vertical-left-tab3">
                                    <div class="mb-3">
                                        <label class="col-form-label col-lg-3">SMTP Host:</label>
                                        <div class="col-lg-9">
                                            <input type="text" name="smtp_host" id="smtp_host" class="form-control" value="<?= h($settingsArray['smtp_host'] ?? '') ?>">
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="col-form-label col-lg-3">SMTP Port:</label>
                                        <div class="col-lg-9">
                                            <input type="number" name="smtp_port" id="smtp_port" class="form-control" value="<?= h($settingsArray['smtp_port'] ?? '') ?>">
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="col-form-label col-lg-3">SMTP Encryption: </label>
                                        <div class="col-lg-9">
                                            <select class="form-select" name="smtp_encryption" id="smtp_encryption">
                                                <option value="ssl" <?= isset($settingsArray['smtp_encryption']) && $settingsArray['smtp_encryption'] === 'ssl' ? 'selected' : '' ?>>SSL</option>
                                                <option value="tls" <?= isset($settingsArray['smtp_encryption']) && $settingsArray['smtp_encryption'] === 'tls' ? 'selected' : '' ?>>TLS</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="col-form-label col-lg-3">SMTP User:</label>
                                        <div class="col-lg-9">
                                            <input type="text" name="smtp_user" id="smtp_user" class="form-control" value="<?= h($settingsArray['smtp_user'] ?? '') ?>">
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="col-form-label col-lg-3">SMTP Password:</label>
                                        <div class="col-lg-9">
                                            <input type="text" name="smtp_password" id="smtp_password" class="form-control" value="<?= h($settingsArray['smtp_password'] ?? '') ?>">
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="col-form-label col-lg-3">From Name:</label>
                                        <div class="col-lg-9">
                                            <input type="text" name="from_name" id="from_name" class="form-control" value="<?= h($settingsArray['from_name'] ?? '') ?>">
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="col-form-label col-lg-3">Reply to Email:</label>
                                        <div class="col-lg-9">
                                            <input type="email" name="reply_to_email" id="reply_to_email" class="form-control" value="<?= h($settingsArray['reply_to_email'] ?? '') ?>">
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="col-form-label col-lg-3">Reply to Name:</label>
                                        <div class="col-lg-9">
                                            <input type="text" name="reply_to_name" id="reply_to_name" class="form-control" value="<?= h($settingsArray['reply_to_name'] ?? '') ?>">
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="col-form-label col-lg-3">BCC All Emails to:</label>
                                        <div class="col-lg-9">
                                            <input type="email" name="bcc_emails_to" id="bcc_emails_to" class="form-control" value="<?= h($settingsArray['bcc_emails_to'] ?? '') ?>">
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="col-form-label col-lg-3">Email Signature:</label>
                                        <div class="col-lg-9">
                                            <textarea name="email_signature" id="email_signature" rows="4" class="form-control"><?= h($settingsArray['email_signature'] ?? '') ?></textarea>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="col-form-label col-lg-3">Email Header:</label>
                                        <div class="col-lg-9">
                                            <textarea name="email_header" id="email_header" rows="8" class="form-control"><?= h($settingsArray['email_header'] ?? '') ?></textarea>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="col-form-label col-lg-3">Email Footer:</label>
                                        <div class="col-lg-9">
                                            <textarea name="email_footer" id="email_footer" rows="8" class="form-control"><?= h($settingsArray['email_footer'] ?? '') ?></textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="tab-pane fade" id="vertical-left-tab4">
                                    <div class="mb-3">
                                        <label class="col-form-label col-lg-3">Log Activity</label>
                                        <div class="col-lg-9">
                                            <div class="form-check-horizontal">
                                                <label class="form-check form-switch mb-0">
                                                    <input type="checkbox" class="form-check-input is-valid" name="log_activity" id="log_activity" value="1"
                                                        <?= !isset($settingsArray['log_activity']) || $settingsArray['log_activity'] ? 'checked' : '' ?>>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="text-end bottom-0 position-fixed pt-2 pb-2 pe-4 bg-white w-100 sticky-submit-btn">
            <?= $this->Form->button(__('Save Settings') . ' <i class="ph-floppy-disk ms-2"></i>', [
                'class' => 'btn btn-success',
                'escapeTitle' => false
            ]) ?>
        </div>
    </div>
    <?= $this->Form->end() ?>
</div>