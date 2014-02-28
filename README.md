Normalt
=======

Normalt is a extension to Symfony Serializer than implements only the Normalization part. It comes with several
different Normalizers that can be used to normalize from object to array and denormalize from array to object.

Getting Started
---------------

Each normalizer can be used on its own, but you can also use a `NormalizerSet` to use many different dependent on
the type you are normalizing, just like when using the serializer.

``` php
use Normalt\NormalizerSet;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Normalizer\CustomNormalizer;

$set = new NormalizerSet(array(
    new GetSetMethodNormalizer,
    new CustomNormalizer,
));

$normalized = $set->normalize(new MyObject);
$object = $set->denormalize($normalized);
```

Any normalizer that is used through the `NormalizerSet` will have an instance of it set
if they implement `Normalt\NormalizerAware`. Same as if you have a Normalizer that implements
`SerializerAwareInterface` and use the Serializer.

RecursiveReflectionNormalizer
-----------------------------

It is a special normalizer that uses a list of normalizers, but instead of applying on an per object basis it
traverses the properties and applies normalization to each. If a property contains an array this is also traversed.

This can be used together with `DoctrineNormalizer` to automatically convert from Entity to array and back again.


``` php

use Normalt\Normalizer\RecursiveReflectionNormalizer;
use Normalt\Normalizer\DoctrineNormalizer;

$normalizer = new RecursiveReflectionNormalizer(array(
    new DoctrineNormalizer($objectManager),
));


// Assuming this wraps an entity called MyModel.
// we would get the following array when normalized (assuming its identifier is 1
// array(
//    'model' => array('className' => 'MyModel', 1),
// )
class MyModelWrapper {
    protected $model;

    public function __construct()
    {
        $this->model = new MyModel;
    }
}

$normalizer->normalize(new MyModelWrapper);
```

Naming Suggestions
------------------

Because Normalizer is used in the Serializer and the PropertyNormalizer concepts it is very easy to get
confused. So here is some suggestion for renaming the NormalizerSet.

### Marshal

> ...marshalling (sometimes spelled marshaling) is the process of transforming the memory representation of an
> object to a data format suitable for storage or transmission, and it is typically used when data must be
> moved between different parts of a computer program...

seems like the concept match up to what we are doing here, if we look at array as a suitable representation
for storage.

This would name the NormalizerSet `Marshaller` (like in Java) and leave the PropertyNormalizer be.
