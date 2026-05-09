<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ContributorsController;
use App\Http\Controllers\ContributorMovementsController;
use App\Http\Controllers\ContributorPortalController;
use App\Http\Controllers\IndependentPurchaseOrdersController;
use App\Http\Controllers\SellSharesController;
use App\Http\Controllers\SharesTransController;
use App\Http\Controllers\ServiesController;
use App\Http\Controllers\SharesPOController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\PaymentsController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\BookingsController;
use App\Http\Controllers\PollsController;
use App\Http\Controllers\PollOptionsController;
use App\Http\Controllers\PollAnswersController;
use App\Http\Controllers\ShareTransLinesController;
use App\Http\Controllers\UsersProfitsController;
use App\Http\Controllers\ProfitsController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ModifyController;
use App\Http\Controllers\MeetingsController;
use App\Http\Controllers\RegulationsController;
use App\Http\Controllers\DocumentsController;
use App\Http\Controllers\CircularsController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\PermissionsController;
use App\Http\Controllers\TradingPeriodsController;
use App\Http\Controllers\CompanyPurchaseObligationsController;
use App\Http\Middleware\AuthorizeRoutePermission;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Public routes
Route::get('/', function () {
    return redirect()->route('login');
})->name('home');

// Authentication routes
Auth::routes();

// Protected routes
Route::middleware(['auth', AuthorizeRoutePermission::class])->group(function () {
    // Dashboard
    Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');

    Route::prefix('my')->name('contributor.')->group(function () {
        Route::get('dashboard', [ContributorPortalController::class, 'dashboard'])->name('dashboard');
        Route::get('statement', [ContributorPortalController::class, 'statement'])->name('statement');
        Route::get('sell-offers', [ContributorPortalController::class, 'sellOffers'])->name('sell-offers');
        Route::get('sell-offers/create', [ContributorPortalController::class, 'createSellOffer'])->name('sell-offers.create');
        Route::post('sell-offers', [ContributorPortalController::class, 'storeSellOffer'])->name('sell-offers.store');
        Route::get('sell-offers/{sellShare}/edit', [ContributorPortalController::class, 'editSellOffer'])->name('sell-offers.edit');
        Route::put('sell-offers/{sellShare}', [ContributorPortalController::class, 'updateSellOffer'])->name('sell-offers.update');
        Route::get('purchase-orders', [ContributorPortalController::class, 'purchaseOrders'])->name('purchase-orders');
        Route::get('purchase-orders/create', [ContributorPortalController::class, 'createPurchaseOrder'])->name('purchase-orders.create');
        Route::post('purchase-orders', [ContributorPortalController::class, 'storePurchaseOrder'])->name('purchase-orders.store');
        Route::post('purchase-orders/independent', [ContributorPortalController::class, 'storeIndependentPurchaseOrder'])->name('purchase-orders.independent.store');
        Route::get('polls', [ContributorPortalController::class, 'polls'])->name('polls');
        Route::get('polls/{poll}', [ContributorPortalController::class, 'showPoll'])->name('polls.show');
        Route::post('polls/{poll}/vote', [ContributorPortalController::class, 'votePoll'])->name('polls.vote');
        Route::get('meetings', [ContributorPortalController::class, 'meetings'])->name('meetings');
    });
    
    // Chart routes
    Route::get('/goals-chart', function () {
        // Generate goals chart
        return response()->file(public_path('images/goals-chart.png'));
    })->name('home.goals-chart');
    
    Route::get('/user-profit', function () {
        // Generate user profit chart
        return response()->file(public_path('images/user-profit.png'));
    })->name('home.user-profit');
    
    // Resource routes
    Route::resource('contributors', ContributorsController::class);
    Route::get('contributors/{contributor}/statement', [ContributorsController::class, 'statement'])
        ->name('contributors.statement');
    Route::resource('contributor-movements', ContributorMovementsController::class)->only(['index', 'create', 'store']);
    
    // Contributor document routes
    Route::get('contributors/documents/{document}/download', [ContributorsController::class, 'downloadDocument'])
        ->name('contributors.documents.download');
    Route::delete('contributors/documents/{document}', [ContributorsController::class, 'deleteDocument'])
        ->name('contributors.documents.delete');
    
    Route::resource('sell-shares', SellSharesController::class);
    Route::resource('shares-trans', SharesTransController::class);
    Route::resource('shares-pos', SharesPOController::class);
    Route::resource('independent-purchase-orders', IndependentPurchaseOrdersController::class)->only(['index', 'show', 'update']);
    Route::resource('payments', PaymentsController::class);
    Route::resource('polls', PollsController::class);
    Route::resource('poll-options', PollOptionsController::class);
    Route::resource('poll-answers', PollAnswersController::class);
    Route::resource('share-trans-lines', ShareTransLinesController::class);
    Route::resource('users-profits', UsersProfitsController::class);
    Route::resource('profits', ProfitsController::class);
    Route::resource('servies', ServiesController::class);
    Route::resource('meetings', MeetingsController::class);
    Route::resource('regulations', RegulationsController::class);
    Route::resource('documents', DocumentsController::class);
    Route::resource('circulars', CircularsController::class);
    Route::resource('categories', CategoriesController::class);
    Route::resource('users', UsersController::class);
    Route::resource('permissions', PermissionsController::class);
    Route::resource('trading-periods', TradingPeriodsController::class)->except(['show']);
    Route::resource('company-purchase-obligations', CompanyPurchaseObligationsController::class)->only(['index', 'show', 'edit', 'update']);
    
    // seetings route
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::post('/settings/store', [SettingsController::class, 'store'])->name('settings.store');

    // bookings routes
    Route::get('bookings', [BookingsController::class, 'index'])->name('bookings.index');
    Route::get('bookings/create', [BookingsController::class, 'create'])->name('bookings.create');
    Route::post('bookings/store', [BookingsController::class, 'store'])->name('bookings.store');
    Route::get('bookings/{id}/edit', [BookingsController::class, 'edit'])->name('bookings.edit');
    Route::put('bookings/{id}/update', [BookingsController::class, 'update'])->name('bookings.update');
    Route::delete('bookings/{id}/destroy', [BookingsController::class, 'destroy'])->name('bookings.destroy');
    Route::put('/bookings/{bookingId}/status', [BookingsController::class, 'update_status'])
        ->name('bookings.update-status');
    

    // Additional routes
    Route::post('shares-trans/{sharesTrans}/post', [SharesTransController::class, 'post'])->name('shares-trans.post');

    Route::get('modify', [ModifyController::class, 'index'])->name('modify.index');
    
    Route::get('sell-shares/{sellShare}/print', [SellSharesController::class, 'print'])
        ->name('sell-shares.print');
    Route::post('sell-shares/{sellShare}/settle', [SellSharesController::class, 'settle'])
        ->name('sell-shares.settle');
    Route::post('sell-shares/{sellShare}/close', [SellSharesController::class, 'close'])
        ->name('sell-shares.close');
    
    // Poll voting route
    Route::post('polls/{poll}/vote', [PollAnswersController::class, 'vote'])
        ->name('polls.vote');
    
    // Poll results route
    Route::get('polls/{poll}/results', [PollsController::class, 'results'])
        ->name('polls.results');
    
    // User profits route
    Route::get('users/{user}/profits', [UsersProfitsController::class, 'userProfits'])
        ->name('users.profits');
    
    // Mark profit as paid
    Route::post('users-profits/{usersProfit}/mark-paid', [UsersProfitsController::class, 'markAsPaid'])
        ->name('users-profits.mark-paid');
    
    // Toggle profit active status
    Route::post('profits/{profit}/toggle-active', [ProfitsController::class, 'toggleActive'])
        ->name('profits.toggle-active');
    
    // Toggle share trans line posted status
    Route::post('share-trans-lines/{shareTransLine}/toggle-posted', [ShareTransLinesController::class, 'togglePosted'])
        ->name('share-trans-lines.toggle-posted');
    
    // Toggle payment confirmed status
    Route::post('payments/{payment}/toggle-confirmed', [PaymentsController::class, 'toggleConfirmed'])
        ->name('payments.toggle-confirmed');
    
    // Toggle shares PO accept status
    Route::post('shares-pos/{sharesPO}/toggle-accept', [SharesPOController::class, 'toggleAccept'])
        ->name('shares-pos.toggle-accept');
    Route::post('shares-pos/{sharesPO}/mark-default', [SharesPOController::class, 'markDefault'])
        ->name('shares-pos.mark-default');

    // Download routes for file features
    Route::get('regulations/{regulation}/download', [RegulationsController::class, 'download'])
        ->name('regulations.download');
    Route::get('documents/{document}/download', [DocumentsController::class, 'download'])
        ->name('documents.download');
    Route::get('circulars/{circular}/download', [CircularsController::class, 'download'])
        ->name('circulars.download');
    
    // Meeting attachment routes
    Route::get('meetings/attachments/{attachment}/download', [MeetingsController::class, 'downloadAttachment'])
        ->name('meetings.attachments.download');
    Route::delete('meetings/attachments/{attachment}', [MeetingsController::class, 'deleteAttachment'])
        ->name('meetings.attachments.delete');

    // Profile routes
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/password', [ProfileController::class, 'changePassword'])->name('profile.password');

    // API routes
    Route::get('/contributors/share/{userId}', [SellSharesController::class, 'getusershares'])
        ->name('contributors.share');
});
