<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo 'hasStatus:' . (DB::getSchemaBuilder()->hasColumn('ingresso_candidatos','status') ? 1 : 0) . PHP_EOL;
$c = DB::table('ingresso_candidatos')->orderBy('id')->first();
if ($c) {
    print_r($c);
} else {
    echo "no candidates\n";
}
