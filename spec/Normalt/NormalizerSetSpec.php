<?php

namespace spec\Normalt;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class NormalizerSetSpec extends ObjectBehavior
{
    /**
     * @param Symfony\Component\Serializer\Normalizer\NormalizerInterface $unsupported
     * @param Symfony\Component\Serializer\Normalizer\NormalizerInterface $supported
     */
    function let($unsupported, $supported)
    {
        $unsupported->implement('Symfony\Component\Serializer\Normalizer\DenormalizerInterface');
        $supported->implement('Symfony\Component\Serializer\Normalizer\DenormalizerInterface');

        $this->beConstructedWith(array($unsupported, $supported));
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Normalt\NormalizerSet');
    }

    function it_implements_normalizer_and_denormalizer()
    {
        $this->shouldHaveType('Symfony\Component\Serializer\Normalizer\NormalizerInterface');
        $this->shouldHaveType('Symfony\Component\Serializer\Normalizer\DenormalizerInterface');
    }

    function it_supports_normalization_if_one_of_the_normalizers_supports_it($unsupported, $supported)
    {
        $unsupported->supportsNormalization('data', null)->shouldBeCalled()->willReturn(false);
        $supported->supportsNormalization('data', null)->shouldBeCalled()->willReturn(true);

        $this->supportsNormalization('data')->shouldReturn(true);
    }

    function it_supports_denormalization_if_one_of_the_normalizers_supports_it($unsupported, $supported)
    {
        $unsupported->supportsDenormalization('data', 'string', null)->shouldBeCalled()->willReturn(false);
        $supported->supportsDenormalization('data', 'string', null)->shouldBeCalled()->willReturn(true);

        $this->supportsDenormalization('data', 'string')->shouldReturn(true);
    }

    function it_normalizes_with_supported_normalizer($unsupported, $supported)
    {
        $supported->supportsNormalization('data', null)->willReturn(true);
        $supported->normalize('data', null, array())->shouldBeCalled()->willReturn(array());

        $this->normalize('data')->shouldReturn(array());
    }

    function it_denormalizes_with_supported_normalizer($unsupported, $supported)
    {
        $supported->supportsDenormalization('data', 'format', null)->willReturn(true);
        $supported->denormalize('data', 'format', null, array())->shouldBeCalled()->willReturn(array());

        $this->denormalize('data', 'format')->shouldReturn(array());
    }

    /**
     * @param Normalt\NormalizerAware $normalizer
     */
    function it_sets_itself_as_normalizer_if_normalizer_aware($normalizer)
    {
        $normalizer->implement('Symfony\Component\Serializer\Normalizer\NormalizerInterface');
        $normalizer->implement('Symfony\Component\Serializer\Normalizer\DenormalizerInterface');

        $this->beConstructedWith(array($normalizer));

        $normalizer->supportsNormalization('data', null)->willReturn(true);
        $normalizer->supportsDenormalization('data', 'string', null)->willReturn(true);

        $normalizer->setNormalizer($this)->shouldBeCalledTimes(2);

        $normalizer->normalize('data', null, array())->willReturn();
        $normalizer->denormalize('data', 'string', null, array())->willReturn();

        $this->normalize('data');
        $this->denormalize('data', 'string');
    }
}
