<?php

namespace App\Http\Resources\API;

use Illuminate\Http\Resources\Json\JsonResource;

class BlogResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'            => $this->id,
            'title'          => $this->title,
            'description'   => $this->description,
            'is_featured'   => $this->is_featured,
            'total_views'   => $this->total_views,
            'author_id'   => $this->author_id,
            'author_name'   => optional($this->author)->display_name,
            'author_image'=> optional($this->author)->login_type != null ? optional($this->author)->social_image : getSingleMedia(optional($this->author), 'profile_image',null),
            'status'        => $this->status,
            'attchments' => getAttachments($this->getMedia('blog_attachment')),
            'attchments_array' => getAttachmentArray($this->getMedia('blog_attachment'),null),
            'deleted_at'        => $this->deleted_at,
            'created_at'        => $this->created_at,
        ];
    }
}
