<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use JoBins\LaravelRepository\Serializer\DataArraySerializer;
use League\Fractal\Manager;
use League\Fractal\Resource\Collection as ResourceCollection;
use League\Fractal\Resource\Item as ResourceItem;
use League\Fractal\TransformerAbstract;

final class Helper
{
    public static function transform(Model|Collection $model, TransformerAbstract $transformer): array
    {
        $manager = new Manager;
        $manager->setSerializer(new DataArraySerializer);

        if ($model instanceof Collection) {
            $resource = new ResourceCollection($model, $transformer);
        } else {
            $resource = new ResourceItem($model, $transformer);
        }

        return $manager->createData($resource)->toArray();
    }
}
