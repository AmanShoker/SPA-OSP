<?php session_start(); ?>
<div id="welcome">
        <?php if (isset($_SESSION['username'])): ?>
            <h1>Welcome back, <?php echo $_SESSION['username']; ?>!</h1>
        <?php else: ?>
            <h1>Welcome to Our Site</h1>
        <?php endif; ?>
</div>