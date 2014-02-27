<?php

namespace Normalt;

use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

/**
 * @package Normalt
 */
class NormalizerSet implements NormalizerInterface, DenormalizerInterface
{
    private $normalizers;
    private $denormalizers;

    public function __construct($normalizers = array())
    {
        array_map(array($this, 'add'), $normalizers);
    }

    public function add($normalizer)
    {
        if ($normalizer instanceof NormalizerInterface) {
            $this->normalizers[] = $normalizer;
        }

        if ($normalizer instanceof DenormalizerInterface) {
            $this->denormalizers[] = $normalizer;
        }
    }

    public function normalize($data, $class, $format = null, $context = array())
    {
    }

    public function denormalize($data, $class, $format = null, $context = array())
    {
    }

    public function supportsNormalization()
    {
    }

    public function supportsDenormalization()
    {
    }

    private function getNormalizer($data, $format = null)
    {
    }

    private function getDenormalizer($data, $format = null)
    {
    }
}
