<?php

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

/*
 * Route test demo
 * */
Route::get('/', function () {
    return view('welcome');
});
Route::get('ListCategoryProducts','CategoryProductController@index');
Route::post('addNewCategoryProduct','CategoryProductController@store');

/*
 * Route cho phia admin
 * */
Route::group(['prefix'=>'admin'],function(){
    Route::get('DashBoard','adminController@DashBoard');

    /*
     * Route CURD cho cac thành phần của hệ thống
     * Categories
     * Article
     * Product
     * Blog
     * Comments
     * Contacts
     * Linkeds
     * ...
     * */
    Route::group(['prefix'=>'Categories'],function(){
        Route::get('CategoriesBlog','CategoryBlogController@index');
        Route::get('addCategoriesBlog','CategoryBlogController@getStore');
        Route::post('addCategoriesBlog','CategoryBlogController@store');
        Route::get('updateCategoriesBlog/{id}','CategoryBlogController@getUpdate');
        Route::post('updateCategoriesBlog/{id}','CategoryProductController@update');
        Route::get('deleteCategoriesBlog/{id}','CategoryBlogController@destroy');

        Route::get('CategoriesProduct','CategoryProductController@index');
        Route::get('addCategoriesProduct','CategoryProductController@getStore');
        Route::post('addCategoriesProduct','CategoryProductController@store');
        Route::get('updateCategoriesProduct/{id}','CategoryProductController@getUpdate');
        Route::post('updateCategoriesProduct/{id}','CategoryProductController@update');
        Route::get('deleteCategoriesProduct/{id}','CategoryProductController@destroy');
    });

    Route::group(['prefix'=>'Product'],function(){
        Route::get('Products','ProductController@index');
        Route::get('addProducts','ProductController@getStore');
        Route::post('addProduct','ProductController@store');

        Route::get('updateProduct/{id}','ProductController@getUpdate');
        Route::post('updateProduct/{id}','ProductController@Update');

        Route::get('deleteProduct/{id}','ProductController@destroy');

        Route::post('addImage/{id}','ProductController@postAddImage');
        Route::get('deleteImage/{id}','ProductController@getDeleteImage');
    });

    Route::group(['prefix'=>'Blog'],function(){
        Route::get('Blogs','BlogController@index');

        Route::get('addBlogs','BlogController@getAddBlogs');

        Route::get('updateBlog/{id}','BlogController@getUpdateBlogs');

        Route::get('deleteBlog/{id}','BlogController@destroy');
    });
});

/*
 * Route cho phia client
 * */
