Normalt
=======

Normalt is a extension to Symfony Serializer than implements only the Normalization part. It comes with several
different Normalizers that can be used to normalize from object to array and denormalize from array to object.

That is the overall goal anyways.

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
```

Included Normalizers
--------------------

Other than the normalt normalizers from the serializer component we have added some more special
ones that can be used. Theese can be used with the normal Serializer aswell.

* `PropertyNormalizer` which uses reflection and an additional array of serializers that get each property name, and value
  as a seperate normalization.
