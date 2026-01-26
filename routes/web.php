<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;
use App\Notifications\WelcomeEmailNotification;
use App\Models\User;

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


use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderController; 
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController; 
use App\Http\Controllers\HomeController; 
use App\Http\Controllers\ShippingController; 

use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\CategoryController; 
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\ProfileController as AdminProfileController;


require __DIR__.'/auth.php';



Route::get('/',[HomeController::class,'index'])->name('home');
Route::get('/products', [ProductController::class, 'index'])->name('products');
Route::get('/product/{id}', [ProductController::class, 'show'])->name('product.show');
Route::get('/category/{id}', [ProductController::class, 'byCategory'])->name('products.byCategory');

Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile/delete', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    Route::post('/cart/{id}', [CartController::class, 'store'])->name('cart.store');
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');
    Route::post('/checkout', [CheckoutController::class, 'process'])->name('checkout.process');
    Route::get('/orders', [OrderController::class, 'history'])->name('orders.history');

      // Order Routes - ADD THESE
 // Order routes
    Route::get('/orders', [OrderController::class, 'history'])->name('orders.history');
    Route::get('/orders/{id}', [OrderController::class, 'show'])->name('orders.show');
    Route::get('/orders/{id}/track', [OrderController::class, 'trackOrder'])->name('orders.track');
    Route::post('/orders/place', [OrderController::class, 'placeOrder'])->name('orders.place');
    Route::post('/orders/{id}/cancel', [OrderController::class, 'cancelOrder'])->name('orders.cancel');

     // Checkout Routes
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');
    Route::post('/checkout', [CheckoutController::class, 'process'])->name('checkout.process');
    Route::post('/checkout/quick', [CheckoutController::class, 'quickCheckout'])->name('checkout.quick');

    // Admin-only routes that can be accessed from shared pages
    Route::middleware(['is_admin'])->group(function () {
        Route::patch('/products/{id}/toggle-featured', [ProductController::class, 'toggleFeatured'])
            ->name('products.toggleFeatured');
    });
}); 

Route::middleware(['auth', 'is_admin'])->prefix('admin')->name('admin.')->group(function () { 
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
    Route::get('/profile', [AdminProfileController::class, 'show'])->name('profile');
    Route::put('/profile/update', [AdminProfileController::class, 'update'])->name('profile.update');
    Route::resource('products', AdminProductController::class);
    Route::resource('categories', CategoryController::class);
    Route::resource('users', UserController::class)->only(['index', 'edit', 'update', 'destroy']);
    Route::resource('orders', AdminOrderController::class)->only(['index', 'show', 'update', 'destroy']); 
    Route::get('shipments', [ShippingController::class, 'index'])->name('shipments.index'); 
    Route::get('shipments/{shipment}', [ShippingController::class, 'show'])->name('shipments.show');
    Route::put('shipments/{shipment}', [ShippingController::class, 'update'])->name('shipments.update');
    Route::put('orders/{order}/shipping', [ShippingController::class, 'update'])->name('orders.shippings.update');
});

// Test email route
Route::get('/test-email', function () {
    try {
        Mail::raw('Test email from Laravel app', function ($message) {
            $message->to('test@example.com')
                   ->subject('Test Email');
        });

        return 'Test email sent! Check your Mailtrap inbox.';
    } catch (\Exception $e) {
        return 'Error sending email: ' . $e->getMessage();
    }
});

// Test welcome notification
Route::get('/test-welcome-email', function () {
    try {
        // Create a test user (won't be saved to database)
        $user = new User([
            'name' => 'Test User',
            'email' => 'test@example.com'
        ]);

        // Send the welcome notification
        $user->notify(new WelcomeEmailNotification());

        return 'Welcome email sent! Check your Mailtrap inbox.';
    } catch (\Exception $e) {
        return 'Error sending welcome email: ' . $e->getMessage();
    }
});
