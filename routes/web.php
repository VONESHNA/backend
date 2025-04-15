<?php
use App\Http\Controllers\ApiController;
use App\Http\Controllers\ViewController;
use App\Http\Controllers\DirectController;
use App\Http\Controllers\AuthController;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

Route::get('/view', function () {
    return view('welcome');
});

// Route::get('/', function () {
//     return view('view_selection')->name('view.index');
// });

//direct view
//Route::get('/', [ViewController::class, 'index'])->name('view.index');
Route::get('/{view}', [ViewController::class, 'show'])->name('view.selected_view');
Route::get('/{view?}', [ViewController::class, 'show'])->name('view.index'); // The '?' makes the parameter optional
Route::get('/direct_view', function(){ return view('direct_view.login');})->name('view.direct_view');
Route::get('/api_view', function(){ return view('api_view');})->name('view.api_view');

//Route::get('direct_view/register/', function(){return 'jo';});
Route::prefix('direct_view')->group(function () {
    
    //Route::get('/register',function(){return 'direct register';})->name('direct.register');
    Route::get('/register',function(){return view('direct_view.register');})->name('direct.register');
    Route::post('/register', [DIrectController::class, 'register']);

    Route::get('/directlogin',function(){return view('direct_view.login');})->name('direct.login');
    Route::post('/postlogin',[DirectController::class, 'login'])->name('postlogin');
    //Route::post('/postlogin',function(){ return 'hi';})->name('postlogin');

    //Route::get('/home',function(){return 'direct home';});
    Route::get('/home', function () {
        $users = User::all();
        return view('direct_view.home', compact('users'));
    })->middleware('auth')->name('direct.home');
    Route::get('/edit',function(){return 'direct edit';});

    
Route::get('/user/edit/{id}', function ($id) {
    $user = User::findOrFail($id);
    return view('direct_view.edit', compact('user'));
})->middleware('auth')->name('direct.user.edit');


Route::post('/user/update/{id}', function (Request $request, $id) {
    $user = User::findOrFail($id);
    $user->update($request->validate([
        'first_name' => 'required|string|max:255',
        'last_name' => 'required|string|max:255',
        'dob' => 'required|date',
        'address' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email,' . $user->id,
        'phone' => 'required|digits:10|unique:users,phone,' . $user->id,
        'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]));

    if ($request->hasFile('photo')) {
        $user->photo = $request->file('photo')->store('photos', 'public');
        $user->save();
    }

    return redirect()->route('direct.home')->with('success', 'User updated successfully');
})->middleware('auth')->name('direct.user.update');
Route::delete('/user/delete/{id}', function ($id) {
    $user = User::findOrFail($id);
    
    // Check if the user trying to delete is the logged-in user
    if ($user->id === auth()->id()) {
        return redirect()->route('direct.home')->with('error', 'You cannot delete your own account.');
    }

    $user->delete();
    return redirect()->route('direct.home')->with('success', 'User deleted successfully');
})->middleware('auth')->name('direct.user.delete');



Route::post('/logout', function () {
    auth()->logout();
    return redirect()->route('direct.login');
})->name('direct.logout');


});
//direct view



Route::get('/register', function () {
    return view('auth.register');
})->name('register');

Route::post('/register', [AuthController::class, 'register']);

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::post('/login', [AuthController::class, 'login']);

Route::get('/home', function () {
    $users = User::all();
    return view('home', compact('users'));
})->middleware('auth')->name('home');

Route::post('/logout', function () {
    auth()->logout();
    return redirect()->route('login');
})->name('logout');

Route::get('/user/edit/{id}', function ($id) {
    $user = User::findOrFail($id);
    return view('auth.edit', compact('user'));
})->middleware('auth')->name('user.edit');

Route::post('/user/update/{id}', function (Request $request, $id) {
    $user = User::findOrFail($id);
    $user->update($request->validate([
        'first_name' => 'required|string|max:255',
        'last_name' => 'required|string|max:255',
        'dob' => 'required|date',
        'address' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email,' . $user->id,
        'phone' => 'required|digits:10|unique:users,phone,' . $user->id,
        'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]));

    if ($request->hasFile('photo')) {
        $user->photo = $request->file('photo')->store('photos', 'public');
        $user->save();
    }

    return redirect()->route('home')->with('success', 'User updated successfully');
})->middleware('auth')->name('user.update');

Route::delete('/user/delete/{id}', function ($id) {
    $user = User::findOrFail($id);
    $user->delete();
    return redirect()->route('home')->with('success', 'User deleted successfully');
})->middleware('auth')->name('user.delete');

