<?php
namespace App\Repositories;

interface UserRepositoryInterface
{
   public function all();

   public function find($id);

   public function create($attributes);

   public function getAll();

   public function make(Array $data);

   public function search(String $name);

   public function getUserById($user);

   public function updateUser(Resquest $request, $user);

   public function destroyUser($user);
}
