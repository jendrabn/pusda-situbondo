<?php

namespace App\Http\Requests\Admin;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{

  public function authorize()
  {
    return auth()->user()->role === User::ROLE_ADMIN;
  }

  public function rules()
  {
    return [
      'skpd_id' => ['required', 'numeric', 'exists:skpd,id'],
      'name' => ['required', 'string', 'max:50'],
      'username' => ['required', 'string', 'max:25', 'alpha_dash', 'unique:users,username,' . $this->user->id],
      'email' => ['required', 'string', 'email', 'max:50', 'unique:users,email,' . $this->user->id],
      'phone' => ['nullable', 'max:15', 'starts_with:+62,62,08'],
      'address' => ['nullable', 'string', 'max:255'],
      'role' => ['required', 'in:' . implode(',', User::ROLES)],
      'password' => ['nullable', 'string', 'min:3', 'max:50'],
    ];
  }
}