<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
use App\Mail\MyTestEmail;
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

Route::get('/', function () {
    return redirect('login');
});
Route::group(['middleware' => 'checkStatus'], function () {
    Route::middleware(['dynamic.app.name'])->group(function () {
        //Clear Cache facade value:
        Route::get('/clear', function () {
            Artisan::call('cache:clear');
            Artisan::call('optimize');
            Artisan::call('route:cache');
            Artisan::call('route:clear');
            Artisan::call('view:clear');
            Artisan::call('config:cache');
            return '<h1>Cache cleared</h1>';
        });

        Route::get('/dashboard', function () {
            return view('dashboard');
        })->middleware(['auth', 'verified'])->name('dashboard');


        //investor routes
        require __DIR__ . '/investor.php';

        //project routes
        require __DIR__ . '/project.php';

        //income routes
        require __DIR__ . '/income.php';

        //expense routes
        require __DIR__ . '/expense.php';

        //cash_account routes
        require __DIR__ . '/cash_account.php';

        //cash_transfer routes
        require __DIR__ . '/cash_transfer.php';



        //user routes
        require __DIR__ . '/user.php';

        //role routes
        require __DIR__ . '/role.php';

        //notification routes
        require __DIR__ . '/notification.php';

        //profile routes
        require __DIR__ . '/profile.php';
    });
});
//auth routes
require __DIR__ . '/auth.php';
