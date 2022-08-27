<?php


use App\Models\Order;
use App\Models\Product;
use App\Models\Category;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\TransactionController;

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

// Route::get('/', function () {
//     return view('home.index', [
//         'title' => 'Home',
//         'products'  => Product::latest()->limit(8)->get(),
//         'categories' => Category::latest()->limit(7)->get()
//     ]);
// })->name('home');

Route::get('/', [ProductController::class, 'index'])->name('home');
Route::get('/homeComponent', function () {
    $products = Product::paginate(8);
    $categories = Category::all();
    return view('produk.home', compact('categories', 'products'))->render();
})->name('home.component');
Route::get('/produk/fetch_data', [ProductController::class, 'fetch_data']);
Route::get('/produk/{product:id}', [ProductController::class, 'show']);

Route::group(['middleware' => ['auth']], function () {
    Route::get('getProducts', function (Request $request) {

        if ($request->ajax()) {
            $data = Product::with(['category'])->latest()->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->make(true);
        }
        abort(404);
    })->name('product.index');

    Route::get('getProducts/{id}', function ($id, Request $request) {
        if ($request->ajax()) {
            return Product::with(['category'])->where('id', $id)->get();
        }
        abort(404);
    });



    Route::get('getCategories', function (Request $request) {
        if ($request->ajax()) {
            $data = Category::latest()->get();
            return DataTables::of($data)->addIndexColumn()->make(true);
        }

        abort(404);
    })->name('product.categories');

    Route::get('getCategories/{id}', function ($id, Request $request) {
        if ($request->ajax()) {
            return Category::where('id', $id)->get();
        }
        abort(404);
    });

    Route::get('getOrders', function (Request $request) {
        if ($request->ajax()) {
            $data = Transaction::with(['users'])->latest()->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->make(true);
        }
    })->name('orders.admin');

    Route::get('getOrder', function (Request $request) {
        if ($request->ajax()) {
            $data = Transaction::where('id_user', Auth::id())->with(['users'])->latest()->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->make(true);
        }
    })->name('orders.user');





    Route::post('/admin/sendOrder', [TransactionController::class, 'sendOrder'])->name('send.order');
    Route::get('/admin/detailTransaksi/{transaction:id}', [TransactionController::class, 'detailOrder']);

    Route::get('/keranjang', function (Request $request) {
        if ($request->ajax()) {
            $carts = Order::with(['product'])->where('status', 1)->where('id_user', Auth::id())->get();
            return view('produk.keranjang', [
                'carts' => $carts
            ])->render();
        }
    });

    Route::post('/logout', [LoginController::class, 'logout']);
    Route::post('/addtocart', [OrderController::class, 'addtocart']);
    Route::get('/checkout', [OrderController::class, 'checkout']);
    Route::get('/getTotal', [OrderController::class, 'getTotal'])->name('getTotal');
    Route::get('/profile', [ProfileController::class, 'index']);
    Route::post('/profile', [ProfileController::class, 'update']);
    Route::get('/city/{province}', [ProfileController::class, 'getCity']);
    Route::delete('/deletecart/{order:id}', [OrderController::class, 'deletecart']);
    Route::post('/createOrder', [TransactionController::class, 'create']);
    Route::get('/transaksi', [TransactionController::class, 'index']);
    Route::get('/detailTransaksi/{transaction:id}', [TransactionController::class, 'detail'])->name('transaksi.detail');
    Route::post('/bukti-pembayaran', [TransactionController::class, 'buktiPembayaran'])->name('bukti.pembayaran');
    Route::post('/cancel-transaksi', [TransactionController::class, 'cancel'])->name('cancel.transaksi');
    Route::post('/terimaorder', [TransactionController::class, 'receiveOrder'])->name('receive.order');
});


Route::group(['middleware' => ['admin', 'auth']], function () {
    Route::get('/admin', function () {
        return view('admin.index', [
            'title' => 'Admin | Dashboard',
            'menu'  => 'dashboard',
            'order' => DB::table('transactions')->select(DB::raw('count(*) as order_count'))->first(),
            'pelanggan' => DB::table('users')->select(DB::raw('count(*) as user_count'))->first(),
            'produk' => DB::table('products')->select(DB::raw('count(*) as produk_count'))->first(),
            'stok'  => DB::table('products')->select(DB::raw('SUM(stok) as stok_produk'))->first(),
            'total_transaksi' =>  DB::table('transactions')->select(DB::raw('SUM(total_bayar) as total_transaksi'))
                ->where('status', 3)
                ->orWhere('status', 4)
                ->first()
        ]);
    });
    Route::get('/admin/produk', function () {
        return view('admin.produk', [
            'title' => 'Admin | Produk',
            'categories' => Category::all(),
            'menu'  => 'produk'
        ]);
    });

    Route::get('/admin/kategori', function () {
        return view('admin.kategori', [
            'title' => 'Admin | Kategori',
            'menu'  => 'produk'
        ]);
    });

    Route::get('/admin/orders', function () {
        return view('admin.orders', [
            'title' => 'Admin | Orders',
            'menu'  => 'orders'
        ]);
    });

    Route::get('/admin/user-admin', function () {
        return view('admin.user-admin', [
            'title' => 'Admin | User Admin',
            'menu'  => 'users'
        ]);
    });
    Route::get('/admin/user-pelanggan', function () {
        return view('admin.user-pelanggan', [
            'title' => 'Admin | User Pelanggan',
            'menu'  => 'users'
        ]);
    });

    Route::get('/admin/produk/slug', [ProductController::class, 'getSlug']);
    Route::post('/admin/produk', [ProductController::class, 'store']);
    Route::delete('/admin/produk/{product:slug}', [ProductController::class, 'destroy']);
    Route::put('/admin/produk/{product:slug}', [ProductController::class, 'update']);


    Route::get('/admin/kategori/slug', [CategoryController::class, 'getSlug']);
    Route::post('/admin/kategori', [CategoryController::class, 'store']);
    Route::delete('/admin/kategori/{category:slug}', [CategoryController::class, 'destroy']);
    Route::put('/admin/kategori/{category:slug}', [CategoryController::class, 'update']);

    Route::get('getAdmin', [UserController::class, 'getAdmin'])->name('user.admin');
    Route::get('getPelanggan', [UserController::class, 'getCommonUser'])->name('user.pelanggan');
});





Route::get('/login', [LoginController::class, 'index'])->name('login')->middleware('guest');
Route::post('/login', [LoginController::class, 'authenticate']);

Route::get('/register', [RegisterController::class, 'index'])->name('register')->middleware('guest');
Route::post('/register', [RegisterController::class, 'register']);
