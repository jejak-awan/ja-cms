<?php

namespace App\Modules\Tag\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TagResource extends JsonResource
{
    // ...resource transformation

    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'articles' => ArticleResource::collection($this->articles),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
