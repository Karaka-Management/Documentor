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
		<tr><th>CRAP<td><?= $this->crap; ?>
</table>
<div class="clear"></div>
<h2>Covered Classes</h2>
<p>The ratio of classes that have a 100% code coverage. Higher means better!</p>
<div class="meter orange"><span style="width: <?= (int) ($this->coveredClasses/$this->classes * 100); ?>%"></span></div>
<h2>Covered Methods</h2>
<p>The ratio of methods that have a 100% code coverage. Higher means better!</p>
<div class="meter orange"><span style="width: <?= (int) ($this->coveredMethods/$this->methods * 100); ?>%"></span></div>
<h2>Lists</h2>
<table class="floatLeft">
	<thead class="floatLeft">
		<caption>Uncovered classes</caption>
	<tbody>
		<tr><td>1<td>
		<tr><td>2<td>
		<tr><td>3<td>
		<tr><td>4<td>
		<tr><td>5<td>
</table>
<table class="floatLeft">
	<thead class="floatLeft">
		<caption>Uncovered methods</caption>
	<tbody>
		<tr><td>1<td>
		<tr><td>2<td>
		<tr><td>3<td>
		<tr><td>4<td>
		<tr><td>5<td>
</table>
<table class="floatLeft">
	<thead class="floatLeft">
		<caption>CRAP classes</caption>
	<tbody>
		<tr><td>1<td>
		<tr><td>2<td>
		<tr><td>3<td>
		<tr><td>4<td>
		<tr><td>5<td>
</table>
<table class="floatLeft">
	<thead class="floatLeft">
		<caption>CRAP methods</caption>
	<tbody>
		<tr><td>1<td>
		<tr><td>2<td>
		<tr><td>3<td>
		<tr><td>4<td>
		<tr><td>5<td>
</table>
<div class="clear"></div>
<p>The lists above contain the top 5 classes and methods recommended for improvements. More detailed class and method inspections should be performed by going into the code coverage reports. Lower means better!</p>
<?php include 'footer.tpl.php'; ?>