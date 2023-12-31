<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DatatableController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProjectsController;
use App\Http\Controllers\TasksController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\InvoicesController;



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

Route::middleware('auth')->group(function () {
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('auth.dashboard');
    Route::get('/tables', [DatatableController::class, 'index'])->name('tables.index');
    Route::get('/categories', [CategoriesController::class, 'index'])->name('categories.index');
    Route::get('/categories/create', [CategoriesController::class, 'create'])->name('categories.create');
    Route::post('/categories', [CategoriesController::class, 'store'])->name('categories.store');
    Route::get('/categories/{category}', [CategoriesController::class, 'show'])->name('categories.show');
    Route::get('/categories/{category}/edit', [CategoriesController::class, 'edit'])->name('categories.edit');
    Route::put('/categories/{category}', [CategoriesController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{id}', [CategoriesController::class, 'destroy']) ->name('categories.destroy');
    Route::get('/products', [ProductsController::class, 'index'])->name('products.index');
    Route::get('/products/create', [ProductsController::class, 'create'])->name('products.create');
    Route::post('/products', [ProductsController::class, 'store'])->name('products.store');
    Route::get('/products/{product}', [ProductsController::class, 'show'])->name('products.show');
    Route::get('/products/{product}/edit', [ProductsController::class, 'edit'])->name('products.edit');
    Route::put('/products/{product}', [ProductsController::class, 'update'])->name('products.update');
    Route::delete('/products/{id}', [ProductsController::class, 'destroy']) ->name('products.destroy');
    Route::get('/almostoutstock', [ProductsController::class, 'almostoutstock'])->name('products.almostoutstock');
    Route::get('/outstock', [ProductsController::class, 'outstock'])->name('products.outstock');
    Route::get('/sales', [SalesController::class, 'index'])->name('sales.index');
    Route::get('/sales/create', [SalesController::class, 'create'])->name('sales.create');
    Route::post('/sales', [SalesController::class, 'store'])->name('sales.store');
    Route::get('/sales/{sale}', [SalesController::class, 'show'])->name('sales.show');
    Route::delete('/sales/{id}', [SalesController::class, 'destroy']) ->name('sales.destroy');
    // Route::put('/sales/{sale}/updatePaymentStatus', [SalesController::class, 'updatePaymentStatus'])->name('sales.updatePaymentStatus');
    Route::post('/update-payment-status/{sale}', [SalesController::class, 'updatePaymentStatus'])->name('sales.update_payment_status');
    Route::get('/sales/{saleId}/download-pdf',  [SalesController::class, 'downloadSalesPDF'])->name('sales.download_pdf');

    Route::get('/invoices', [InvoicesController::class, 'index'])->name('invoices.index');
    Route::get('/invoices/{invoice}', [InvoicesController::class, 'show'])->name('invoices.show');
    Route::get('/invoices/{invoiceId}/download-pdf', [InvoicesController::class, 'downloadPDF'])->name('invoices.download_pdf');

    
});

Route::prefix('admin')->middleware(['auth', 'isAdmin'])->group(function(){
    Route::get('/dashboard', [AdminController::class, 'admin'])->name('admin.dashboard');
    Route::get('/users', [AdminController::class, 'manageUsers'])->name('admin.users');
    Route::get('/createuser', [AdminController::class, 'createUserView'])->name('admin.createuser.view');
    Route::post('/createuser', [AdminController::class, 'createuser'])->name('admin.createuser'); 
    Route::get('/createadmin', [AdminController::class, 'createAdminView'])->name('admin.createadmin.view');
    Route::post('/createadmin', [AdminController::class, 'createadmin'])->name('admin.createadmin'); 
    Route::get('/projects', [ProjectsController::class, 'index'])->name('projects.index');
    Route::get('/projects/create', [ProjectsController::class, 'create'])->name('projects.create');
    Route::post('/projects', [ProjectsController::class, 'store'])->name('projects.store');
    Route::get('/projects/{project}', [ProjectsController::class, 'show'])->name('projects.show');
    Route::get('/projects/{project}/edit', [ProjectsController::class, 'edit'])->name('projects.edit');
    Route::put('/projects/{project}', [ProjectsController::class, 'update'])->name('projects.update');
    Route::delete('/projects/{id}', [ProjectsController::class, 'destroy']) ->name('projects.destroy');
    Route::get('/tasks', [TasksController::class, 'index'])->name('tasks.index');
    Route::get('/tasks/create', [TasksController::class, 'create'])->name('tasks.create');
    Route::post('/tasks', [TasksController::class, 'store'])->name('tasks.store');
    Route::get('/tasks/{task}', [TasksController::class, 'show'])->name('tasks.show');
    Route::get('/tasks/{task}/edit', [TasksController::class, 'edit'])->name('tasks.edit');
    Route::put('/tasks/{task}', [TasksController::class, 'update'])->name('tasks.update');
    Route::delete('/tasks/{id}', [TasksController::class, 'destroy']) ->name('tasks.destroy');
});

