<?php include 'header.tpl.php'; ?>
<h1><?= $this->ref->getShortName(); ?></h1>
<p><?= $this->getComment()->getLicense(); ?></p>
<h2>Description</h2>
<p><?= $this->getComment()->getDescription(); ?></p>
<h2>Tests</h2>
<h3>Complexity</h3>
<p>Cyclomatic complexity is a software metric (measurement), used to indicate the complexity of a program. It is a quantitative measure of the number of linearly independent paths through a program's source code.</p>
<div class="meter orange"><span style="width: <?= $this->getPercentage($this->coverage['complexity'] ?? null); ?>%"></span></div>
<h3>Coverage</h3>
<p>In computer science, code coverage is a measure used to describe the degree to which the source code of a program is tested by a particular test suite. A program with high code coverage has been more thoroughly tested and has a lower chance of containing software bugs than a program with low code coverage. The inteded code coverage is 90%.</p>
<div class="meter orange"><span style="width: <?= $this->getCoverageRatio(); ?>%"></span></div>
<h2>Overview</h2>
<pre><?= $this->getTop(); ?><?= "\n{"; ?>
<?php foreach($this->getConst() as $const) { echo "\n" . $const; } echo "\n"; ?>
<?php foreach($this->getMembers() as $member) { echo "\n" . $member; } echo "\n"; ?>
<?php foreach($this->getMethods() as $methods) { echo "\n" . $methods; } echo "\n}"; ?>
</pre>
<?php include 'footer.tpl.php'; ?>