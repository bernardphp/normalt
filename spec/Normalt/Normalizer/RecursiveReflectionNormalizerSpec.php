<?php

namespace spec\Normalt\Normalizer;

class RecursiveReflectionNormalizerSpec extends \PhpSpec\ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Normalt\Normalizer\RecursiveReflectionNormalizer');
    }

    function it_is_normalizer_aware()
    {
        $this->shouldHaveType('Normalt\Normalizer\AggregateNormalizerAware');
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
     * @param Normalt\Normalizer\AggregateNormalizer $aggregate
     */
    function it_delegates_to_marshaller_when_unknown_object_is_called($aggregate)
    {
        $this->setAggregateNormalizer($aggregate);

        $import = new \Fixtures\Import;
        $wrapper = new \Fixtures\ImportWrapper($import);

        $aggregate->supportsNormalization($import, null)->willReturn(true);
        $aggregate->normalize($import, null)->shouldBeCalled()->willReturn(array(
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

    /**
     * @param Normalt\Normalizer\AggregateNormalizer $aggregate
     */
    function it_denormalizes_into_object($aggregate)
    {
        $data = array(
            'id' => 10,
            'metadata' => array(
                'name' => 'BelgianUsers',
            ),
        );

        $this->setAggregateNormalizer($aggregate);

        $import = $this->denormalize($data, 'Fixtures\Import');

        $import->shouldBeAnInstanceOf('Fixtures\\Import');
        $import->getId()->shouldReturn(10);
        $import->getMetadata()->shouldReturn(array(
            'name' => 'BelgianUsers',
        ));
    }

    /**
     * @param Symfony\Component\Serializer\Normalizer\DenormalizerInterface $normalizer
     * @param Normalt\Normalizer\AggregateNormalizer $aggregate
     * @param Fixtures\Import $import
     */
    function it_denormalizes_complex_object($normalizer, $aggregate, $import)
    {
        $data = array(
            'import' => array(
                'class' => 'Fixtures\Import',
                'id' => 10,
            ),
        );

        $normalizer->supportsDenormalization($data['import'], 'array', null)->willReturn(true);
        $normalizer->denormalize($data['import'], 'array', null)->willReturn($import);

        $this->beConstructedWith(array($normalizer));
        $this->setAggregateNormalizer($aggregate);

        $wrapper = $this->denormalize($data, 'Fixtures\ImportWrapper');
        $wrapper->shouldBeAnInstanceOf('Fixtures\ImportWrapper');
        $wrapper->getImport()->shouldReturn($import);
    }
}
