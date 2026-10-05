<?php

// Compatibility entry point for earlier deployment notes.
require dirname(__DIR__).'/vendor/autoload.php';
$app = require dirname(__DIR__).'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
$status = $kernel->call('media:build-responsive');
echo $kernel->output();
exit($status);
