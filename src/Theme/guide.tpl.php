<?php declare(strict_types=1);
include 'header.tpl.php'; ?>
<nav><?= $this->getNavigation(); ?></nav>
<?= $this->content; ?>
<?php include 'footer.tpl.php'; ?>
