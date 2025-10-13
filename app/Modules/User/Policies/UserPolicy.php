<?php

namespace App\Modules\User\Policies;

use App\Modules\User\Models\User;

class UserPolicy
{
    // ...policy methods

    public function view(User $user, User $model)
    {
        return $user->id === $model->id;
    }

    public function create(User $user)
    {
        return true;
    }

    public function update(User $user, User $model)
    {
        return $user->id === $model->id;
    }

    public function delete(User $user, User $model)
    {
        return $user->id === $model->id;
    }
}
