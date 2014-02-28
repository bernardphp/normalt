<?php

namespace spec\Normalt\Normalizer;

use stdClass;

class DoctrineNormalizerSpec extends \PhpSpec\ObjectBehavior
{
    /**
     * @param Doctrine\Common\Persistence\ObjectManager $manager
     */
    function let($manager)
    {
        $this->beConstructedWith($manager);
    }

    function it_is_a_denormalizer_and_normalizer()
    {
        $this->shouldHaveType('Symfony\Component\Serializer\Normalizer\NormalizerInterface');
        $this->shouldHaveType('Symfony\Component\Serializer\Normalizer\DenormalizerInterface');
    }

    /**
     * @param Doctrine\Common\Persistence\Mapping\ClassMetadataFactory $metadataFactory
     */
    function it_supports_object_that_have_metadata($metadataFactory, $manager)
    {
        $manager->getMetadataFactory()->willReturn($metadataFactory);

        $std = new stdClass;
        $metadataFactory->hasMetadataFor('stdClass')->willReturn(true);

        $this->supportsNormalization($std)->shouldReturn(true);
        $this->supportsDenormalization(array('className' => 'stdClass'), 'stdClass')->shouldReturn(true);
    }

    /**
     * @param Doctrine\Common\Persistence\Mapping\ClassMetadata $metadata
     */
    function it_normalizes_entity($metadata, $manager)
    {
        $manager->getClassMetadata('stdClass')->willReturn($metadata);

        $std = new stdClass;

        $metadata->getName()->willReturn('stdClass');
        $metadata->getIdentifierValues($std)->willReturn(array(1));

        $this->normalize($std)->shouldReturn(array('className' => 'stdClass', 1));
    }

    function it_denormalizes_entity($manager)
    {
        $std = new stdClass;

        $manager->find('stdClass', array(1))->willReturn($std);

        $data = array('className' => 'stdClass', 1);

        $this->denormalize($data, 'array')->shouldReturn($std);
    }
}
