<?php include 'header.tpl.php'; ?>
<h1>Coverage</h1>
<ul>
	<li>Covered Classes: <?= $this->coveredClasses; ?>/<?= $this->classes; ?> 
	<li>Covered Methods: <?= $this->coveredMethods; ?>/<?= $this->methods; ?> 
</ul>
<?php include 'footer.tpl.php'; ?>