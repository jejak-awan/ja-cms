<?php

namespace App\Modules\Category\Repositories;

class CategoryRepository
{
    // ...repository logic

    public function all()
    {
        return \App\Modules\Category\Models\Category::all();
    }

    public function find($id)
    {
        return \App\Modules\Category\Models\Category::findOrFail($id);
    }

    public function create(array $data)
    {
        return \App\Modules\Category\Models\Category::create($data);
    }

    public function update($id, array $data)
    {
        $category = $this->find($id);
        $category->update($data);
        return $category;
    }

    public function delete($id)
    {
        $category = $this->find($id);
        $category->delete();
        return true;
    }
}
