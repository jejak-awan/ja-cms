<?php

namespace App\Modules\Category\Policies;

use App\Modules\Category\Models\Category;
use App\Modules\User\Models\User;

class CategoryPolicy
{
    // ...policy methods

    public function view(User $user, Category $category)
    {
        return true;
    }

    public function create(User $user)
    {
        return true;
    }

    public function update(User $user, Category $category)
    {
        return true;
    }

    public function delete(User $user, Category $category)
    {
        return true;
    }
}
