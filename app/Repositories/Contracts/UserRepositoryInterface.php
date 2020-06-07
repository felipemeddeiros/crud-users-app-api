<?php
namespace App\Repositories\Contracts;

interface UserRepositoryInterface
{
	
   public function getAll();

   public function make(Array $data);

   public function search(String $name);

   public function getUserById($user);

   public function updateUser(Resquest $request, $user);

   public function destroyUser($user);

}