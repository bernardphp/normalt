<?php

namespace Normalt;

use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

/**
 * Functionality extracted from Symfony\Component\Serializer\Serializer in order to
 * limit it to denormalization and normalization while still giving the user lot of
 * flexibility when needed.
 *
 * @package Normalt
 */
class NormalizerSet implements NormalizerInterface, DenormalizerInterface
{
    protected $normalizers = array();
    protected $denormalizers = array();

    public function __construct($normalizers = array())
    {
        array_map(array($this, 'add'), $normalizers);
    }

    public function normalize($object, $format = null, array $context = array())
    {
        if ($normalizer = $this->getNormalizer($object, $format)) {
            return $normalizer->normalize($object, $format, $context);
        }

        throw new UnexpectedValueException('No supported normalizer found for "' . get_class($object) . '".');
    }

    public function denormalize($data, $class, $format = null, array $context = array())
    {
        if ($denormalizer = $this->getDenormalizer($data, $class, $format)) {
            return $denormalizer->denormalize($data, $class, $format, $context);
        }

        throw new UnexpectedValueException('No supported normalizer found for "' . $class . '".');
    }

    public function supportsNormalization($data, $format = null)
    {
        return (boolean) $this->getNormalizer($data, $format);
    }

    public function supportsDenormalization($data, $type, $format = null)
    {
        return (boolean) $this->getDenormalizer($data, $type, $format);
    }

    protected function getNormalizer($data, $format = null)
    {
        foreach ($this->normalizers as $normalizer) {
            if (false == $normalizer->supportsNormalization($data, $format)) {
                continue;
            }

            if ($normalizer instanceof NormalizerAware) {
                $normalizer->setNormalizer($this);
            }

            return $normalizer;
        }
    }

    protected function getDenormalizer($data, $type, $format = null)
    {
        foreach ($this->denormalizers as $normalizer) {
            if (false == $normalizer->supportsDenormalization($data, $type, $format)) {
                continue;
            }

            if ($normalizer instanceof NormalizerAware) {
                $normalizer->setNormalizer($this);
            }

            return $normalizer;
        }
    }

    protected function add($normalizer)
    {
        if ($normalizer instanceof NormalizerInterface) {
            $this->normalizers[] = $normalizer;
        }

        if ($normalizer instanceof DenormalizerInterface) {
            $this->denormalizers[] = $normalizer;
        }
    }

}
