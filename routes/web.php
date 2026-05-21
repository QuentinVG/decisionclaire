<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LargePurchaseImpactController;
use App\Http\Controllers\LivingBalanceController;
use App\Http\Controllers\PdfExportController;
use App\Http\Controllers\PortfolioExportController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PurchaseDecisionController;
use App\Http\Controllers\SavedSimulationController;
use App\Http\Controllers\SavingsGoalController;
use App\Http\Controllers\ScenarioComparatorController;
use App\Http\Controllers\SubscriptionAuditController;
use App\Http\Controllers\ToolController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');
Route::get('/confiance', fn () => view('trust'))->name('trust');
Route::get('/outils', [ToolController::class, 'index'])->name('tools.index');

Route::get('/outils/reste-a-vivre', [LivingBalanceController::class, 'show'])->name('tools.living-balance.show');
Route::post('/outils/reste-a-vivre', [LivingBalanceController::class, 'calculate'])->name('tools.living-balance.calculate');

Route::get('/outils/jachete-ou-pas', [PurchaseDecisionController::class, 'show'])->name('tools.purchase-decision.show');
Route::post('/outils/jachete-ou-pas', [PurchaseDecisionController::class, 'calculate'])->name('tools.purchase-decision.calculate');

Route::get('/outils/impact-gros-achat', [LargePurchaseImpactController::class, 'show'])->name('tools.large-purchase-impact.show');
Route::post('/outils/impact-gros-achat', [LargePurchaseImpactController::class, 'calculate'])->name('tools.large-purchase-impact.calculate');

Route::get('/outils/objectif-epargne', [SavingsGoalController::class, 'show'])->name('tools.savings-goal.show');
Route::post('/outils/objectif-epargne', [SavingsGoalController::class, 'calculate'])->name('tools.savings-goal.calculate');

Route::get('/outils/abonnements', [SubscriptionAuditController::class, 'show'])->name('tools.subscription-audit.show');
Route::post('/outils/abonnements', [SubscriptionAuditController::class, 'calculate'])->name('tools.subscription-audit.calculate');

Route::get('/outils/comparateur-scenarios', [ScenarioComparatorController::class, 'show'])->name('tools.scenario-comparator.show');
Route::post('/outils/comparateur-scenarios', [ScenarioComparatorController::class, 'calculate'])->name('tools.scenario-comparator.calculate');

Route::get('/portfolio-snippet', PortfolioExportController::class)->name('portfolio.snippet');

Route::get('/robots.txt', function () {
    $url = rtrim(config('app.url'), '/');

    return response(
        "User-agent: *\n".
        "Allow: /\n".
        "Sitemap: {$url}/sitemap.xml\n",
        200,
        ['Content-Type' => 'text/plain; charset=UTF-8']
    );
})->name('robots');

Route::get('/sitemap.xml', function () {
    $url = e(rtrim(config('app.url'), '/'));
    $pages = collect([
        ['path' => '/', 'priority' => '1.0'],
        ['path' => '/outils', 'priority' => '0.9'],
        ['path' => '/outils/jachete-ou-pas', 'priority' => '0.9'],
        ['path' => '/outils/reste-a-vivre', 'priority' => '0.8'],
        ['path' => '/outils/impact-gros-achat', 'priority' => '0.8'],
        ['path' => '/outils/objectif-epargne', 'priority' => '0.8'],
        ['path' => '/outils/abonnements', 'priority' => '0.8'],
        ['path' => '/outils/comparateur-scenarios', 'priority' => '0.8'],
        ['path' => '/confiance', 'priority' => '0.8'],
    ])->map(fn (array $page): string => "  <url>\n    <loc>{$url}{$page['path']}</loc>\n    <lastmod>2026-05-21</lastmod>\n    <priority>{$page['priority']}</priority>\n  </url>")
        ->implode("\n");

    return response(
        <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
{$pages}
</urlset>
XML,
        200,
        ['Content-Type' => 'application/xml; charset=UTF-8']
    );
})->name('sitemap');

Route::get('/dashboard', DashboardController::class)->middleware(['auth'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::post('/simulations', [SavedSimulationController::class, 'store'])->name('simulations.store');
    Route::get('/simulations/{simulation}', [SavedSimulationController::class, 'show'])->name('simulations.show');
    Route::post('/simulations/{simulation}/duplicate', [SavedSimulationController::class, 'duplicate'])->name('simulations.duplicate');
    Route::delete('/simulations/{simulation}', [SavedSimulationController::class, 'destroy'])->name('simulations.destroy');
    Route::get('/simulations/{simulation}/export-pdf', PdfExportController::class)->name('simulations.export-pdf');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
