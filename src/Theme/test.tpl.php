<?php include 'header.tpl.php'; ?>
<h1>Stats</h1>
<table class="full">
	<caption>General</caption>
	<tbody>
		<tr><td>Tests<td>Success<td>Errors<td>Failures<td>Empty<td>Time (s)
		<tr><td><?= $this->test['tests']; ?><td><?= $this->test['assertions'] - $this->test['failures'] - $this->test['errors'] - $this->test['empty']; ?><td><?= $this->test['errors']; ?><td><?= $this->test['failures']; ?><td><?= $this->test['empty']; ?><td><?= round($this->test['time'], 2); ?>
</table>
<h1>Error</h1>
<table class="full">
    <thead>
    <caption>Errors</caption>
    <tbody>
    <?php foreach($this->results['errors'] as $error) : ?>
        <tr><td><?= $error['class']; ?>-<?= $error['method']; ?>
    <?php endforeach; ?>
</table>
<h1>Failure</h1>
<table class="full">
    <thead>
    <caption>Failures</caption>
    <tbody>
    <?php foreach($this->results['failures'] as $failure) : ?>
        <tr><td><?= $failure['class']; ?>-<?= $failure['method']; ?>
    <?php endforeach; ?>
</table>
<?php include 'footer.tpl.php'; ?>