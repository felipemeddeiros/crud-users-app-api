<?php
namespace App\Repositories\Eloquent;

use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Traits\ApiResponser;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;

class UserRepository implements UserRepositoryInterface
{
   use ApiResponser;

   public function getAll()
   {
      return User::latest()->get();
   }

   public function make($data) 
   {
      $data['password'] = Hash::make($data['password']);

      $user = User::create($data);
      $user->token = $user->createToken($user->email)->accessToken;

      return $user;
   }

   public function search($name)
   {
      return User::where('name', 'LIKE', '%'.$name.'%')->get();
   }

   public function getUserById($user) 
   {
      $user = User::findOrfail($user);

      return $user;
   }

   public function updateUser($request, $user) 
   {
      $user = User::findOrFail($user);

      $user->fill($request->all());

      if ($request->has('password')) {
         $user->password = Hash::make($request->password);
      }

      $user->save();

      return $user;
   }

   public function destroyUser($user)
   {
      $user = User::findOrfail($user);

      $user->delete();

      return $user;
   }

   public function login($data) 
   {
      if(Auth::attempt(['email' => strtolower($data['email']), 'password' => $data['password']])) {
         $user = auth()->user();
         $user->token = $user->createToken($user->email)->accessToken;
         return $user;
      }

      throw new AuthenticationException('Login inválido');
   }
}