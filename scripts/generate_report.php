<?php
// Usage: php scripts/generate_report.php --month=2026-04 --status=Pendente --out=storage/app/reports/example.csv

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Expense;
use Illuminate\Support\Facades\Storage;

$options = getopt('', ['month::','status::','payment_method_id::','category_id::','due_from::','due_to::','min_value::','max_value::','out::']);

$month = $options['month'] ?? date('Y-m');
$year = (int) substr($month, 0, 4);
$monthNum = (int) substr($month, 5, 2);

$query = Expense::with(['category','paymentMethod'])
    ->whereYear('date', $year)
    ->whereMonth('date', $monthNum);

if (!empty($options['status']) && $options['status'] !== 'all') {
    $query->where('status', $options['status']);
}
if (!empty($options['payment_method_id'])) {
    $query->where('payment_method_id', $options['payment_method_id']);
}
if (!empty($options['category_id'])) {
    $query->where('category_id', $options['category_id']);
}
if (!empty($options['due_from'])) {
    $query->whereDate('due_date', '>=', $options['due_from']);
}
if (!empty($options['due_to'])) {
    $query->whereDate('due_date', '<=', $options['due_to']);
}
if (isset($options['min_value']) && $options['min_value'] !== '') {
    $query->where('value', '>=', (float) $options['min_value']);
}
if (isset($options['max_value']) && $options['max_value'] !== '') {
    $query->where('value', '<=', (float) $options['max_value']);
}

$count = $query->count();

$outPath = $options['out'] ?? ('storage/app/reports/relatorio-' . $month . '.csv');
$fullPath = __DIR__ . '/../' . $outPath;
$fullDir = dirname($fullPath);
if (!is_dir($fullDir)) {
    mkdir($fullDir, 0777, true);
}
$handle = fopen($fullPath, 'w');
if (!$handle) {
    echo "Failed to open file for writing: {$fullPath}\n";
    exit(1);
}

fputcsv($handle, ['Data','Categoria','Descricao','Valor','Vencimento','Status','Forma Pgto']);

foreach ($query->with(['category','paymentMethod'])->orderBy('date')->cursor() as $e) {
    fputcsv($handle, [
        $e->date ? $e->date->format('Y-m-d') : '',
        $e->category->name ?? '',
        $e->description ?? '',
        number_format($e->value, 2, '.', ''),
        $e->due_date ? $e->due_date->format('Y-m-d') : '',
        $e->status,
        $e->paymentMethod->name ?? '',
    ]);
}

fclose($handle);

echo "Wrote {$count} records to {$outPath}\n";
exit(0);
