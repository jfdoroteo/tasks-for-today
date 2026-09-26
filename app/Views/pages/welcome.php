<section class="page-intro page-intro--today">
    <p class="eyebrow">Welcome</p>
    <h1>Today's tasks</h1>
    <p class="intro-copy">A focused list of the work scheduled for <?= esc($todayLabel) ?>.</p>
</section>

<section aria-labelledby="today-list-title">
    <div class="section-heading">
        <div>
            <p class="eyebrow">Daily overview</p>
            <h2 id="today-list-title">On the schedule</h2>
        </div>
        <span class="count-label"><?= count($tasks) ?> <?= count($tasks) === 1 ? 'task' : 'tasks' ?></span>
    </div>

    <?php if ($tasks === []): ?>
        <div class="empty-state">
            <p>No tasks are scheduled for today.</p>
            <a href="<?= base_url('tasks') ?>">See all tasks</a>
        </div>
    <?php else: ?>
        <ul class="task-list">
            <?php foreach ($tasks as $task): ?>
                <li class="task-row">
                    <span class="task-title"><?= esc($task['title']) ?></span>
                    <span class="status-label" data-status="<?= esc($task['status'], 'attr') ?>">
                        <?= esc(ucwords(str_replace('_', ' ', $task['status']))) ?>
                    </span>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <p class="section-note">Need a different date? <a href="<?= base_url('tasks') ?>">View the complete task list</a>.</p>
</section>
