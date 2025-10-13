<?php

namespace App\Modules\Article\Repositories;

class ArticleRepository
{
    // ...repository logic

    public function all()
    {
        return \App\Modules\Article\Models\Article::all();
    }

    public function find($id)
    {
        return \App\Modules\Article\Models\Article::findOrFail($id);
    }

    public function create(array $data)
    {
        return \App\Modules\Article\Models\Article::create($data);
    }

    public function update($id, array $data)
    {
        $article = $this->find($id);
        $article->update($data);
        return $article;
    }

    public function delete($id)
    {
        $article = $this->find($id);
        $article->delete();
        return true;
    }
}
