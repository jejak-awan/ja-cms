<?php

namespace App\Modules\Page\Repositories;

class PageRepository
{
    // ...repository logic

    public function all()
    {
        return \App\Modules\Page\Models\Page::all();
    }

    public function find($id)
    {
        return \App\Modules\Page\Models\Page::findOrFail($id);
    }

    public function create(array $data)
    {
        return \App\Modules\Page\Models\Page::create($data);
    }

    public function update($id, array $data)
    {
        $page = $this->find($id);
        $page->update($data);
        return $page;
    }

    public function delete($id)
    {
        $page = $this->find($id);
        $page->delete();
        return true;
    }
}
