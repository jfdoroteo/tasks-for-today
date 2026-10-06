<?php
$editing = $task !== null;
$errors = session('task_form_errors') ?? [];
$values = session('task_form_values') ?? [];
$fieldValue = static function (string $field, string $fallback = '') use ($values): string {
    $value = $values[$field] ?? $fallback;

    return is_scalar($value) ? (string) $value : '';
};
$action = $editing ? site_url('tasks/' . $task['id']) : site_url('tasks');
?>

<section class="page-intro page-intro--tasks page-intro--form">
    <p class="eyebrow">Task management</p>
    <h1><?= $editing ? 'Edit task' : 'New task' ?></h1>
    <p class="intro-copy"><?= $editing ? 'Update the details as your plan changes.' : 'Add a task to your schedule.' ?></p>
</section>

<section class="form-card" aria-label="Task details">
    <?php if ($errors !== []): ?><p class="form-alert" role="alert">Please check the fields below.</p><?php endif; ?>
    <?php if (session('task_form_error')): ?><p class="form-alert" role="alert"><?= esc(session('task_form_error')) ?></p><?php endif; ?>

    <form class="task-form" method="post" action="<?= esc($action, 'attr') ?>">
        <?= csrf_field() ?>
        <div class="form-field">
            <label for="title">Task title <span aria-hidden="true">*</span></label>
            <input id="title" name="title" type="text" maxlength="150" required value="<?= esc($fieldValue('title', $task['title'] ?? ''), 'attr') ?>">
            <?php if (isset($errors['title'])): ?><p class="field-error"><?= esc($errors['title']) ?></p><?php endif; ?>
        </div>
        <div class="form-grid">
            <div class="form-field">
                <label for="task_date">Scheduled date <span aria-hidden="true">*</span></label>
                <input id="task_date" name="task_date" type="date" required value="<?= esc($fieldValue('task_date', $task['task_date'] ?? $today), 'attr') ?>">
                <?php if (isset($errors['task_date'])): ?><p class="field-error"><?= esc($errors['task_date']) ?></p><?php endif; ?>
            </div>
            <div class="form-field">
                <label for="status">Status</label>
                <select id="status" name="status" required>
                    <?php foreach (['pending' => 'Pending', 'in_progress' => 'In progress', 'completed' => 'Completed'] as $statusValue => $statusLabel): ?>
                        <option value="<?= esc($statusValue, 'attr') ?>" <?= $fieldValue('status', $task['status'] ?? 'pending') === $statusValue ? 'selected' : '' ?>><?= esc($statusLabel) ?></option>
                    <?php endforeach; ?>
                </select>
                <?php if (isset($errors['status'])): ?><p class="field-error"><?= esc($errors['status']) ?></p><?php endif; ?>
            </div>
        </div>
        <div class="form-actions">
            <button class="button button--primary" type="submit"><?= $editing ? 'Save changes' : 'Add task' ?></button>
            <a class="button button--secondary" href="<?= site_url('tasks') ?>">Cancel</a>
        </div>
    </form>
</section>
