<?php $this->start('css'); ?>
<?= $this->Html->css([
    '/assets/css/cropper.css',
    '/assets/js/dropzone/dropzone.css'
]) ?>
<?php $this->end(); ?>

<div class="page-header">
    <div class="page-header-content d-lg-flex">
        <div class="d-flex">
            <h4 class="page-title mb-0">
                <?= isset($title) ? $title : '' ?>
            </h4>

            <a href="#page_header"
                class="btn btn-light align-self-center collapsed d-lg-none border-transparent rounded-pill p-0 ms-auto"
                data-bs-toggle="collapse">
                <i class="ph-caret-down collapsible-indicator ph-sm m-1"></i>
            </a>
        </div>
    </div>
</div>

<div class="content">
    <?= $this->Form->create($user, ['class' => 'needs-validation', 'novalidate', 'id' => 'edit_user', 'name' => 'edit_user', 'type' => 'file']) ?>
    <div class="row">
        <div class="col-lg-8 offset-lg-2">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Staff</h5>
                </div>

                <div class="card-body">
                    <div class="col-12">
                        <ul class="nav nav-tabs wmin-lg-200 me-lg-3 mb-3 mb-lg-0">
                            <li class="nav-item">
                                <a href="#tab1" class="nav-link <?= $activeTab == 'tab1' ? 'active' : '' ?>" data-bs-toggle="tab">General Info</a>
                            </li>
                            <li class="nav-item">
                                <a href="#tab2" class="nav-link <?= $activeTab == 'tab2' ? 'active' : '' ?>" data-bs-toggle="tab">Job Info</a>
                            </li>
                            <li class="nav-item">
                                <a href="#tab3" class="nav-link <?= $activeTab == 'tab3' ? 'active' : '' ?>" data-bs-toggle="tab">Account Settings</a>
                            </li>

                            <li class="nav-item">
                                <a href="#tab4" class="nav-link <?= $activeTab == 'tab4' ? 'active' : '' ?>" data-bs-toggle="tab">Files </a>
                            </li>
                        </ul>
                    </div> <br>

                    <div class="col-12">
                        <div class="tab-content">
                            <div class="tab-pane fade <?= $activeTab == 'tab1' ? 'show active' : '' ?>" id="tab1">
                                <h3>General Info</h3>

                                <div class="d-flex justify-content-center w-100">
                                    <div class="card-img-actions mb-3">
                                        <?php
                                        $profileImagePath = WWW_ROOT . 'profile_img_uploads' . DS . $user->profile_image;
                                        $profileImage = $user->profile_image;
                                        if (!empty($user->profile_image) && file_exists($profileImagePath)) {
                                            $profileImage = $this->Html->image('/profile_img_uploads/' . $user->profile_image, [
                                                'class' => 'rounded-circle',
                                                'id' => 'profileCropImage',
                                                'alt' => 'profile-img',
                                                'width' => 150,
                                                'height' => 150
                                            ]);
                                        } else {
                                            $profileImage = $this->Html->image('/assets/images/avatar.jpg', [
                                                'class' => 'rounded-circle',
                                                'id' => 'profileCropImage',
                                                'alt' => 'default-profile-img',
                                                'width' => 150,
                                                'height' => 150
                                            ]);
                                        }
                                        ?>
                                        <div id="imagePreview">
                                            <?= $profileImage ?>
                                        </div>
                                        <div class="card-img-actions-overlay card-img rounded-circle d-flex align-items-center justify-content-center">
                                            <?php if ($this->User->hasPermission('Users', 'uploadProfileImage')): ?>
                                                <button id="profileButton" class="btn btn-outline-white btn-icon rounded-pill" data-bs-popup='tooltip' data-bs-placement='top'
                                                    title='Upload (200X200 px)'>
                                                    <i class="ph-upload-simple"></i>
                                                </button>
                                            <?php endif; ?>
                                            <?php if ($this->User->hasPermission('Users', 'saveCropProfileImage')): ?>
                                                <button id="profile_crop_button" class="btn btn-outline-white btn-icon rounded-pill" data-bs-popup='tooltip' data-bs-placement='top'
                                                    title='Upload and crop'>
                                                    <i class="ph-camera"></i>
                                                </button>
                                            <?php endif; ?>
                                            <?= $this->Form->file('profile_image', ['type' => 'file', 'id' => 'profileImageInput', 'style' => 'display:none;', 'accept' => 'image/*']) ?>
                                            <?= $this->Form->file('profile_image', ['type' => 'file', 'id' => 'cropProfileImageInput', 'style' => 'display:none;', 'accept' => 'image/*']) ?>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <?= $this->Form->label('first_name', 'First Name <span class="text-danger">*</span>', ['class' => 'form-label', 'escape' => false]) ?>
                                    <?= $this->Form->text('first_name', [
                                        'class' => 'form-control',
                                        'placeholder' => 'First Name',
                                        'required' => true
                                    ]) ?>
                                    <div class="invalid-feedback">Please Enter First Name</div>
                                </div>

                                <div class="mb-3">
                                    <?= $this->Form->label('middle_name', 'Middle Name', ['class' => 'form-label', 'escape' => false]) ?>
                                    <?= $this->Form->text('middle_name', [
                                        'class' => 'form-control',
                                        'placeholder' => 'Middle Name',
                                    ]) ?>
                                </div>

                                <div class="mb-3">
                                    <?= $this->Form->label('last_name', 'Last Name', ['class' => 'form-label', 'escape' => false]) ?>
                                    <?= $this->Form->text('last_name', [
                                        'class' => 'form-control',
                                        'placeholder' => 'Last Name',
                                    ]) ?>
                                </div>

                                <div class="mb-3">
                                    <p>Gender</p>
                                    <?= $this->Form->radio('gender', [
                                        ['value' => 'male', 'text' => ' Male'],
                                        ['value' => 'female', 'text' => ' Female']
                                    ], [
                                        'class' => 'form-check-input form-check-input-secondary',
                                        'label' => ['class' => 'form-check-label ms-2'],
                                        'default' => !empty($user->gender) ? $user->gender : 'male',
                                    ]) ?>
                                </div>

                                <div class="mb-3">
                                    <?= $this->Form->label('employee_code', 'Employee Code <span class="text-danger">*</span>', ['class' => 'form-label', 'escape' => false]) ?>
                                    <?= $this->Form->text('employee_code', [
                                        'class' => 'form-control',
                                        'placeholder' => 'Employee Code',
                                        'required' => true
                                    ]) ?>
                                    <div class="invalid-feedback">Please Enter Employee Code</div>
                                </div>


                                <div class="mb-3">
                                    <?= $this->Form->label('phone_no', 'Phone No', ['class' => 'form-label']) ?>
                                    <?= $this->Form->number('phone_no', [
                                        'class' => 'form-control',
                                        'placeholder' => 'Phone No'
                                    ]) ?>
                                </div>

                                <div class="mb-3">
                                    <?= $this->Form->label('alt_phone_no', 'Alternative Phone No', ['class' => 'form-label']) ?>
                                    <?= $this->Form->number('alt_phone_no', [
                                        'class' => 'form-control',
                                        'placeholder' => 'Alternative Phone No'
                                    ]) ?>
                                </div>

                                <div class="mb-3">
                                    <?= $this->Form->label('address', 'Address', ['class' => 'form-label']) ?>
                                    <?= $this->Form->textarea('address', [
                                        'class' => 'form-control',
                                        'placeholder' => 'Enter your address',
                                        'rows' => 3
                                    ]) ?>
                                </div>

                                <div class="mb-3">
                                    <?= $this->Form->label('alt_address', 'Alternate Address', ['class' => 'form-label']) ?>
                                    <?= $this->Form->textarea('alt_address', [
                                        'class' => 'form-control',
                                        'placeholder' => 'Enter alternate address',
                                        'rows' => 3
                                    ]) ?>
                                </div>

                                <div class="mb-3">
                                    <?= $this->Form->label('skype', 'Skype ID', ['class' => 'form-label']) ?>
                                    <?= $this->Form->text('skype', [
                                        'class' => 'form-control',
                                        'placeholder' => 'Enter Skype ID'
                                    ]) ?>
                                    <div class="invalid-feedback">Please Enter Skype</div>
                                </div>

                                <div class="mb-3">
                                    <?= $this->Form->label('dob', 'Date of Birth', ['class' => 'form-label']) ?>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="ph-calendar"></i>
                                        </span>
                                        <?= $this->Form->text('dob', [
                                            'class' => 'form-control datepicker-basic',
                                            'placeholder' => 'Date of Birth',
                                        ]) ?>
                                    </div>
                                </div>

                                <?php if ($this->User->isAdmin()) { ?>

                                    <div class="mb-3">
                                        <?= $this->Form->label('pan_no', 'PAN Number', ['class' => 'form-label']) ?>
                                        <?= $this->Form->text('pan_no', [
                                            'class' => 'form-control',
                                            'maxlength' => 10,
                                            'placeholder' => 'Enter PAN Number',
                                            'style' => 'text-transform: uppercase;'
                                        ]) ?>
                                        <div class="invalid-feedback">Please Enter Pan No</div>
                                    </div>

                                    <div class="mb-3">
                                        <?= $this->Form->label('bank_name', 'Bank Name', ['class' => 'form-label']) ?>
                                        <?= $this->Form->text('bank_name', [
                                            'class' => 'form-control',
                                            'placeholder' => 'Enter Bank Name'
                                        ]) ?>
                                        <div class="invalid-feedback">Please Enter Bank Name</div>
                                    </div>

                                    <div class="mb-3">
                                        <?= $this->Form->label('bank_account_no', 'Bank Account Number', ['class' => 'form-label']) ?>
                                        <?= $this->Form->text('bank_account_no', [
                                            'class' => 'form-control',
                                            'placeholder' => 'Enter Bank Account Number'
                                        ]) ?>
                                        <div class="invalid-feedback">Please Enter Bank Account No</div>
                                    </div>

                                <?php } ?>
                            </div>

                            <div class="tab-pane fade <?= $activeTab == 'tab2' ? 'show active' : '' ?>" id="tab2">
                                <h3>Job Info</h3>

                                <div class="mb-3">
                                    <?= $this->Form->label('job_title', 'Job Title', ['class' => 'form-label', 'escape' => false]) ?>
                                    <?= $this->Form->text('job_title', [
                                        'class' => 'form-control',
                                        'placeholder' => 'Job Title',
                                    ]) ?>
                                </div>

                                <div class="mb-3">
                                    <p>Is Trainee ?</p>
                                    <?= $this->Form->checkbox('is_trainee', [
                                        'id' => 'is_trainee',
                                        'value' => '1',
                                        'class' => 'form-check-input form-check-input-secondary',
                                        'checked' => !empty($user->is_trainee) && $user->is_trainee == 1
                                    ]) ?>
                                    <?= $this->Form->label('is_trainee', 'Trainee', [
                                        'for' => 'is_trainee',
                                        'class' => 'ms-1'
                                    ]) ?>
                                </div>

                                <div class="mb-3">
                                    <p>Is BDE ?</p>
                                    <?= $this->Form->checkbox('is_bde', [
                                        'id' => 'is_bde',
                                        'value' => '1',
                                        'class' => 'form-check-input form-check-input-secondary',
                                        'checked' => !empty($user->is_bde) && $user->is_bde == 1
                                    ]) ?>
                                    <?= $this->Form->label('is_bde', 'BDE', [
                                        'for' => 'is_bde',
                                        'class' => 'ms-1'
                                    ]) ?>
                                </div>

                                <div class="mb-3">
                                    <?= $this->Form->label('salary', 'Salary', ['class' => 'form-label']) ?>
                                    <?= $this->Form->number('salary', [
                                        'class' => 'form-control',
                                        'placeholder' => 'Enter Salary',
                                        'step' => '0.01'
                                    ]) ?>
                                </div>

                                <div class="mb-3">
                                    <?= $this->Form->label('date_of_joining', 'Date of Hire', ['class' => 'form-label']) ?>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="ph-calendar"></i>
                                        </span>
                                        <?= $this->Form->text('date_of_joining', [
                                            'class' => 'form-control datepicker-basic',
                                            'placeholder' => 'Select Date',
                                        ]) ?>
                                    </div>
                                </div>

                                <?php if ($this->User->isAdmin()) { ?>

                                    <div class="mb-3">
                                        <?= $this->Form->label('increment_month', 'Increment Month', ['class' => 'form-label']) ?>
                                        <?= $this->Form->select('increment_month', [
                                            'January' => 'January',
                                            'February' => 'February',
                                            'March' => 'March',
                                            'April' => 'April',
                                            'May' => 'May',
                                            'June' => 'June',
                                            'July' => 'July',
                                            'August' => 'August',
                                            'September' => 'September',
                                            'October' => 'October',
                                            'November' => 'November',
                                            'December' => 'December'
                                        ], [
                                            'empty' => 'Select Increment Month',
                                            'class' => 'form-control',
                                            'default' => $user->increment_month
                                        ]) ?>
                                    </div>

                                    <div class="mb-3">
                                        <?= $this->Form->label('security_deposit_amount', 'Security Deposit Amount', ['class' => 'form-label']) ?>
                                        <?= $this->Form->number('security_deposit_amount', [
                                            'class' => 'form-control',
                                            'placeholder' => 'Enter Security Deposit Amount'
                                        ]) ?>
                                        <div class="invalid-feedback">Please Enter Security Deposit Amount</div>
                                    </div>

                                <?php } ?>
                            </div>

                            <div class="tab-pane fade <?= $activeTab == 'tab3' ? 'show active' : '' ?>" id="tab3">
                                <h3>Account Settings</h3>

                                <?php if ($this->User->isAdmin()) { ?>

                                    <div class="mb-3">
                                        <label class="form-switch">
                                            <p>Status</p>
                                            <?= $this->Form->checkbox('status', [
                                                'id' => 'status',
                                                'value' => '1',
                                                'class' => 'form-check-input form-check-input-secondary',
                                                'checked' => !empty($user->status) && $user->status == 1
                                            ]) ?>
                                        </label>
                                    </div>

                                <?php } ?>

                                <div class="mb-3">
                                    <?= $this->Form->label('role', 'Role <span class="text-danger">*</span>', ['class' => 'form-label', 'escape' => false]) ?>
                                    <?= $this->Form->select('role_id', $roles, [
                                        'class' => 'select form-select',
                                        'empty' => '-- Select Role --',
                                        'id' => 'role',
                                        'required' => true
                                    ]); ?>
                                    <div class="invalid-feedback">Please Select Role</div>
                                </div>

                                <div class="mb-3">
                                    <?= $this->Form->label('email', 'Email <span class="text-danger">*</span>', ['class' => 'form-label', 'escape' => false]) ?>
                                    <?= $this->Form->text('email', [
                                        'class' => 'form-control',
                                        'placeholder' => 'Email',
                                        'required' => true
                                    ]) ?>
                                    <div class="invalid-feedback">Please Enter Email</div>
                                </div>

                                <div class="mb-3">
                                    <?= $this->Form->label('alt_email', 'Alternate Email', ['class' => 'form-label', 'escape' => false]) ?>
                                    <?= $this->Form->text('alt_email', [
                                        'class' => 'form-control',
                                        'placeholder' => 'Alternate Email'
                                    ]) ?>
                                    <div class="invalid-feedback">Please Enter Alternate Email</div>
                                </div>

                                <div class="mb-3">
                                    <?= $this->Form->label('password', 'New Password', ['class' => 'form-label']) ?>
                                    <?= $this->Form->password('password', [
                                        'class' => 'form-control',
                                        'placeholder' => 'New Password',
                                        'value' => '',
                                        'required' => false
                                    ]) ?>
                                </div>

                                <div class="mb-3">
                                    <?= $this->Form->label('confirm_password', 'Confirm New Password', ['class' => 'form-label']) ?>
                                    <?= $this->Form->password('confirm_password', [
                                        'class' => 'form-control',
                                        'placeholder' => 'Confirm New Password',
                                        'value' => '',
                                        'required' => false
                                    ]) ?>
                                </div>
                            </div>

                            <div class="tab-pane fade <?= $activeTab == 'tab4' ? 'show active' : '' ?>" id="tab4">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h3>Files</h3>
                                    </div>
                                    <div>
                                        <?php if ($this->User->hasPermission('Users', 'uploadTempFiles') && $this->User->hasPermission('Users', 'deleteTempFiles') && $this->User->hasPermission('Users', 'saveUploadedFiles')): ?>
                                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#file_upload_modal"><i class="ph-plus-circle me-2"></i> Add Files</button>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <table id="file_upload_table" class="table datatable-basic table-striped">
                                    <thead>
                                        <tr>
                                            <th>Image</th>
                                            <th>File</th>
                                            <th>Size</th>
                                            <th>Uploaded by</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($files as $file) { ?>
                                            <tr>
                                                <td>
                                                    <?php
                                                    $fileUrl = !empty($file->file_name) ? '/uploads/' . $file->file_name : '/assets/images/avatar.jpg';
                                                    $fileExtension = pathinfo($file->file_name, PATHINFO_EXTENSION);
                                                    ?>
                                                    <?php if (in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif'])): ?>
                                                        <?= $this->Html->image($fileUrl, ['class' => 'rounded-circle', 'alt' => 'File Image', 'width' => '40', 'height' => '40']) ?>
                                                    <?php elseif ($fileExtension == 'pdf'): ?>
                                                        <?= $this->Html->image('/assets/images/pdf-icon.png', ['class' => 'rounded-circle', 'alt' => 'PDF File', 'width' => '40', 'height' => '40']) ?>
                                                    <?php else: ?>
                                                        <?= $this->Html->image('/assets/images/avatar.jpg', ['class' => 'rounded-circle', 'alt' => 'File', 'width' => '40', 'height' => '40']) ?>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <p>
                                                        <?php
                                                        $originalFileName = $file->file_name;
                                                        $displayName = preg_replace('/^[^-]+-/', '', $originalFileName);
                                                        ?>
                                                        <?= $this->Html->link($displayName, 'javascript:void(0);', [
                                                            'class' => 'open-modal',
                                                            'data-file' => $this->Url->build('/uploads/' . $originalFileName, ['fullBase' => true]),
                                                        ]) ?>
                                                    </p>
                                                    <p><?= $file->description ?></p>
                                                </td>
                                                <td><?= $file->file_size ?></td>
                                                <td><?= $file->uploaded_by_user->first_name ?></td>
                                                <td>
                                                    <?= $this->Html->link(
                                                        __('<i class="ph-download-simple"></i>'),
                                                        $this->Url->build('/uploads/' . $file->file_name, ['fullBase' => true]),
                                                        [
                                                            'class' => 'text-success',
                                                            'escape' => false,
                                                            'data-bs-popup' => 'tooltip',
                                                            'data-bs-placement' => 'top',
                                                            'title' => 'Download',
                                                            'download' => $file->file_name,
                                                            'target' => '_blank'
                                                        ]
                                                    ); ?>
                                                    <?php if ($this->User->hasPermission('Users', 'deleteFile')): ?>
                                                        <?= $this->Html->link(
                                                            __('<i class="ph-trash"></i>'),
                                                            '#',
                                                            [
                                                                'class' => 'text-danger sweet_warning',
                                                                'escape' => false,
                                                                'id' => $file->id,
                                                                'data-bs-popup' => 'tooltip',
                                                                'data-bs-placement' => 'top',
                                                                'title' => 'Delete',
                                                                'data-record-id' => $file->id,
                                                                'data-url' => $this->Url->build(['controller' => 'Users', 'action' => 'deleteFile', $user->id]),
                                                                'data-confirm' => 'Are you sure?',
                                                            ]
                                                        ); ?>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                        <!-- New files will be appended here -->
                                    </tbody>
                                </table>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="text-end bottom-0 position-fixed pt-2 pb-2 pe-4 bg-white w-100 sticky-submit-btn">
        <?= $this->Form->button(__('Save') . ' <i class="ph-floppy-disk ms-2"></i>', [
            'class' => 'btn btn-success',
            'escapeTitle' => false
        ]) ?>
        <?= $this->Html->link(
            __('Back'),
            ['controller' => 'Users', 'action' => 'index'],
            ['class' => 'btn btn-light']
        ) ?>
    </div>

    <?= $this->Form->end() ?>
</div>

<!-- File upolad modal -->
<div id="file_upload_modal" class="modal fade" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Upload Image</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <?= $this->Form->create(null, ['url' => ['controller' => 'Users', 'action' => 'saveUploadedFiles'], 'type' => 'post', 'id' => 'fileUploadForm']) ?>

            <div class="modal-body">
                <?= $this->Form->hidden('uploaded_files', ['id' => 'uploadedFiles']) ?>
                <?= $this->Form->hidden('uploaded_descriptions', ['id' => 'uploadedDescriptions']) ?>
                <?= $this->Form->hidden('user_id', ['value' => $user->id]) ?>

                <div class="dropzone" id="file-upload"></div>

                <!-- Preview section for images and descriptions -->
                <div id="image-preview-container" class="mt-3">
                    <h6>Uploaded Images</h6>
                    <div id="image-previews"></div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-link" data-bs-dismiss="modal">Close</button>
                <?= $this->Form->button(__('Save'), ['type' => 'submit', 'class' => 'btn btn-primary']) ?>
            </div>

            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
<!-- /file upload modal -->

<!-- profile image crop modal -->
<div id="profile_crop_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Crop Image Before Upload</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <div class="img-container">
                    <div class="row">
                        <div class="col-md-8">
                            <img src="" id="sample_image" />
                        </div>
                        <div class="col-md-4">
                            <div class="preview"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" id="zoomin" class="btn btn-primary">Zoom In</button>
                <button type="button" id="zoomout" class="btn btn-primary">Zoom Out</button>
                <button type="button" id="rotateleft" class="btn btn-primary">rotate Left</button>
                <button type="button" id="rotateright" class="btn btn-primary">rotate Right</button>
                <button type="button" id="scalex" class="btn btn-primary">Scale X</button>
                <button type="button" id="scaley" class="btn btn-primary">Scale Y</button>
                <br><br>
                <button type="button" id="aspres169" class="btn btn-primary">16:9</button>
                <button type="button" id="aspres43" class="btn btn-primary">4:3</button>
                <button type="button" id="aspres11" class="btn btn-primary">1:1</button>
                <button type="button" id="aspres23" class="btn btn-primary">2:3</button>
                <button type="button" id="aspresfree" class="btn btn-primary">free</button>
                <button type="button" id="crop" class="btn btn-primary">Crop</button>
                <button type="button" class="btn btn-link" data-bs-dismiss="modal">Close</button>
            </div>

        </div>
    </div>
</div>
<!-- /profile image crop modal -->

<!-- File Show Modal HTML -->
<div class="modal fade" id="showFileModal" tabindex="-1" role="dialog" aria-labelledby="fileModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="fileModalLabel"></h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <iframe id="fileIframe" src="" style="width:100%; height:500px;" frameborder="0"></iframe>
            </div>
        </div>
    </div>
</div>
<!-- File Show Modal HTML -->

<?php $this->start('script'); ?>
<?= $this->Html->script([
    '/assets/js/vendor/forms/validation/validate.min.js',
    '/assets/js/custom/form_validation.js',
    '/assets/js/dropzone/dropzone.js',
    '/assets/js/bootstrap/components_modals.js',
    '/assets/js/custom/cropper.js'
]) ?>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        DateTimePickers.init();
    });
</script>

<script>
    Dropzone.autoDiscover = false;

    var uploadedFiles = [];

    var myDropzone = new Dropzone("#file-upload", {
        url: "<?= $this->Url->build(['controller' => 'Users', 'action' => 'uploadTempFiles']); ?>",
        maxFilesize: 0, // MB
        acceptedFiles: null,
        addRemoveLinks: true,
        headers: {
            'X-CSRF-Token': csrfToken
        },
        success: function(file, response) {
            if (typeof response === "string") {
                response = JSON.parse(response);
            }
            file.serverFilename = response.data.filename;
            uploadedFiles.push(response.data.filename);

            // Add a description input for each uploaded image
            $('#image-previews').append(function() {

                var fileExtension = file.serverFilename.split('.').pop().toLowerCase();

                var previewUrl;
                if (['jpg', 'jpeg', 'png', 'gif'].includes(fileExtension)) {
                    previewUrl = baseUrl + `/temp_uploads/${file.serverFilename}`;
                } else if (fileExtension === 'pdf') {
                    previewUrl = baseUrl + '/assets/images/pdf-icon.png'; // Path to your PDF icon
                } else {
                    previewUrl = baseUrl + '/assets/images/avatar.jpg'; // Path to your generic file icon
                }

                return `
                <div class="uploaded-image-container mb-3" data-filename="${file.serverFilename}" style="display: flex; align-items: flex-start; border: 1px solid #ddd; padding: 10px; border-radius: 5px;">
                    <div class="image-preview" style="margin-right: 15px;">
                        <img src="${previewUrl}" class="uploaded-image" style="width: 100px; height: 100px; object-fit: cover; border-radius: 5px;">
                    </div>
                    <div class="image-info" style="flex: 1;">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="image-name" style="font-weight: bold;">${file.name}</div>
                            <i class="ph-trash remove-image-btn" style="cursor: pointer;color: red;" data-file="${file.serverFilename}"></i>
                        </div>
                        <div class="image-size text-muted" style="font-size: 0.9em;">Size: ${(file.size / 1024).toFixed(2)} KB</div>
                        <div class="image-description mt-2">
                            <input type="text" class="form-control description-input mt-1" id="desc-${file.serverFilename}" data-file="${file.serverFilename}" placeholder="Enter description" style="width: 100%;">
                        </div>
                    </div>
                </div>
            `
            });
            $('#uploadedFiles').val(uploadedFiles.join(','));
        },
        error: function(file, response) {
            if (typeof response === "string") {
                response = JSON.parse(response);
            }
            console.log(response);
        },
        removedfile: function(file) {
            if (file.serverFilename) {
                $.ajax({
                    url: "<?= $this->Url->build(['controller' => 'Users', 'action' => 'deleteTempFiles']); ?>",
                    type: 'POST',
                    data: {
                        id: file.serverFilename,
                        _csrfToken: csrfToken
                    },
                    success: function(response) {
                        if (typeof response === "string") {
                            response = JSON.parse(response);
                        }
                        console.log(response.message);
                    },
                    error: function(response) {
                        if (typeof response === "string") {
                            response = JSON.parse(response);
                        }
                        console.error(response.message);
                    }
                });

                // Remove the file from the list of uploaded files
                uploadedFiles = uploadedFiles.filter(function(value) {
                    return value !== file.serverFilename;
                });
                $('#uploadedFiles').val(uploadedFiles.join(','));

                $('[data-filename="' + file.serverFilename + '"]').remove();
            }
            // Remove the file from the preview list
            var _ref;
            return (_ref = file.previewElement) != null ? _ref.parentNode.removeChild(file.previewElement) : void 0;
        }
    });

    var myDropzoneRemove = Dropzone.forElement("#file-upload");
    // Function to remove all files
    function removeAllFiles() {
        myDropzoneRemove.removeAllFiles();
    }
    // Attach the click event to the .btn-link button
    document.querySelector('.btn-link').addEventListener('click', removeAllFiles);

    // Attach the click event to the .btn-close button
    document.querySelector('.btn-close').addEventListener('click', removeAllFiles);

    $(document).on('click', '.remove-image-btn', function() {
        var filename = $(this).data('file');
        $('[data-filename="' + filename + '"]').remove();

        // Find the corresponding file in Dropzone and remove it
        var fileToRemove = myDropzone.files.find(function(file) {
            return file.serverFilename === filename;
        });

        if (fileToRemove) {
            myDropzone.removeFile(fileToRemove);
        }
    });

    // AJAX form submission
    $('#fileUploadForm').on('submit', function(e) {
        e.preventDefault();

        var descriptions = {};

        $('.description-input').each(function() {
            var filename = $(this).data('file');
            var description = $(this).val();
            descriptions[filename] = description;
        });

        $('#uploadedDescriptions').val(JSON.stringify(descriptions));

        var form = $(this);
        var formData = new FormData(this);

        $.ajax({
            url: form.attr('action'),
            type: form.attr('method'),
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-Token': csrfToken
            },
            success: function(response) {
                if (response.success) {
                    $('#file_upload_modal').modal('hide');
                    swalInit.fire(
                        "success",
                        response.message,
                        "success"
                    );
                    myDropzone.removeAllFiles();
                    var fileUploadTable = $('#file_upload_table').DataTable();
                    response.data.files.forEach(function(file) {

                        var fileExtension = file.file_name.split('.').pop().toLowerCase();

                        // Set the appropriate image URL based on the file type
                        var fileImageUrl;
                        if (['jpg', 'jpeg', 'png', 'gif'].includes(fileExtension)) {
                            fileImageUrl = file.url;
                        } else if (fileExtension === 'pdf') {
                            fileImageUrl = baseUrl + '/assets/images/pdf-icon.png';
                        } else {
                            fileImageUrl = baseUrl + '/assets/images/avatar.jpg';
                        }

                        fileUploadTable.row.add([
                            `<img src='${fileImageUrl}' width="40" height="40" alt="img">`,
                            `<p>
                            <a href="javascript:void(0);" 
                                class="open-modal"
                                data-file="${file.url}">
                                ${file.file_name}
                            </a>
                         </p>
                         <p>${file.description}</p>`,
                            `${(file.file_size / 1024).toFixed(2)} KB`,
                            `${file.uploaded_by}`,
                            `<a href="${file.url}" 
                            class="text-success"
                            escape="false"
                            id="${file.id}"
                            data-bs-popup="tooltip"
                            data-bs-placement="top"
                            title="Download"
                            download="${file.file_name}"
                            target="_blank">
                            <i class="ph-download-simple"></i>
                        </a>
                        <a href="#" 
                            class="text-danger sweet_warning"
                            escape="false"
                            id="${file.id}"
                            data-bs-popup="tooltip"
                            data-bs-placement="top"
                            title="Delete"
                            data-record-id="${file.id}"
                            data-url="${file.deleteUrl}"
                            data-confirm="Are you sure?">
                            <i class="ph-trash"></i>
                        </a>`
                        ]).draw();
                    });
                    init();
                }
            },
            error: function(xhr, status, error) {
                swalInit.fire(
                    "error",
                    'An error occurred while uploading files.',
                    "error"
                );
            }
        });
    });

    $(document).ready(function() {
        init();
    });

    function init() {
        $('.open-modal').on('click', function() {
            var fileUrl = $(this).data('file');

            $('#fileIframe').attr('src', fileUrl);

            $('#showFileModal').modal('show');
        });
    }
</script>

<script>
    $(document).ready(function() {
        $('#profileButton').click(function(e) {
            e.preventDefault();
            $('#profileImageInput').click();
        });

        $('#profileImageInput').change(function() {
            var file = this.files[0];
            if (file) {
                var formData = new FormData();
                formData.append('profile_image', file);
                formData.append('id', <?= $user->id ?>);

                var img = new Image();
                img.onload = function() {
                    if (this.width > 200 || this.height > 200) {
                        swalInit.fire(
                            "error",
                            "Image dimensions should not exceed 200x200 pixels.",
                            "error"
                        );
                    } else {

                        $.ajax({
                            url: '<?= $this->Url->build(['controller' => 'Users', 'action' => 'uploadProfileImage']); ?>',
                            type: 'POST',
                            headers: {
                                'X-CSRF-Token': csrfToken
                            },
                            data: formData,
                            contentType: false,
                            processData: false,
                            success: function(response) {
                                if (typeof response === "string") {
                                    response = JSON.parse(response);
                                }
                                if (response.success) {
                                    swalInit.fire(
                                        "success",
                                        "Image uploaded successfully",
                                        "success"
                                    );
                                    $('#imagePreview').html(
                                        '<img src="' + baseUrl + '/profile_img_uploads/' + response.data.filename +
                                        '" class="rounded-circle" id="profileCropImage" alt="profile-img" width="150" height="150" />'
                                    );
                                } else {
                                    swalInit.fire(
                                        "error",
                                        response.message,
                                        "error"
                                    );
                                }
                            },
                            error: function() {
                                swalInit.fire(
                                    "error",
                                    'There was an error uploading the image.',
                                    "error"
                                );
                            }
                        });
                    }
                };
                img.src = URL.createObjectURL(file);
            }
        });
    });
</script>

<script>
    $(document).ready(function() {
        var $modal = $('#profile_crop_modal');
        var image = document.getElementById('sample_image');
        var cropper;

        $('#profile_crop_button').click(function(e) {
            e.preventDefault();
            $('#cropProfileImageInput').click();

        });

        $('#cropProfileImageInput').change(function(event) {
            var files = event.target.files;

            if (files && files.length > 0) {
                reader = new FileReader();
                reader.onload = function(event) {
                    image.src = reader.result;
                    $modal.modal('show');
                };
                reader.readAsDataURL(files[0]);
            }
        });

        $modal.on('shown.bs.modal', function() {
            if (cropper) {
                cropper.destroy();
            }
            cropper = new Cropper(image, {
                aspectRatio: NaN,
                viewMode: 0,
                preview: '.preview',
                movable: true,
            });
        }).on('hidden.bs.modal', function() {
            cropper.destroy();
            cropper = null;
            image.src = '';
        });

        $('#zoomin').click(function() {
            if (cropper) {
                cropper.zoom(0.1);
            }
        });

        $('#zoomout').click(function() {
            if (cropper) {
                cropper.zoom(-0.1);
            }
        });

        $('#rotateleft').click(function() {
            if (cropper) {
                cropper.rotate(-45);
            }
        });

        $('#rotateright').click(function() {
            if (cropper) {
                cropper.rotate(45);
            }
        });

        $('#scalex').click(function() {
            if (cropper) {
                cropper.scaleX(-1);
            }
        });

        $('#scaley').click(function() {
            if (cropper) {
                cropper.scaleY(-1);
            }
        });

        $('#aspres169').click(function() {
            if (cropper) {
                cropper.setAspectRatio(16 / 9);
            }
        });

        $('#aspres43').click(function() {
            if (cropper) {
                cropper.setAspectRatio(4 / 3);
            }
        });

        $('#aspres11').click(function() {
            if (cropper) {
                cropper.setAspectRatio(1 / 1);
            }
        });

        $('#aspres23').click(function() {
            if (cropper) {
                cropper.setAspectRatio(2 / 3);
            }
        });

        $('#aspresfree').click(function() {
            if (cropper) {
                cropper.setAspectRatio(NaN);
            }
        });

        $('#crop').click(function() {
            if (cropper) {
                canvas = cropper.getCroppedCanvas();

                canvas.toBlob(function(blob) {
                    url = URL.createObjectURL(blob);
                    var reader = new FileReader();
                    reader.readAsDataURL(blob);
                    reader.onloadend = function() {
                        var base64data = reader.result;

                        $.ajax({
                            url: '<?= $this->Url->build(['controller' => 'Users', 'action' => 'saveCropProfileImage']) ?>',
                            method: 'POST',
                            headers: {
                                'X-CSRF-Token': csrfToken
                            },
                            data: {
                                image: base64data,
                                id: <?= $user->id ?>
                            },
                            success: function(data) {
                                if (typeof data === "string") {
                                    data = JSON.parse(data);
                                }
                                $('#profileCropImage').attr('src', data.data.imageUrl);
                                $modal.modal('hide');
                                swalInit.fire(
                                    "success",
                                    "Image uploaded successfully",
                                    "success"
                                );
                            }
                        });
                    };
                });
            }
        });
    });
</script>
<?php $this->end(); ?>