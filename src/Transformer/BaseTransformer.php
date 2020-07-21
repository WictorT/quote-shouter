<?php
namespace App\Transformer;

abstract class BaseTransformer
{
    abstract public function transform($entity);

    public function transformMultiple(array $entities): array
    {
        $transformedData = [];

        foreach ($entities as $entity) {
            $transformedData[] = $this->transform($entity);
        }

        return $transformedData;
    }
}
