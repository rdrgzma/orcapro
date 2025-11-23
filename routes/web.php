<?php

use App\Http\Controllers\BudgetController;
use App\Http\Controllers\BudgetPublicController;
use App\Http\Controllers\WorkOrderController;
use App\Livewire\BudgetForm;
use App\Livewire\BudgetList;
use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use App\Livewire\Settings\TwoFactor;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use App\Models\Budget;
use App\Models\WorkOrder;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('dashboard', function () {
    $pendingBudgets = Budget::where('status', 'sent')->count();
    $openOS = WorkOrder::where('status', 'open')->count();
    $revenueMonth = 0; // Placeholder
    return view('dashboard', [
        'pendingBudgets' => $pendingBudgets,
        'openOS' => $openOS,
        'revenueMonth' => $revenueMonth
    ]);
})
    ->middleware(['auth'])
    ->name('dashboard');

// Link Público (sem auth)
Route::get('/o/{token}', [BudgetPublicController::class, 'show'])->name('budgets.public.show');
Route::post('/o/{token}/approve', [BudgetPublicController::class, 'approve'])->name('budgets.public.approve');
Route::post('/o/{token}/reject', [BudgetPublicController::class, 'reject'])->name('budgets.public.reject');

// PDF
Route::get('/o/{token}/pdf', [BudgetPublicController::class, 'pdf'])->name('budgets.public.pdf');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Route::get('settings/profile', Profile::class)->name('profile.edit');
    Route::get('settings/password', Password::class)->name('user-password.edit');
    Route::get('settings/appearance', Appearance::class)->name('appearance.edit');

    Route::get('settings/two-factor', TwoFactor::class)
        ->middleware(
            when(
                Features::canManageTwoFactorAuthentication()
                    && Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword'),
                ['password.confirm'],
                [],
            ),
        )
        ->name('two-factor.show');

    Route::get('budgets', BudgetList::class)->name('budgets.index');
    Route::get('budgets/create', BudgetForm::class)->name('budgets.create');
    Route::get('budgets/{budget}/edit', BudgetForm::class)->name('budgets.edit');
    Route::get('budgets/{budget}', [BudgetController::class, 'show'])->name('budgets.show');
    Route::post('/budgets/{budget}/convert', [BudgetController::class, 'convertToWorkOrder'])
        ->name('budgets.convert');

    Route::resource('work-orders', WorkOrderController::class);
});
