<?php

namespace App\Service;

use Doctrine\ORM\Mapping\Entity;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

abstract class AbstractJsonService
{   
    public function __construct(
        private SerializerInterface $serializer
    ){}

    protected function serializeArrayToJson(array $data)
    {   
        return $this->serializer->serialize($data, 'json');
    }

    protected function serializeObjectToJson(object $data)
    {   
        return $this->serializer->serialize($data, 'json');
    }

    protected function deserializeJsonDataToCreateObject($data, string $entityClass)
    {
        return $this->serializer->deserialize($data, $entityClass, 'json');
    }

    protected function deserializeJsonDataToUpdateObject($data, string $entityClass, $object)
    {
        return $this->serializer->deserialize($data, $entityClass, 'json', [AbstractNormalizer::OBJECT_TO_POPULATE => $object]);
    }
}