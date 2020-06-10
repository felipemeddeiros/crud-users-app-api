<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginUser;
use App\Http\Requests\StoreUser;
use App\Http\Requests\UpdateUser;
use App\Repositories\UserRepositoryInterface;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UserController extends Controller
{
    use ApiResponser;

    protected $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = $this->userRepository->getAll();

        return $this->successResponse($user);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \App\Models\User
     */
    public function store(StoreUser $request)
    {
        $user = $this->userRepository->make($request->all());

        return $this->successResponse($user, Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show($user)
    {
        $user = $this->userRepository->getUserById($user);

        return $this->successResponse($user);
    }

      /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUser $request, $user)
    {
        $user = $this->userRepository->updateUser($request, $user);

        return $this->successResponse($user);
    }

    /**
     * Delete the user
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy($user)
    {
        $user = $this->userRepository->destroyUser($user);

        return $this->successResponse($user);
    }

    /**
     * Allows the user to login
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \App\Models\User
     */
    public function login(LoginUser $request)
    {
        $user = $this->userRepository->login($request->all());

        return $this->successResponse($user);
    }
}
