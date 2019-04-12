<!DOCTYPE html>
<html lang="en">
<?php require_once ROOT . '/app/views/components/header.php'; ?>
<body>
<div class="container-scroller">
    <?php require_once ROOT . '/app/views/components/navbar.php'; ?>
    <div class="container-fluid page-body-wrapper pl-0 pr-0">
        <?php require_once ROOT . '/app/views/components/sidebar.php'; ?>
        <?php require_once $pageContent; ?>
    </div>
</div>
<?php include_once ROOT . '/app/views/components/footer.php'; ?>
</body>
</html>