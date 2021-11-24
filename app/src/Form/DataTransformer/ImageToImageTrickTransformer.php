<?php

namespace App\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class ImageToImageTrickTransformer implements DataTransformerInterface
{
    public function transform($value)
    {
        // TODO: Implement transform() method.
        dd('transform', $value);
    }

    public function reverseTransform($value)
    {
        // TODO: Implement reverseTransform() method.
        dd('reverseTransform', $value);
    }
}
