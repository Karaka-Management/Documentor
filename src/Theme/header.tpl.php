<?php include 'head.tpl.php'; ?>
<div class="grad grad-main"></div>
<div id="header-top" class="cont">
    <div id="logo">
        <img width="70px" src="<?= $this->base; ?>/img/main.png">
    </div>
    <div id="logo-name">Orange Management</div>
    <nav>
        <ul>
            <li class="<?= $this->section === '' ? 'active' : ''; ?>"><a href="<?= $this->base; ?>/index.html">MAIN</a><li class="<?= $this->section === 'Guide' ? 'active' : ''; ?>"><a href="<?= $this->base; ?>/guide/index.html">GUIDE</a><li class="<?= $this->section === 'Documentation' ? 'active' : ''; ?>"><a href="<?= $this->base; ?>/tableOfContents.html">DOCS</a><li class="<?= $this->section === 'Test' ? 'active' : ''; ?>"><a href="<?= $this->base; ?>/test.html">TEST</a><li class="<?= $this->section === 'Coverage' ? 'active' : ''; ?>"><a href="<?= $this->base; ?>/coverage.html">COVERAGE</a>
        </ul>
    </nav>
</div>
<header>
    <div class="cont">
        <div id="header-info">
            <h1><?= $this->section; ?></h1>
            <div id="header-search-box">
                <div class="holder"><input id="search"></div>
                <ul id="search-result"></ul>
            </div>
        </div>
    </div>
    <div style="clear: both;"></div>
</header>
<div class="grad grad-main"></div>
<main><div class="cont">