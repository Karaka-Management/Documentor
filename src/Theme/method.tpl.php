<?php include 'header.tpl.php'; ?>
<main>
    <h1><?= $this->ref->getDeclaringClass()->getShortName(); ?> ~ <?= $this->ref->getShortName(); ?></h1>
    <p>@since: <?= $this->getComment()->getSince(); ?>; @author: <?= $this->getComment()->getAuthor(); ?></p>
    <h2>Function</h2>
    <p><?= $this->getMethod(); ?></p>
    <pre><?= $this->getComment()->getDescription(); ?></pre>
    <h2>Parameters</h2>
    <h2>Return</h2>
    <p><?= $this->ref->hasReturnType() ? $this->ref->getReturnType() : $this->getComment()->getReturn(); ?></p>
    <h2>Throws</h2>
    <p><?= $this->getComment()->getThrows(); ?></p>
    <h2>UnitTests</h2>
    <h2>CodeCoverage</h2>
    <h2>Examples</h2>
    <pre>
    </pre>
</main>
<?php include 'footer.tpl.php'; ?>