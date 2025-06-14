<?php

/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\Client> $clients
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
            <h5 class="py-sm-2 my-sm-1">Clients List</h5>
            <div class="d-flex">
                <?php if ($this->User->hasPermission('Clients', 'add')): ?>
                    <?= $this->Html->link(
                        __('Add New') . ' <i class="ph-plus-circle ms-2"></i>',
                        ['action' => 'add'],
                        [
                            'class' => 'btn btn-primary',
                            'escape' => false,
                            'role' => 'button'
                        ]
                    ) ?>
                <?php endif; ?>

                <?php if ($this->User->hasPermission('Clients', 'delete')): ?>
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
                        <?php if ($this->User->hasPermission('Clients', 'delete')): ?>
                            <input type="checkbox" class="form-check-input form-check-input-secondary" id="select_all">
                        <?php endif; ?>
                    </th>
                    <th width="15%">Name</th>
                    <th width="15%">Email</th>
                    <th width="10%">Phone</th>
                    <th width="12%">Country</th>
                    <th width="12%">Source</th>
                    <th width="8%" class="text-center">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($clients as $client): ?>
                    <tr>
                        <td>
                            <?php if ($this->User->hasPermission('Clients', 'delete')): ?>
                                <input type="checkbox" class="select_checkbox form-check-input form-check-input-secondary" value="<?= $client->id ?>">
                            <?php endif; ?>
                        </td>
                        <td>
                            <?= $this->Html->link(h($client->first_name) . ' ' . h($client->last_name), ['action' => 'edit', $client->id], ['class' => 'text-secondary', 'data-bs-popup' => 'tooltip', 'data-bs-placement' => 'top', 'title' => 'Edit', 'escape' => false]) ?>
                        </td>
                        <td>
                            <a href="mailto:<?= !empty($client->email) ? $client->email : $client->alt_email ?>"><?= !empty($client->email) ? $client->email : $client->alt_email ?></a>
                        </td>
                        <td><?= h($client->phone_no) ?></td>
                        <td>
                            <?php if (!empty($client->country)): ?>
                                <?= h($client->country_entity->name) ?>
                            <?php else: ?>
                                No country assigned
                            <?php endif; ?>
                        </td>
                        <td><?= h($client->lead_sources->title) ?></td>
                        <td class="actions text-center">
                            <?php if ($this->User->hasPermission('Clients', 'edit')): ?>
                                <?= $this->Html->link(__('<i class="ph-note-pencil"></i>'), ['action' => 'edit', $client->id], ['class' => 'text-secondary', 'data-bs-popup' => 'tooltip', 'data-bs-placement' => 'top', 'title' => 'Edit', 'escape' => false]) ?>
                            <?php endif; ?>

                            <?php if ($this->User->hasPermission('Clients', 'delete')): ?>
                                <?= $this->Html->link(
                                    __('<i class="ph-trash"></i>'),
                                    '#',
                                    [
                                        'class' => 'text-danger sweet_warning',
                                        'escape' => false,
                                        'id' => $client->id,
                                        'data-bs-popup' => 'tooltip',
                                        'data-bs-placement' => 'top',
                                        'title' => 'Delete',
                                        'data-record-id' => $client->id,
                                        'data-url' => $this->Url->build(['action' => 'delete', $client->id]),
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