<?php include 'header.tpl.php'; ?>
<h1><?= $this->ref->getDeclaringClass()->getShortName(); ?> ~ <?= $this->ref->getShortName(); ?></h1>
<p>@since: <?= $this->getComment()->getSince(); ?>; @author: <?= $this->getComment()->getAuthor(); ?>; @class: <?= $this->getClassLink(); ?></p>
<h2>Function</h2>
<pre><?= $this->getMethod(); ?></pre>
<pre><?= $this->getComment()->getDescription(); ?></pre>
<h2>Parameters</h2>
<?php $params = $this->getComment()->getParameters(); foreach($params as $param) : ?>
    <?= $param['type']; ?> <?= $param['var']; ?> <?= $param['desc']; ?>
<?php endforeach; ?>
<h2>Return</h2>
<p><?= $this->ref->hasReturnType() ? $this->ref->getReturnType() : $this->getComment()->getReturn(); ?></p>
<h2>Throws</h2>
<p><?= $this->getComment()->getThrows(); ?></p>
<h2>UnitTests</h2>
<h3>Complexity</h3>
<div class="meter red"><span style="width: <?= (int) log($this->coverage['complexity'] ?? 0); ?>%"></span></div>
<p><?= $this->coverage['complexity'] ?? 0; ?></p>
<h3>Crap</h3>
<div class="meter red"><span style="width: <?= (int) log($this->coverage['crap'] ?? 0); ?>%"></span></div>
<p><?= $this->coverage['crap'] ?? 0; ?></p>
<h2>CodeCoverage</h2>
<h2>Examples</h2>
<pre>
</pre>
<?php include 'footer.tpl.php'; ?>