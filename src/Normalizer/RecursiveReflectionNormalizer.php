<?php

namespace Normalt\Normalizer;

use Normalt\NormalizerSet;
use Normalt\NormalizerAware;
use ReflectionObject;

/**
 * This uses reflection to run a recursive normalization on every property
 * that is found, if the property contains an array each member of that
 * array will be normalized if a normalizer for it is found.
 *
 * If an object is found that is not supported the the process will be aborted
 * and an exception will be raised.
 *
 * When deserializing it is important that each denormalizer knows when an array
 * should be turned into an object.
 *
 * @package Normalt
 */
class RecursiveReflectionNormalizer extends NormalizerSet implements NormalizerAware
{
    protected $normalizer;

    public function normalize($object, $format = null, array $context = array())
    {
        $normalized = array();
        $reflection = new ReflectionObject($object);

        foreach ($reflection->getProperties() as $property) {
            if ($property->isPrivate()) {
                continue;
            }

            $property->setAccessible(true);

            $normalized[$property->getName()] = $this->normalizeValue($property->getValue($object));
        }

        return $normalized;
    }

    private function normalizeValue($data)
    {
        switch (true) {
            case is_scalar($data):
                return $data;

            case is_array($data):
                return $this->normalizeValues($data);

            case $normalizer = $this->getNormalizer($data):
                return $normalizer->normalize($data);

            default:
                return $this->normalizer->normalize($data);
        }
    }

    private function normalizeValues($data)
    {
        $normalized = array();

        foreach ($data as $key => $value) {
            $normalized[$key] = $this->normalizeValue($value);
        }

        return $normalized;
    }

    public function denormalize($data, $type, $format = null, array $context = array())
    {
    }

    public function setNormalizer($normalizer)
    {
        $this->normalizer = $normalizer;
    }

    public function supportsNormalization($data, $format = null)
    {
        return is_object($data);
    }

    public function supportsDenormalization($data, $type, $format = null)
    {
        return is_array($data) && class_exists($type);
    }

    private function createPrototype($class)
    {
        return unserialize(sprintf('O:%u:"%s":0:{}', strlen($className), $className));
    }
}
