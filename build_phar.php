<?php
$phar = new \Phar(__DIR__ . '/documentor.phar', FilesystemIterator::CURRENT_AS_FILEINFO | FilesystemIterator::KEY_AS_FILENAME, 'documentor.phar');
$phar->startBuffering();
$phar->setStub('<?php Phar::mapPhar(); include "phar://documentor.phar/index.php"; __HALT_COMPILER(); ?>');
$phar->buildFromDirectory(__DIR__ . '/src');
$phar->buildFromDirectory(__DIR__ . '/../phpOMS');
$phar->stopBuffering();
