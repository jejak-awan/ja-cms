<?php

namespace App\Modules\Tag\Repositories;

class TagRepository
{
    // ...repository logic

    public function all()
    {
        return \App\Modules\Tag\Models\Tag::all();
    }

    public function find($id)
    {
        return \App\Modules\Tag\Models\Tag::findOrFail($id);
    }

    public function create(array $data)
    {
        return \App\Modules\Tag\Models\Tag::create($data);
    }

    public function update($id, array $data)
    {
        $tag = $this->find($id);
        $tag->update($data);
        return $tag;
    }

    public function delete($id)
    {
        $tag = $this->find($id);
        $tag->delete();
        return true;
    }
}
