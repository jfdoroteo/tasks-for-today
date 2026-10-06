<section class="login-layout" aria-labelledby="login-title">
    <div class="login-panel">
        <p class="eyebrow">Task management</p>
        <h1 id="login-title">Welcome back.</h1>
        <p class="intro-copy">Sign in to add, edit, or archive tasks. Reading the task lists does not require an account.</p>

        <?php if (session('auth_notice')): ?><p class="notice" role="status"><?= esc(session('auth_notice')) ?></p><?php endif; ?>
        <?php if (session('auth_error')): ?><p class="form-alert" role="alert"><?= esc(session('auth_error')) ?></p><?php endif; ?>

        <form class="task-form" method="post" action="<?= site_url('login') ?>">
            <?= csrf_field() ?>
            <div class="form-field">
                <label for="username">Username</label>
                <input id="username" name="username" type="text" autocomplete="username" maxlength="50" required autofocus value="<?= esc((string) (session('login_username') ?? ''), 'attr') ?>">
            </div>
            <div class="form-field">
                <label for="password">Password</label>
                <input id="password" name="password" type="password" autocomplete="current-password" required>
            </div>
            <button class="button button--primary" type="submit">Sign in</button>
        </form>
    </div>
    <aside class="login-aside" aria-label="Task board preview">
        <span class="preview-orbit" aria-hidden="true"></span>
        <p class="eyebrow">Tasks for Today</p>
        <h2>Keep the day in view.</h2>
        <p>Plan what is next, update what changed, and set completed work aside.</p>
        <div class="preview-tasks" aria-hidden="true">
            <span><i></i> Plan the day</span>
            <span><i></i> Make progress</span>
            <span><i></i> Finish well</span>
        </div>
    </aside>
</section>
