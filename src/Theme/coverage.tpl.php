<?php include 'header.tpl.php'; ?>
<h1>Coverage</h1>
<table class="floatLeft">
	<tbody>
		<tr><th>Classes<td><?= $this->classes; ?>
		<tr><th>Covered<td><?= $this->coveredClasses; ?>
</table>
<table class="floatLeft">
	<tbody>
		<tr><th>Methods<td><?= $this->methods; ?>
		<tr><th>Covered<td><?= $this->coveredMethods; ?>
</table>
<table class="floatLeft">
	<tbody>
		<tr><th>Complexity<td><?= $this->complexity; ?>
		<tr><th>CRAP<td><?= (int) $this->crap; ?>
</table>
<div class="clear"></div>
<h2>Covered Classes</h2>
<p>The ratio of classes that have a 100% code coverage. Higher means better!</p>
<div class="meter orange"><span style="width: <?= (int) ($this->coveredClasses/$this->classes * 100); ?>%"></span></div>
<h2>Covered Methods</h2>
<p>The ratio of methods that have a 100% code coverage. Higher means better!</p>
<div class="meter orange"><span style="width: <?= (int) ($this->coveredMethods/$this->methods * 100); ?>%"></span></div>
<h2>Lists</h2>
<table class="full">
	<thead>
		<caption>Uncovered classes</caption>
	<tbody>
	<?php foreach($this->uncoveredClasses as $class) : ?>
		<tr><td><?= $class['uncovered']; ?><td><a href="<?= $this->base . '/' . str_replace('\\', '/', $class['class']) . '.html' ; ?>"><?= $class['class']; ?></a>
	<?php endforeach; ?>
</table>
<table class="full">
	<thead>
		<caption>CRAP classes</caption>
	<tbody>
	<?php foreach($this->crapClasses as $class) : ?>
		<tr><td><?= (int) $class['crap']; ?><td><a href="<?= $this->base . '/' . str_replace('\\', '/', $class['class']) . '.html' ; ?>"><?= $class['class']; ?></a>
	<?php endforeach; ?>
</table>
<table class="full">
	<thead>
		<caption>CRAP methods</caption>
	<tbody>
	<?php foreach($this->crapMethods as $method) : ?>
		<tr><td><?= (int) $method['crap']; ?><td><a href="<?= $this->base . '/' . str_replace('\\', '/', $method['class']) . '-' . $method['method'] . '.html' ; ?>"><?= $method['class'] . '-' . $method['method']; ?></a>
	<?php endforeach; ?>
</table>
<div class="clear"></div>
<p>The lists above contain the top classes and methods recommended for improvements. More detailed class and method inspections should be performed by going into the code coverage reports. Lower means better!</p>
<?php include 'footer.tpl.php'; ?>