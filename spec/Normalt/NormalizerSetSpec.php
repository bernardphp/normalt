<?php

namespace spec\Normalt;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class NormalizerSetSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Normalt\NormalizerSet');
    }

    function it_implements_normalizer_and_denormalizer()
    {
        $this->shouldHaveType('Symfony\Component\Serializer\Normalizer\NormalizerInterface');
        $this->shouldHaveType('Symfony\Component\Serializer\Normalizer\DenormalizerInterface');
    }

    /**
     * @param Symfony\Component\Serializer\Normalizer\NormalizerInterface $unsupported
     * @param Symfony\Component\Serializer\Normalizer\NormalizerInterface $supported
     */
    function it_supports_normalization_if_one_of_the_normalizers_supports_it($unsupported, $supported)
    {
        $this->beConstructedWith(array($unsupported, $supported));

        $unsupported->supportsNormalization('data', null)->shouldBeCalled()->willReturn(false);
        $supported->supportsNormalization('data', null)->shouldBeCalled()->willReturn(true);

        $this->supportsNormalization('data')->shouldReturn(true);
    }

    /**
     * @param Symfony\Component\Serializer\Normalizer\DenormalizerInterface $unsupported
     * @param Symfony\Component\Serializer\Normalizer\DenormalizerInterface $supported
     */
    function it_supports_denormalization_if_one_of_the_normalizers_supports_it($unsupported, $supported)
    {
        $this->beConstructedWith(array($unsupported, $supported));

        $unsupported->supportsDenormalization('data', 'string', null)->shouldBeCalled()->willReturn(false);
        $supported->supportsDenormalization('data', 'string', null)->shouldBeCalled()->willReturn(true);

        $this->supportsDenormalization('data', 'string')->shouldReturn(true);
    }

    /**
     * @param Symfony\Component\Serializer\Normalizer\NormalizerInterface $unsupported
     * @param Symfony\Component\Serializer\Normalizer\NormalizerInterface $supported
     */
    function it_normalizes_with_supported_normalizer($unsupported, $supported)
    {
        $this->beConstructedWith(array($unsupported, $supported));

        $supported->supportsNormalization('data', null)->willReturn(true);
        $supported->normalize('data', null, array())->shouldBeCalled()->willReturn(array());

        $this->normalize('data')->shouldReturn(array());
    }

    /**
     * @param Symfony\Component\Serializer\Normalizer\DenormalizerInterface $unsupported
     * @param Symfony\Component\Serializer\Normalizer\DenormalizerInterface $supported
     */
    function it_denormalizes_with_supported_normalizer($unsupported, $supported)
    {
        $this->beConstructedWith(array($unsupported, $supported));

        $supported->supportsDenormalization('data', 'format', null)->willReturn(true);
        $supported->denormalize('data', 'format', null, array())->shouldBeCalled()->willReturn(array());

        $this->denormalize('data', 'format')->shouldReturn(array());
    }
}
