Normalt
=======

Normalt is a extension to Symfony Serializer than implements only the Normalization part. It comes with several
different Normalizers that can be used to normalize from object to array and denormalize from array to object.

That is the overall goal anyways.

Normalizers
-----------

* AggregateNormalizer lets you use a chain of Normalizers for the normalization and denormalization.
* AggregatePropertyNormalizer which uses reflection and introspects each property and gives it to a PropertyNormalizer.
  This allows you to have referenced objects from Doctrine in you objects but still keep a sane denormalized array
  structure.
