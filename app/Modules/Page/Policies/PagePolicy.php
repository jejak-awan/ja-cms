<?php

namespace App\Modules\Page\Policies;

use App\Modules\Page\Models\Page;
use App\Modules\User\Models\User;

class PagePolicy
{
    // ...policy methods

    public function view(User $user, Page $page)
    {
        return true;
    }

    public function create(User $user)
    {
        return true;
    }

    public function update(User $user, Page $page)
    {
        return $user->id === $page->user_id;
    }

    public function delete(User $user, Page $page)
    {
        return $user->id === $page->user_id;
    }
}
