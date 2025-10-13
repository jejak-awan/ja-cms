<?php

namespace App\Modules\Article\Policies;

use App\Modules\Article\Models\Article;
use App\Modules\User\Models\User;

class ArticlePolicy
{
    // ...policy methods

    public function view(User $user, Article $article)
    {
        return true;
    }

    public function create(User $user)
    {
        return true;
    }

    public function update(User $user, Article $article)
    {
        return $user->id === $article->user_id;
    }

    public function delete(User $user, Article $article)
    {
        return $user->id === $article->user_id;
    }
}
