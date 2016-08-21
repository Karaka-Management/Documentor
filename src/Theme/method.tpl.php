<?php include 'header.tpl.php'; ?>
<h1><?= $this->ref->getDeclaringClass()->getShortName(); ?> ~ <?= $this->ref->getShortName(); ?></h1>
<p><?= $this->getComment()->isDeprecated() ? '<span class="deprecated">@deprecated</span> ' : ''; ?>@since: <?= $this->getComment()->getSince(); ?>; @author: <?= $this->getComment()->getAuthor(); ?>; @class: <?= $this->getClassLink(); ?></p>
<?php $latex = $this->getComment->getLatex(); if(isset($latex)) : foreach($latex as $formula) : ?>
<p>$$<?= $formula; ?>$$</p>
<?php endforeach; endif; ?>
<h2>Function</h2>
<pre><?= $this->getMethod(); ?></pre>
<?php $description = $this->getComment()->getDescription(); if(!empty($description)) : ?>
<p><?= $description ?></p>
<?php endif; ?>
<?php $params = $this->getComment()->getParameters(); if(!empty($params)) : ?>
<h2>Parameters</h2>
<?php foreach($params as $param) : ?>
    <p><?= $this->formatType($param['type']); ?> <?= $this->formatVariable($param['var']); ?>:<br><?= $param['desc']; ?></p>
<?php endforeach; endif; ?>
<?php $return = $this->getComment()->getReturn(); if($this->ref->hasReturnType() || isset($return)) : ?>
<h2>Return</h2>
<p><?= $this->ref->hasReturnType() ? $this->linkType($this->ref->getReturnType()) : $this->formatType($return['type']); if(isset($return['desc'])) { echo ':<br>' . $return['desc']; } ?></p>
<?php endif; ?>
<?php $throws = $this->getComment()->getThrows(); if(!empty($throws)) : ?>
<h2>Throws</h2>
<?php foreach($throws as $throw) : ?>
    <p><?= $this->formatType($throw['type']); ?>:<br><?= $throw['desc']; ?><p>
<?php endforeach; endif; ?>
<h2>Tests</h2>
<h3>Complexity</h3>
<p>Cyclomatic complexity is a software metric (measurement), used to indicate the complexity of a program. It is a quantitative measure of the number of linearly independent paths through a program's source code.</p>
<div class="meter orange"><span style="width: <?= $this->getPercentage($this->coverage['complexity'] ?? null); ?>%"></span></div>
<h3>CRAP</h3>
<p>The Change Risk Anti-Patterns (CRAP) Index is calculated based on the cyclomatic complexity and code coverage of a unit of code. Code that is not too complex and has an adequate test coverage will have a low CRAP index. The CRAP index can be lowered by writing tests and by refactoring the code to lower its complexity.</p>
<div class="meter orange"><span style="width: <?= $this->getPercentage($this->coverage['crap'] ?? null); ?>%"></span></div>
<?php $examples = $this->getComment()->getExamples(); if(!empty($examples)) : ?>
<h2>Examples</h2>
<?php foreach($examples as $example) : ?>
	<pre><?= $example ?></pre>
<?php endforeach; endif; ?>
<h2>Code</h2>
<pre><?= $this->code; ?></pre>
<?php include 'footer.tpl.php'; ?>