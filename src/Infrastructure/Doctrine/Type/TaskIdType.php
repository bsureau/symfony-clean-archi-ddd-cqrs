<?php

namespace App\Infrastructure\Doctrine\Type;

use App\Domain\VO\TaskId;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

class TaskIdType extends Type
{
    public const NAME = 'TaskIdType';

    /**
     * @inheritDoc
     */
    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return 'TaskId';
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): TaskId
    {
        return TaskId::create($value);
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