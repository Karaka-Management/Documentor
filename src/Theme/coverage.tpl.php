<?php include 'header.tpl.php'; ?>
<h1>Coverage</h1>
<h2>Covered Classes</h2>
<div class="meter orange"><span style="width: <?= (int) ($this->coveredClasses/$this->classes * 100); ?>%"></span></div>
<?= $this->coveredClasses; ?>/<?= $this->classes; ?> 
<h2>Covered Methods</h2>
<div class="meter orange"><span style="width: <?= (int) ($this->coveredMethods/$this->methods * 100); ?>%"></span></div>
<?= $this->coveredMethods; ?>/<?= $this->methods; ?> 
<?php include 'footer.tpl.php'; ?>