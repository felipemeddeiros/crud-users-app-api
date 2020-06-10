<?php

namespace App\Repositories\Eloquent;

use App\Models\User;
use App\Repositories\Eloquent\Base\BaseRepository;
use App\Repositories\UserRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    /**
     * Métodos ja implementados pela BaseRepository
     *
     * @method Array create(Array $atribbutes)
     * @method Array find(Integer $id)
     * @method Array all()
     */

    /**
     * UserRepository constructor.
     *
     * @param User $model
     */
    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    public function getAll()
    {
        return $this->model->latest()->get();
    }

    public function make($data)
    {
        $data['password'] = Hash::make($data['password']);

        $user        = $this->model->create($data);
        $user->token = $user->createToken($user->email)->accessToken;

        return $user;
    }

    public function search($name)
    {
        return $this->model->where('name', 'LIKE', '%' . $name . '%')->get();
    }

    public function getUserById($user)
    {
        $user = $this->model->findOrfail($user);

        return $user;
    }

    public function updateUser($request, $user)
    {
        $user = $this->model->findOrFail($user);

        $user->fill($request->all());

        if ($request->has('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return $user;
    }

    public function destroyUser($user)
    {
        $user = $this->model->findOrfail($user);

        $user->delete();

        return $user;
    }

    public function login($data)
    {
        if (Auth::attempt(['email' => strtolower($data['email']), 'password' => $data['password']])) {
            $user        = auth()->user();
            $user->token = $user->createToken($user->email)->accessToken;
            return $user;
        }

        throw new AuthenticationException('Login inválido');
    }
}
