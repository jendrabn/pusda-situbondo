<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
  /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

  use AuthenticatesUsers;

  protected $maxAttempts = 3;
  protected $decayMinutes = 1;

  public function __construct()
  {
    $this->middleware('guest')->except('logout');
  }

  public function redirectTo()
  {
    return match (auth()->user()->role) {
      User::ROLE_ADMIN => route('admin.dashboard'),
      User::ROLE_SKPD =>  route('admin_skpd.dashboard'),
      default => route('home')
    };
  }

  public function username()
  {
    $fieldType = filter_var(request('username'), FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
    request()->merge([$fieldType => request('username')]);

    return $fieldType;
  }
}
