<?php include 'header.tpl.php'; ?>
<h1><?= $this->ref->getShortName(); ?></h1>
<p><?= $this->getComment()->getLicense(); ?></p>
<h2>Description</h2>
<p><?= $this->getComment()->getDescription(); ?></p>
<h2>UnitTests</h2>
<h2>CodeCoverage</h2>
<p>Coverage: <?= $this->coverage['coveredmethods'] ?? ''; ?>/<?= $this->coverage['methods'] ?? ''; ?></p>
<p>Complexity: <?= $this->coverage['complexity'] ?? 'nope'; ?></p>
<h2>Overview</h2>
<pre><?= $this->getTop(); ?><?= "\n{"; ?>
<?php foreach($this->getMembers() as $member) { echo "\n" . $member; } echo "\n"; ?>
<?php foreach($this->getMethods() as $methods) { echo "\n" . $methods; } echo "\n}"; ?>
</pre>
<?php include 'footer.tpl.php'; ?>