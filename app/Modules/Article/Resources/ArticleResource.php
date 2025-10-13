<?php

namespace App\Modules\Article\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ArticleResource extends JsonResource
{
    // ...resource transformation

    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'content' => $this->content,
            'category' => new \App\Modules\Category\Resources\CategoryResource($this->category),
            'user' => new \App\Modules\User\Resources\UserResource($this->user),
            'tags' => TagResource::collection($this->tags),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
