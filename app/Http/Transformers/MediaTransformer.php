<?php

namespace App\Http\Transformers;

use App\Models\Media;
use League\Fractal\TransformerAbstract;

class MediaTransformer extends TransformerAbstract
{
    public function transform(Media $media): array
    {
        return [
            'id'         => $media->id,
            'file_name'  => $media->file_name,
            'url'        => $media->getFullUrl(),
            'size'       => [
                'byte'      => $media->size,
                'formatted' => $media->human_readable_size,
            ],
            'mime_type'  => $media->mime_type,
            'responsive' => [
                'thumb'  => $this->getResponsive('thumb', $media),
                'small'  => $this->getResponsive('small', $media),
                'medium' => $this->getResponsive('medium', $media),
                'large'  => $this->getResponsive('large', $media),
            ],
            'properties' => null,
        ];
    }

    protected function getResponsive(string $type, Media $media): ?string
    {
        if ( !$media->getGeneratedConversions()->get($type) ) {
            return null;
        }

        return $media->getUrl($type);
    }
}
