<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= esc($title) ?> | Tasks for Today</title>
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
</head>
<body>
    <header class="site-header">
        <div class="container header-inner">
            <a class="brand" href="<?= base_url('/') ?>">Tasks for Today</a>
            <nav aria-label="Main navigation">
                <a class="<?= $activePage === 'today' ? 'active' : '' ?>" href="<?= base_url('/') ?>" <?= $activePage === 'today' ? 'aria-current="page"' : '' ?>>Today</a>
                <a class="<?= $activePage === 'tasks' ? 'active' : '' ?>" href="<?= base_url('tasks') ?>" <?= $activePage === 'tasks' ? 'aria-current="page"' : '' ?>>All tasks</a>
                <a class="<?= $activePage === 'profile' ? 'active' : '' ?>" href="<?= base_url('profile') ?>" <?= $activePage === 'profile' ? 'aria-current="page"' : '' ?>>Profile</a>
                <a class="<?= $activePage === 'about' ? 'active' : '' ?>" href="<?= base_url('about') ?>" <?= $activePage === 'about' ? 'aria-current="page"' : '' ?>>About</a>
                <?php if (session('task_user_id')): ?>
                    <a class="nav-manage" href="<?= site_url('tasks/new') ?>">New task</a>
                    <form class="nav-logout" method="post" action="<?= site_url('logout') ?>">
                        <?= csrf_field() ?>
                        <button type="submit">Log out</button>
                    </form>
                <?php else: ?>
                    <a class="nav-manage <?= $activePage === 'login' ? 'active' : '' ?>" href="<?= site_url('login') ?>">Sign in</a>
                <?php endif; ?>
            </nav>
        </div>
    </header>
    <main class="container main-content">
