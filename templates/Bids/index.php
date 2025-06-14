<?php

/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\Bid> $bids
 */
?>

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
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center py-sm-0">
            <h5 class="py-sm-2 my-sm-1">Bid List</h5>
            <div class="d-flex">
                <?php if ($this->User->hasPermission('Bids', 'add')): ?>
                    <?= $this->Html->link(
                        __('Add New') . ' <i class="ph-plus-circle ms-2"></i>',
                        '#addNewModal',
                        [
                            'class' => 'btn btn-primary',
                            'escape' => false,
                            'role' => 'button',
                            'data-bs-toggle' => 'modal',
                            'data-bs-target' => '#addNewModal'
                        ]
                    ) ?>
                <?php endif; ?>

                <?php if ($this->User->hasPermission('Bids', 'delete')): ?>
                    <?= $this->Form->create(null, ['url' => ['action' => 'delete'], 'id' => 'deleteSelectedForm']) ?>
                    <?= $this->Form->hidden('ids', ['id' => 'selectedIds']) ?>
                    <?= $this->Form->button(
                        __('Delete Selected') . ' <i class="ph-trash ms-1"></i>',
                        ['type' => 'button', 'class' => 'btn btn-danger ms-2', 'id' => 'delete_all', 'escapeTitle' => false]
                    ) ?>
                    <?= $this->Form->end() ?>
                <?php endif; ?>
            </div>
        </div>

        <table class="table datatable-basic table-striped">
            <thead>
                <tr>
                    <th width="2%">
                        <?php if ($this->User->hasPermission('Bids', 'delete')): ?>
                            <input type="checkbox" class="form-check-input form-check-input-secondary" id="select_all">
                        <?php endif; ?>
                    </th>
                    <th width="35%">URL</th>
                    <th width="15%">Source</th>
                    <th width="10%">Profile</th>
                    <th width="12%">Type</th>
                    <th width="12%">Rate</th>
                    <th width="8%" class="text-center">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($bids as $bid): ?>
                    <tr>
                        <td>
                            <?php if ($this->User->hasPermission('Bids', 'delete')): ?>
                                <input type="checkbox" class="select_checkbox form-check-input form-check-input-secondary" value="<?= $bid->id ?>">
                            <?php endif; ?>
                        </td>
                        <td>
                            <?= $this->Html->link(
                                __(substr($bid->url, 0, 100) . (strlen($bid->url) > 100 ? '...' : '')),
                                $bid->url,
                                [
                                    'data-bs-popup' => 'tooltip',
                                    'data-bs-placement' => 'top',
                                    'title' => $bid->url,
                                    'target' => '_blank'
                                ]
                            ) ?>
                        </td>
                        <td><?= h($bid->lead_sources->title) ?></td>
                        <td><?= h($bid->lead_profiles->title) ?></td>
                        <td>
                            <?php if ($bid->type == 'fixed'): ?>
                                <span class="badge bg-yellow">
                                    <?= ucfirst($bid->type) ?>
                                </span>
                            <?php elseif ($bid->type == 'hourly'): ?>
                                <span class="badge bg-secondary">
                                    <?= ucfirst($bid->type) ?>
                                </span>
                            <?php elseif ($bid->type == 'monthly'): ?>
                                <span class="badge bg-teal">
                                    <?= ucfirst($bid->type) ?>
                                </span>
                            <?php endif; ?>
                        </td>
                        <td><?= h($bid->rate) ?></td>
                        <td class="actions text-center">
                            <?php if ($this->User->hasPermission('Bids', 'convertToLead')): ?>
                                <?= $this->Html->link(
                                    __('<i class="ph-arrow-line-right"></i>'),
                                    ['action' => 'convertToLead', $bid->id],
                                    [
                                        'class' => 'text-primary',
                                        'data-bs-popup' => 'tooltip',
                                        'data-bs-placement' => 'top',
                                        'title' => 'Convert To Lead',
                                        'escape' => false
                                    ]
                                ) ?>
                            <?php endif; ?>

                            <?php if ($this->User->hasPermission('Bids', 'add')): ?>
                                <?= $this->Html->link(
                                    __('<i class="ph-note-pencil"></i>'),
                                    '#',
                                    [
                                        'class' => 'text-secondary edit-bid',
                                        'data-id' => $bid->id,
                                        'data-url' => $bid->url,
                                        'data-source' => $bid->source,
                                        'data-profile' => $bid->profile,
                                        'data-type' => $bid->type,
                                        'data-rate' => $bid->rate,
                                        'data-bs-toggle' => 'modal',
                                        'data-bs-target' => '#addNewModal',
                                        'escape' => false,
                                        'title' => 'Edit',
                                    ]
                                ) ?>
                            <?php endif; ?>

                            <?php if ($this->User->hasPermission('Bids', 'delete')): ?>
                                <?= $this->Html->link(
                                    __('<i class="ph-trash"></i>'),
                                    '#',
                                    [
                                        'class' => 'text-danger sweet_warning',
                                        'escape' => false,
                                        'id' => $bid->id,
                                        'data-bs-popup' => 'tooltip',
                                        'data-bs-placement' => 'top',
                                        'title' => 'Delete',
                                        'data-record-id' => $bid->id,
                                        'data-url' => $this->Url->build(['action' => 'delete', $bid->id]),
                                        'data-confirm' => 'Are you sure?',
                                    ]
                                ); ?>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="addNewModal" tabindex="-1" aria-labelledby="addNewModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addNewModalLabel">Add Bid</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <?= $this->Form->create(null, ['id' => 'addNewForm', 'type' => 'post']) ?>

                <?= $this->Form->hidden('id', ['id' => 'bidId']) ?>

                <div class="row">
                    <div class="col-lg-2">
                        <div class="mb-3">
                            <?= $this->Form->label('url', 'URL <span class="text-danger">*</span>', ['class' => 'form-label', 'escape' => false]) ?>
                        </div>
                    </div>
                    <div class="col-lg-10">
                        <div class="mb-3">
                            <?= $this->Form->text('url', [
                                'id' => 'bidUrl',
                                'class' => 'form-control',
                                'placeholder' => 'Enter URL',
                            ]) ?>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-2">
                        <div class="mb-3">
                            <?= $this->Form->label('source', 'Source <span class="text-danger">*</span>', ['class' => 'form-label', 'escape' => false]) ?>
                        </div>
                    </div>
                    <div class="col-lg-10">
                        <div class="mb-3">
                            <?= $this->Form->select('source', $sources, [
                                'class' => 'select form-select',
                                'id' => 'bidSource',
                                'empty' => '-- Select Source --',
                            ]); ?>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-2">
                        <div class="mb-3">
                            <?= $this->Form->label('profile', 'Profile <span class="text-danger">*</span>', ['class' => 'form-label', 'escape' => false]) ?>
                        </div>
                    </div>
                    <div class="col-lg-10">
                        <div class="mb-3">
                            <?= $this->Form->select('profile', $profiles, [
                                'class' => 'select form-select',
                                'id' => 'bidProfile',
                                'empty' => '-- Select Profile --',
                            ]); ?>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-2">
                        <div class="mb-3">
                            <?= $this->Form->label('type', 'Type <span class="text-danger">*</span>', ['class' => 'form-label', 'escape' => false]) ?>
                        </div>
                    </div>
                    <div class="col-lg-10">
                        <div class="mb-3">
                            <select name="type" id="bidType" class="select form-select">
                                <option value="">-- Select Type --</option>
                                <option value="fixed">Fixed</option>
                                <option value="hourly">Hourly</option>
                                <option value="monthly">Monthly</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-2">
                        <div class="mb-3">
                            <?= $this->Form->label('rate', 'Rate <span class="text-danger">*</span>', ['class' => 'form-label', 'escape' => false]) ?>
                        </div>
                    </div>
                    <div class="col-lg-10">
                        <div class="mb-3">
                            <?= $this->Form->number('rate', [
                                'id' => 'bidRate',
                                'class' => 'form-control',
                                'placeholder' => 'Enter Rate',
                            ]) ?>
                        </div>
                    </div>
                </div>

                <?= $this->Form->end() ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="saveNewData">Save</button>
            </div>
        </div>
    </div>
</div>

<script>
    function convertToLead(bidId) {
        $.ajax({
            url: "<?= $this->Url->build(['controller' => 'Bids', 'action' => 'convertToLead', '_full' => true]) ?>/" + bidId,
            type: 'POST',
            dataType: 'json',
            data: JSON.stringify({
                id: bidId
            }),
            contentType: 'application/json; charset=utf-8',
            headers: {
                'X-CSRF-Token': csrfToken
            },
            success: function(response) {
                if (response.success) {
                    window.location.href = response.redirect;
                } else {
                    alert('Failed to convert lead.');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
            }
        });
    }
</script>

<script type="text/javascript">
    $(document).ready(function() {
        $(document).on('click', '.edit-bid', function() {
            var id = $(this).data('id');
            var url = $(this).data('url');
            var source = $(this).data('source');
            var profile = $(this).data('profile');
            var type = $(this).data('type');
            var rate = $(this).data('rate');

            $('#addNewModalLabel').text('Edit bid');

            $('#bidId').val(id);
            $('#bidUrl').val(url);
            $('#bidSource').val(source);
            $('#bidProfile').val(profile);
            $('#bidType').val(type);
            $('#bidRate').val(rate);

            $('#addNewForm').attr('action', '<?= $this->Url->build(['controller' => 'Bids', 'action' => 'edit']) ?>/' + id);
        });

        $(document).on('click', '[data-bs-target="#addNewModal"]', function() {
            if (!$(this).hasClass('edit-bid')) {
                $('#addNewModalLabel').text('Add Bid');
                $('#addNewForm')[0].reset();
                $('#bidId').val('');
                $('.error-message').remove();
            } else {
                $('.error-message').remove();
            }
        });
    });
</script>

<script>
    $(document).ready(function() {
        // Open modal for adding a new leave type
        $('#saveNewData').on('click', function() {

            $('.error-message').remove();

            var isValid = true;

            var urlField = $('#bidUrl');
            var urlValue = urlField.val().trim();
            if (urlValue === '') {
                isValid = false;
                urlField.after('<div class="error-message text-danger">URL is required.</div>');
            }

            // Validate Source
            var sourceField = $('#bidSource');
            var sourceValue = sourceField.val();
            if (sourceValue === '') {
                isValid = false;
                sourceField.after('<div class="error-message text-danger">Source is required.</div>');
            }

            // Validate Profile
            var profileField = $('#bidProfile');
            var profileValue = profileField.val();
            if (profileValue === '') {
                isValid = false;
                profileField.after('<div class="error-message text-danger">Profile is required.</div>');
            }

            // Validate Type
            var typeField = $('#bidType');
            var typeValue = typeField.val();
            if (typeValue === '') {
                isValid = false;
                typeField.after('<div class="error-message text-danger">Type is required.</div>');
            }

            // Validate Rate
            var rateField = $('#bidRate');
            var rateValue = rateField.val().trim();
            if (rateValue === '') {
                isValid = false;
                rateField.after('<div class="error-message text-danger">Rate is required.</div>');
            } else if (isNaN(rateValue) || rateValue <= 0) { // Ensure rate is a positive number
                isValid = false;
                rateField.after('<div class="error-message text-danger">Please enter a valid rate.</div>');
            }

            if (isValid) {
                var formData = $('#addNewForm').serialize(); // Serialize the form data

                $.ajax({
                    url: '<?= $this->Url->build(['controller' => 'Bids', 'action' => 'add']) ?>',
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        if (response.success) {
                            $('#addNewModal').modal('hide');
                            location.reload(); // Reload the page or dynamically update content
                        } else {
                            alert(response.message);
                        }
                    },
                    error: function() {
                        alert('An error occurred while saving the data.');
                    }
                });
            }
        });
    });
</script>

<script>
    // Select all checkbox when select_all checkbox is checked
    document.addEventListener("DOMContentLoaded", function() {
        var selectAllCheckbox = document.getElementById("select_all");
        if (selectAllCheckbox) {
            selectAllCheckbox.onclick = function() {
                var checkboxes = document.querySelectorAll(".select_checkbox");
                for (var checkbox of checkboxes) {
                    checkbox.checked = this.checked;
                }
            };
        }
    });

    // If individul checkbox select then select_all checkbox unchecked
    var checkboxes = document.querySelectorAll(".select_checkbox");
    checkboxes.forEach(function(checkbox) {
        checkbox.addEventListener("change", function() {
            var allChecked = Array.from(checkboxes).every((cb) => cb.checked);
            document.getElementById("select_all").checked = allChecked;
        });
    });

    // Handle multiple deletions
    document.addEventListener('DOMContentLoaded', function() {
        var deleteAllButton = document.getElementById('delete_all');

        if (deleteAllButton) {
            deleteAllButton.onclick = function(event) {
                event.preventDefault();

                var selected = [];
                var checkboxes = document.querySelectorAll('.select_checkbox');

                checkboxes.forEach(function(checkbox) {
                    if (checkbox.checked && !checkbox.disabled) {
                        selected.push(checkbox.value);
                    }
                });

                if (selected.length > 0) {
                    deleteRecord(
                        $('#deleteSelectedForm').attr('action'), {
                            '_method': 'DELETE',
                            'ids': selected.join(',')
                        },
                        selected
                    );
                } else {
                    swalInit.fire('Oh Snap!', 'Please select some records to delete.', 'warning');
                }
            };
        }
    });
</script>