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
<h2>Tests</h2>
<h3>Complexity</h3>
<p>Cyclomatic complexity is a software metric (measurement), used to indicate the complexity of a program. It is a quantitative measure of the number of linearly independent paths through a program's source code.</p>
<div class="meter orange"><span style="width: <?= $this->getPercentage($this->coverage['complexity'] ?? null); ?>%"></span></div>
<h3>CRAP</h3>
<p>The Change Risk Anti-Patterns (CRAP) Index is calculated based on the cyclomatic complexity and code coverage of a unit of code. Code that is not too complex and has an adequate test coverage will have a low CRAP index. The CRAP index can be lowered by writing tests and by refactoring the code to lower its complexity.</p>
<div class="meter orange"><span style="width: <?= $this->getPercentage($this->coverage['crap'] ?? null); ?>%"></span></div>
<h2>Examples</h2>
<pre>
</pre>
<h2>Code</h2>
<pre><?= $this->code; ?></pre>
<?php include 'footer.tpl.php'; ?>