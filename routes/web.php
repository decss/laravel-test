<?php

use App\Http\Controllers\DiggingDeeperController;
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
Route::get('/home', 'HomeController@index')->name('home');


Route::group(['namespace' => 'Blog', 'prefix' => 'blog'], function() {
    Route::resource('posts', 'PostController')->names('blog.posts');
});

$groupData = [
    'namespace' => 'Blog\Admin',
    'prefix'    => 'admin/blog',
];
Route::group($groupData, function () {


    // BlogCategory
    $methods = ['index', 'edit', 'update', 'create', 'store',];
    Route::resource('categories', 'CategoryController')
        ->only($methods)
        ->names('blog.admin.categories');

    // BlogPost
    Route::resource('posts', 'PostController')
        ->except(['show'])
        ->names('blog.admin.posts');
});

Route::resource('rest', 'RestTestController')->names('restTest');

Route::group(['prefix' => 'digging_deeper'], function () {
    Route::get('collections', 'DiggingDeeperController@collections')
        ->name('digging_deeper.collections');

    Route::get('prepare-catalog', 'DiggingDeeperController@prepareCatalog')
        ->name('digging_deeper.prepareCatalog');

    Route::get('debug', 'DiggingDeeperController@debug')
        ->name('digging_deeper.debug');

    // Cache
    Route::get('cache', [DiggingDeeperController::class, 'cache']);

    // dd(CategoryController::class, \App\Http\Controllers\Blog\Admin\CategoryController::class);
    Route::get('test1', 'DiggingDeeperController@test1');
    Route::get('test2', [\App\Http\Controllers\DiggingDeeperController::class, 'test2']);
    Route::get('test3', [DiggingDeeperController::class, 'test3']);
});


Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
