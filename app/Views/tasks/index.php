<section class="page-intro page-intro--tasks">
    <p class="eyebrow">Task directory</p>
    <h1>All tasks</h1>
    <p class="intro-copy">Active tasks, ordered from the earliest date to the latest.</p>
</section>

<section aria-labelledby="all-tasks-title">
    <div class="section-heading">
        <h2 id="all-tasks-title">Task list</h2>
        <div class="heading-actions">
            <span class="count-label"><?= count($tasks) ?> <?= count($tasks) === 1 ? 'task' : 'tasks' ?></span>
            <?php if (session('task_user_id')): ?>
                <a class="button button--primary" href="<?= site_url('tasks/new') ?>">+ New task</a>
            <?php endif; ?>
        </div>
    </div>

    <?php if (session('notice')): ?><p class="notice" role="status"><?= esc(session('notice')) ?></p><?php endif; ?>
    <?php if (session('task_error')): ?><p class="form-alert" role="alert"><?= esc(session('task_error')) ?></p><?php endif; ?>

    <?php if ($tasks === []): ?>
        <div class="empty-state"><p>There are no active tasks yet.</p></div>
    <?php else: ?>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th scope="col">Task</th>
                        <th scope="col">Scheduled date</th>
                        <th scope="col">Status</th>
                        <?php if (session('task_user_id')): ?><th scope="col">Manage</th><?php endif; ?>
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
                            <?php if (session('task_user_id')): ?>
                                <td>
                                    <div class="row-actions">
                                        <a href="<?= site_url('tasks/' . $task['id'] . '/edit') ?>">Edit</a>
                                        <form method="post" action="<?= site_url('tasks/' . $task['id'] . '/archive') ?>" onsubmit="return confirm('Archive this task? It will disappear from the task lists.');">
                                            <?= csrf_field() ?>
                                            <button type="submit">Archive</button>
                                        </form>
                                    </div>
                                </td>
                            <?php endif; ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</section>
