<?php include 'header.tpl.php'; ?>
<h1>Stats</h1>
<table class="full">
    <caption>General</caption>
    <tbody>
    <tr><td>Classes<td>Interfaces<td>Abstract<td>Traits<td>Methods<td>LOC
    <tr><td><?= $this->stats['classes']; ?><td><?= $this->stats['interfaces']; ?><td><?= $this->stats['abstracts']; ?><td><?= $this->stats['traits']; ?><td><?= $this->stats['methods']; ?><td><?= $this->stats['loc']; ?>
</table>
<h1>Methods without comments</h1>
<table class="full">
    <caption>Methods</caption>
    <tbody>
    <?php foreach($this->withoutComment as $method) : ?>
    <tr><td><a href="<?= $this->base . '/'. $method; ?>.html"><?= $method; ?></a>
    <?php endforeach; ?>
</table>
<?php include 'footer.tpl.php'; ?>