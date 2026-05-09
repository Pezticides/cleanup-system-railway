<?php

use App\Http\Controllers\Admin\TeamController;
use App\Http\Controllers\CleanUpReportController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReactionController;
use App\Http\Controllers\ReportCommentController;
use Illuminate\Support\Facades\Route;

Route::get('/', [CleanUpReportController::class, 'index']);
Route::get('/report', [CleanUpReportController::class, 'create']);
Route::post('/report', [CleanUpReportController::class, 'store']);
Route::get('/reports/{report}', [CleanUpReportController::class, 'show'])->name('reports.show');

Route::get('/dashboard', function () { return view('dashboard'); })->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::post('/reports/{report}/comments', [ReportCommentController::class, 'store'])->name('reports.comments.store');
    Route::post('/reports/{report}/react', [ReactionController::class, 'report'])->name('reports.react');
    Route::post('/comments/{comment}/react', [ReactionController::class, 'comment'])->name('comments.react');

    Route::get('/messages', [MessageController::class, 'index'])->name('messages.index');
    Route::get('/messages/create', [MessageController::class, 'create'])->name('messages.create');
    Route::post('/messages', [MessageController::class, 'store'])->name('messages.store');
    Route::get('/messages/{conversation}', [MessageController::class, 'show'])->name('messages.show');
    Route::post('/messages/{conversation}', [MessageController::class, 'send'])->name('messages.send');

    Route::get('/personnel', function () {
        $user = auth()->user();
        $reports = \App\Models\CleanUpReport::with(['comments', 'reactions'])
            ->where('assigned_user_id', $user->id)
            ->latest()
            ->get();
        return view('reports.personnel', compact('reports'));
    });
});

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/reports', [CleanUpReportController::class, 'admin']);
    Route::patch('/admin/reports/{id}/status', [CleanUpReportController::class, 'updateStatus']);
    Route::delete('/admin/reports/{id}', [CleanUpReportController::class, 'destroy']);
    Route::resource('/admin/teams', TeamController::class);
});

require __DIR__.'/auth.php';
