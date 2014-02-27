<?php

namespace Normalt\Normalizer;

use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

/**
 * Uses its own set of PropertyNormalizers to normalize / denormalize properties
 *
 * @package Normalt
 */
class PropertyNormalizer implements NormalizerInterface, DenormalizerInterface
{
    protected $normalizers;

    public function __construct($normalizers = array())
    {
        $this->normalizers = $normalizers;
    }

    public function normalize($object, $format = null, array $context = array())
    {

    }

    public function denormalize($data, $type, $format = null, array $context = array())
    {
    }

    public function supportsNormalization($data, $format = null)
    {
        return is_object($data);
    }

    public function supportsDenormalization($data, $type, $format = null)
    {
        return class_exists($type);
    }

    private function createPrototype($class)
    {
        return unserialize(sprintf('O:%u:"%s":0:{}', strlen($class), $class));
    }
}
