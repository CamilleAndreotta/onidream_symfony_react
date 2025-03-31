<?php

namespace App\Action;

use App\Utils\EntityTypeUtils;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

abstract class AbstractActions
{   
    public function __construct(
        private SerializerInterface $serializer
    ){}

    protected function serialize(object|array $data, string $entityType)
    {   
        $group = $this->defineEntityGroup($entityType);
        return $this->serializer->serialize($data, 'json', ['groups' => [$group]]);
    }

    protected function serializeToken(array $data): string
    {
        return $this->serializer->serialize($data,'json', []);
    }

    protected function deserialize($data, string $entityClass, $object)
    {
        return $this->serializer->deserialize($data, $entityClass, 'json', [AbstractNormalizer::OBJECT_TO_POPULATE => $object]);
    }

    private function defineEntityGroup(string $entityType){
        return match($entityType){
            EntityTypeUtils::AUTHOR => 'author:read',
            EntityTypeUtils::BOOK => 'book:read',
            EntityTypeUtils::CATEGORY => 'category:show',
            EntityTypeUtils::EDITOR => 'editor:read',
            EntityTypeUtils::EXCERPT => 'excerpt:read',
            EntityTypeUtils::USER => 'user:read'
        };
    }

}