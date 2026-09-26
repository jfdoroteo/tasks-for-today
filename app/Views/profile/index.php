<section class="page-intro page-intro--profile">
    <p class="eyebrow">Team member</p>
    <h1>Profile</h1>
    <p class="intro-copy">The demo account used for this task-management project.</p>
</section>

<?php if ($user === null): ?>
    <div class="empty-state"><p>No demo user was found in the database.</p></div>
<?php else: ?>
    <section class="profile-panel" aria-labelledby="profile-name">
        <div class="profile-header">
            <img
                class="profile-photo"
                src="https://ik.imagekit.io/nsi7x5vhz/818875ea-6837-4d9a-aeb6-f81138f53250.jpg"
                alt="Pixel-art portrait for the demo profile"
                width="132"
                height="132"
                loading="lazy"
            >
            <div>
                <p class="eyebrow">Demo user</p>
                <h2 id="profile-name"><?= esc($user['full_name']) ?></h2>
            </div>
        </div>
        <dl class="details-list">
            <div><dt>Username</dt><dd><?= esc($user['username']) ?></dd></div>
            <div><dt>Email</dt><dd><?= esc($user['email']) ?></dd></div>
            <div><dt>Account created</dt><dd><?= esc(date('M j, Y', strtotime($user['created_at']))) ?></dd></div>
        </dl>
    </section>
<?php endif; ?>
