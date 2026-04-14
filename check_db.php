<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Category;
use App\Models\PaymentMethod;
use App\Models\Expense;

echo "--- DB Basic Counts ---\n";
echo "Categories: " . Category::count() . "\n";
echo "PaymentMethods: " . PaymentMethod::count() . "\n";
echo "Expenses: " . Expense::count() . "\n\n";

// Create test category and payment method
$catName = 'test_cat_for_check_'.time();
$payName = 'test_pay_for_check_'.time();

$category = Category::create(['name' => $catName]);
$payment = PaymentMethod::create(['name' => $payName]);

echo "Created test category id={$category->id} name={$category->name}\n";
echo "Created test payment id={$payment->id} name={$payment->name}\n";

// Create test expense
$expense = Expense::create([
    'date' => now()->format('Y-m-d'),
    'category_id' => $category->id,
    'description' => 'Automated check',
    'value' => 1.23,
    'due_date' => now()->addDays(5)->format('Y-m-d'),
    'status' => 'Pendente',
    'payment_method_id' => $payment->id,
    'notes' => 'created by check_db script',
]);

echo "Created expense id={$expense->id} value={$expense->value} status={$expense->status}\n";

// Read it back
$read = Expense::find($expense->id);
if ($read) {
    echo "Read expense OK id={$read->id} date={$read->date->format('Y-m-d')}\n";
} else {
    echo "Failed to read created expense\n";
}

// Update
$read->update(['status' => 'Pago', 'value' => 2.34]);
$after = Expense::find($expense->id);
echo "Updated expense id={$after->id} value={$after->value} status={$after->status}\n";

// Delete
$after->delete();
$exists = Expense::find($expense->id) ? 'yes' : 'no';
echo "After delete exists? {$exists}\n";

// Cleanup category and payment
$category->delete();
$payment->delete();

echo "Cleanup done. Final counts:\n";
echo "Categories: " . Category::count() . "\n";
echo "PaymentMethods: " . PaymentMethod::count() . "\n";
echo "Expenses: " . Expense::count() . "\n";

echo "--- End check ---\n";
