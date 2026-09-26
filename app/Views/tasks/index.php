<section class="page-intro page-intro--tasks">
    <p class="eyebrow">Task directory</p>
    <h1>All tasks</h1>
    <p class="intro-copy">Every task in the database, ordered from the earliest date to the latest.</p>
</section>

<section aria-labelledby="all-tasks-title">
    <div class="section-heading">
        <h2 id="all-tasks-title">Task list</h2>
        <span class="count-label"><?= count($tasks) ?> <?= count($tasks) === 1 ? 'task' : 'tasks' ?></span>
    </div>

    <?php if ($tasks === []): ?>
        <div class="empty-state"><p>There are no tasks in the database yet.</p></div>
    <?php else: ?>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th scope="col">Task</th>
                        <th scope="col">Scheduled date</th>
                        <th scope="col">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($tasks as $task): ?>
                        <tr>
                            <td class="task-title"><?= esc($task['title']) ?></td>
                            <td><?= esc(date('M j, Y', strtotime($task['task_date']))) ?></td>
                            <td>
                                <span class="status-label" data-status="<?= esc($task['status'], 'attr') ?>">
                                    <?= esc(ucwords(str_replace('_', ' ', $task['status']))) ?>
                                </span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</section>
