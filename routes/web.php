<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\PaymentMethodController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\SummaryController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

// Redireciona para gastos
Route::get('/', fn() => redirect()->route('expenses.index'));

// Gastos
Route::get('/gastos', [ExpenseController::class, 'index'])->name('expenses.index');
Route::post('/gastos', [ExpenseController::class, 'store'])->name('expenses.store');
Route::put('/gastos/{expense}', [ExpenseController::class, 'update'])->name('expenses.update');
Route::delete('/gastos/{expense}', [ExpenseController::class, 'destroy'])->name('expenses.destroy');

// Categorias
Route::get('/categorias', [CategoryController::class, 'index'])->name('categories.index');
Route::post('/categorias', [CategoryController::class, 'store'])->name('categories.store');
Route::put('/categorias/{category}', [CategoryController::class, 'update'])->name('categories.update');
Route::delete('/categorias/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');

// Formas de Pagamento
Route::get('/pagamentos', [PaymentMethodController::class, 'index'])->name('payment-methods.index');
Route::post('/pagamentos', [PaymentMethodController::class, 'store'])->name('payment-methods.store');
Route::put('/pagamentos/{paymentMethod}', [PaymentMethodController::class, 'update'])->name('payment-methods.update');
Route::delete('/pagamentos/{paymentMethod}', [PaymentMethodController::class, 'destroy'])->name('payment-methods.destroy');

// Resumo
Route::get('/resumo', [SummaryController::class, 'index'])->name('summary.index');

// Relatório (seleção por mês)
Route::get('/relatorio', [ReportController::class, 'index'])->name('reports.index');
Route::get('/relatorio/export', [ReportController::class, 'export'])->name('reports.export');

// Configurações (Telegram)
Route::get('/configuracoes', [SettingController::class, 'index'])->name('settings.index');
Route::put('/configuracoes', [SettingController::class, 'update'])->name('settings.update');
