<?php

namespace App\Modules\User\Repositories;

class UserRepository
{
    // ...repository logic

    public function all()
    {
        return \App\Modules\User\Models\User::all();
    }

    public function find($id)
    {
        return \App\Modules\User\Models\User::findOrFail($id);
    }

    public function create(array $data)
    {
        return \App\Modules\User\Models\User::create($data);
    }

    public function update($id, array $data)
    {
        $user = $this->find($id);
        $user->update($data);
        return $user;
    }

    public function delete($id)
    {
        $user = $this->find($id);
        $user->delete();
        return true;
    }
}
