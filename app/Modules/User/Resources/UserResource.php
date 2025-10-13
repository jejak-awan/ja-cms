<?php

namespace App\Modules\User\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    // ...resource transformation

    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'articles' => ArticleResource::collection($this->articles),
            'pages' => PageResource::collection($this->pages),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
