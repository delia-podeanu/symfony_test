<?php

namespace App\Form\DataTransformer;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class CollectionToArrayTransformer implements DataTransformerInterface
{
    /**
     * s
     * @throws TransformationFailedException
     */
    public function transform(mixed $collection): mixed
    {
        if (null === $collection) {
            return [];
        }


        if (\is_array($collection)) {
            return $collection;
        }

        if (!$collection instanceof Collection) {
            throw new TransformationFailedException('Expected a Doctrine\Common\Collections\Collection object.');
        }

        return $collection->toArray();
    }

    /**
     * Transforms choice keys into entities.
     *
     * @param mixed $array An array of entities
     */
    public function reverseTransform(mixed $array): Collection
    {
        if ('' === $array || null === $array) {
            $array = [];
        } else {
            $array = (array) $array;
        }

        return new ArrayCollection($array);
    }
}
