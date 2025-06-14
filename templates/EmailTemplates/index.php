<!-- Content area -->
<div class="content">
    <div class="row">
        <div class="col-lg-2">
            <?= $this->element('inner-sidebar'); ?>
        </div>

        <div class="col-lg-10">
            <div class="card">
                <div class="card-header d-sm-flex align-items-sm-center py-sm-0">
                    <h5 class="py-sm-2 my-sm-1">Email Templates</h5>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table datatable-basic table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th width="30%">Name</th>
                                    <th width="30%">Slug</th>
                                    <th width="30%">Subject</th>
                                    <th width="10%">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($emailTemplates as $emailTemplate): ?>
                                    <tr>
                                        <td><?= h($emailTemplate->name) ?></td>
                                        <td><?= h($emailTemplate->slug) ?></td>
                                        <td><?= h($emailTemplate->subject) ?></td>
                                        <td class="actions">
                                            <?php if ($this->User->hasPermission('EmailTemplates', 'edit')): ?>
                                                <?= $this->Html->link(__('<i class="ph-note-pencil"></i>'), ['action' => 'edit', $emailTemplate->id], ['class' => 'text-secondary', 'data-bs-popup' => 'tooltip', 'data-bs-placement' => 'top', 'title' => 'Edit', 'escape' => false]) ?>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /content area -->