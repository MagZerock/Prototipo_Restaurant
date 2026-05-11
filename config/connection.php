<?php

use Illuminate\Database\Capsule\Manager as Capsule;

$capsule = new Capsule;

$capsule->addConnection([
    'driver'   => 'pgsql',
    'url'      => 'postgresql://postgres.teqgmzwpcwcelrpecdyq:VNcazVEpbUvpGyVJ@aws-1-us-east-1.pooler.supabase.com:5432/postgres',
    'charset'  => 'utf8',
    'prefix'   => '',
    'sslmode'  => 'require',
]);

$capsule->setAsGlobal();
$capsule->bootEloquent();
