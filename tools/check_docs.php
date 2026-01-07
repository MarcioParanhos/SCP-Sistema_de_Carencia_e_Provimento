<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$cid = 1;
$rows = DB::table('ingresso_documentos')->where('ingresso_candidato_id', $cid)->get();
echo "candidate: $cid\n";
echo "total rows: " . count($rows) . "\n";
$notValidated = 0;
foreach ($rows as $r) {
    if (empty($r->validated) || $r->validated == 0) $notValidated++;
}
echo "notValidated: $notValidated\n";
print_r($rows->toArray());
