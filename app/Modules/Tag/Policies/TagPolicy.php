<?php

namespace App\Modules\Tag\Policies;

use App\Modules\Tag\Models\Tag;
use App\Modules\User\Models\User;

class TagPolicy
{
    // ...policy methods

    public function view(User $user, Tag $tag)
    {
        return true;
    }

    public function create(User $user)
    {
        return true;
    }

    public function update(User $user, Tag $tag)
    {
        return true;
    }

    public function delete(User $user, Tag $tag)
    {
        return true;
    }
}
