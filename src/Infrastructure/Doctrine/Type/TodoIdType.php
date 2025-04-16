<?php

namespace App\Infrastructure\Doctrine\Type;

use App\Domain\VO\TodoId;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

class TodoIdType extends Type
{
    public const NAME = 'TodoIdType';

    /**
     * @inheritDoc
     */
    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return 'TodoId';
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): TodoId
    {
        return TodoId::create($value);
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform): string
    {
        return $value->__toString();
    }


    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return self::NAME;
    }
}