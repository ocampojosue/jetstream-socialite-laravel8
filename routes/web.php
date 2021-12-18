<?php

use App\Http\Controllers\FaceBookController;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

Route::prefix('facebook')->name('facebook.')->group(function () {
    Route::get('auth', [FaceBookController::class, 'loginUsingFacebook'])->name('login');
    Route::get('callback', [FaceBookController::class, 'callbackFromFacebook'])->name('callback');
});

Route::get('/auth/github/redirect', function () {
    return Socialite::driver('github')->redirect();
});
Route::get('/g', function () {
    return redirect('dashboard');
});
Route::get('/auth/github/callback', function () {
    $githubUser = Socialite::driver('github')->user();
    $user = User::firstOrCreate(
        [
            'provider_id' => $githubUser->getId()
        ],
        [
            'email' => $githubUser->getEmail(),
            'name' => $githubUser->getName(),
        ]
    );
    //dd($githubUser);
    // $user = User::create([
    //     'email' => $githubUser->getEmail(),
    //     'name' => $githubUser->getName(),
    //     'provider_id' => $githubUser->getId()
    // ]);
    auth()->login($user, true);
    return redirect('dashboard');
});
