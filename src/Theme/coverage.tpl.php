<?php include 'header.tpl.php'; ?>
<h1>Coverage</h1>
<table>
	<tbody>
		<tr><th>Classes<td><?= $this->classes; ?>
		<tr><th>Covered Classes<td><?= $this->coveredClasses; ?>
		<tr><th>Methods<td><?= $this->methods; ?>
		<tr><th>Covered Methods<td><?= $this->coveredMethods; ?>
		<tr><th>Complexity<td><?= $this->complexity; ?>
		<tr><th>CRAP<td><?= $this->crap; ?>
</table>
<h2>Covered Classes</h2>
<div class="meter orange"><span style="width: <?= (int) ($this->coveredClasses/$this->classes * 100); ?>%"></span></div>
<?= $this->coveredClasses; ?>/<?= $this->classes; ?> 
<h2>Covered Methods</h2>
<div class="meter orange"><span style="width: <?= (int) ($this->coveredMethods/$this->methods * 100); ?>%"></span></div>
<?= $this->coveredMethods; ?>/<?= $this->methods; ?> 
<?php include 'footer.tpl.php'; ?>