<?php

namespace spec\Normalt\Normalizer;

// Fixtures
require __DIR__ . '/../../Fixtures/Import.php';
require __DIR__ . '/../../Fixtures/ImportWrapper.php';

class RecursiveReflectionNormalizerSpec extends \PhpSpec\ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Normalt\Normalizer\RecursiveReflectionNormalizer');
    }

    function it_is_normalizer_aware()
    {
        $this->shouldHaveType('Normalt\NormalizerAware');
    }

    function it_is_normalizer_and_denormalizer()
    {
        $this->shouldHaveType('Symfony\Component\Serializer\Normalizer\NormalizerInterface');
        $this->shouldHaveType('Symfony\Component\Serializer\Normalizer\DenormalizerInterface');
    }

    /**
     * @param stdClass $std
     */
    function it_supports_objects_for_normalization($std)
    {
        $this->supportsNormalization($std)->shouldReturn(true);
        $this->supportsNormalization('stirng')->shouldReturn(false);
        $this->supportsNormalization(true)->shouldReturn(false);
        $this->supportsNormalization(array())->shouldReturn(false);
    }

    function it_recursively_normalizes_an_object()
    {
        $fixture = new \Fixtures\Import;

        $this->normalize($fixture)->shouldReturn(array(
            'id' => 1,
            'metadata' => array(
                'name' => 'RussianUsers',
                'file' => 's3://bucket/users.csv',
            ),
        ));
    }

    /**
     * @param Symfony\Component\Serializer\Normalizer\NormalizerInterface $normalizer
     */
    function it_delegates_to_normalizer_when_unknown_object_is_called($normalizer)
    {
        $this->setNormalizer($normalizer);

        $import = new \Fixtures\Import;
        $wrapper = new \Fixtures\ImportWrapper($import);

        $normalizer->supportsNormalization($import, null)->willReturn(true);
        $normalizer->normalize($import, null)->shouldBeCalled()->willReturn(array(
            'id' => 1,
            'class' => 'Fixtures\\Import',
        ));

        $this->normalize($wrapper)->shouldReturn(array(
            'import' => array('id' => 1, 'class' => 'Fixtures\\Import'),
        ));
    }

    /**
     * @param Symfony\Component\Serializer\Normalizer\NormalizerInterface $normalizer
     */
    function it_delegates_for_each_property($normalizer)
    {
        $import = new \Fixtures\Import;
        $wrapper = new \Fixtures\ImportWrapper($import);

        $this->beConstructedWith(array($normalizer));

        $normalizer->supportsNormalization($import, null)->shouldBeCalled()->willReturn(true);
        $normalizer->normalize($import, null)->shouldBeCalled(true)->willReturn(array(
            'id' => 1,
            'class' => 'Fixtures\\Import',
        ));

        $this->normalize($wrapper)->shouldReturn(array(
            'import' => array('id' => 1, 'class' => 'Fixtures\\Import'),
        ));
    }
}
