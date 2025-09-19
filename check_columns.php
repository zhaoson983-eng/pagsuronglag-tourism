<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illware_Console_Kernel::class);
$app->boot();

$columns = \Illuminate\Support\Facades\Schema::getColumnListing('business_profiles');
print_r($columns);
