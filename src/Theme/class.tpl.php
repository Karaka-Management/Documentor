<?php include 'header.tpl.php'; ?>
<main>
    <h1><?= $this->ref->getShortName(); ?></h1>
    <p><?= $this->getComment()->getLicense(); ?></p>
    <h2>Description</h2>
    <p><?= 1//$this->getComment()->getDescription(); ?></p>
    <p></p>
    <h2>Class</h2>
    <pre><?= $this->getTop(); ?><?= "\n{"; ?>
    <?php foreach($this->getMembers() as $member) { echo "\n" . $member; } echo "\n"; ?>
    <?php foreach($this->getMethods() as $methods) { echo "\n" . $methods; } echo "\n}"; ?>
    </pre>
</main>
<?php include 'footer.tpl.php'; ?>