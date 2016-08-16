<?php include 'header.tpl.php'; ?>
<h1><?= $this->ref->getShortName(); ?></h1>
<p><?= $this->getComment()->getLicense(); ?></p>
<h2>Description</h2>
<p><?= $this->getComment()->getDescription(); ?></p>
<h2>UnitTests</h2>
<h2>CodeCoverage</h2>
<h3>Complexity</h3>
<div class="meter orange"><span style="width: <?= $this->getPercentage($this->coverage['complexity'] ?? null); ?>%"></span></div>
<p><?= $this->coverage['complexity'] ?? 0; ?></p>
<h3>Coverage</h3>
<div class="meter orange"><span style="width: <?= $this->getCoverageRatio(); ?>%"></span></div>
<p><?= $this->coverage['coveredmethods'] ?? 0; ?>/<?= $this->coverage['methods'] ?? 0; ?></p>
<h2>Overview</h2>
<pre><?= $this->getTop(); ?><?= "\n{"; ?>
<?php foreach($this->getMembers() as $member) { echo "\n" . $member; } echo "\n"; ?>
<?php foreach($this->getMethods() as $methods) { echo "\n" . $methods; } echo "\n}"; ?>
</pre>
<?php include 'footer.tpl.php'; ?>