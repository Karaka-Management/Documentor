<?php
$phar = new \Phar(__DIR__, \FilesystemIterator::CURRENT_AS_FILEINFO | \FilesystemIterator::KEY_AS_FILENAME, 'documentor.phar');
$phar->buildFromDirectory(__DIR__ . '/src');
$phar->setStub($phar->createDefaultStub('src/index.php'));
