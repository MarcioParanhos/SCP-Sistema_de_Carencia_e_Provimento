<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\IngressoDocumento;

$candidateId = 1;
$total = IngressoDocumento::where('ingresso_candidato_id', $candidateId)->count();
$notValidated = IngressoDocumento::where('ingresso_candidato_id', $candidateId)->where('validated', 0)->count();

echo "total={$total}, notValidated={$notValidated}\n";

$update = [];
// emulate NTE confirm (not CPM)
if ($total > 0 && $notValidated === 0) {
    $update['status'] = 'Aguardando Confirmação pela CPM';
}

if (!empty($update)) {
    DB::table('ingresso_candidatos')->where('id', $candidateId)->update($update);
    echo "updated candidate status to: ";
    $c = DB::table('ingresso_candidatos')->where('id', $candidateId)->first();
    print_r($c);
} else {
    echo "nothing to update\n";
}
