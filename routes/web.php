<?php

use App\Http\Controllers\DiggingDeeperController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

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
Auth::routes();
Route::get('/home', [HomeController::class, 'index'])->name('home');


Route::group(['namespace' => 'Blog', 'prefix' => 'blog'], function() {
    Route::resource('posts', \PostController::class)->names('blog.posts');
});

$groupData = [
    'namespace' => 'Blog\Admin',
    'prefix'    => 'admin/blog',
];
Route::group($groupData, function () {
    // BlogCategory
    $methods = ['index', 'edit', 'update', 'create', 'store',];
    Route::resource('categories', \CategoryController::class)
        ->only($methods)
        ->names('blog.admin.categories');

    // BlogPost
    Route::resource('posts', \PostController::class)
        ->except(['show'])
        ->names('blog.admin.posts');
});

Route::resource('rest', \RestTestController::class)->names('restTest');

Route::group(['prefix' => 'digging_deeper'], function () {
    Route::get('collections', [DiggingDeeperController::class, 'collections'])
        ->name('digging_deeper.collections');

    Route::get('prepare-catalog', [DiggingDeeperController::class, 'prepareCatalog'])
        ->name('digging_deeper.prepareCatalog');

    Route::get('debug', [DiggingDeeperController::class, 'debug'])
        ->name('digging_deeper.debug');

    // Cache
    Route::get('cache', [DiggingDeeperController::class, 'cache']);

    Route::get('test1', [DiggingDeeperController::class, 'test1']);
    Route::get('test2', [DiggingDeeperController::class, 'test2']);
    Route::get('test3', [DiggingDeeperController::class, 'test3']);
});


Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
