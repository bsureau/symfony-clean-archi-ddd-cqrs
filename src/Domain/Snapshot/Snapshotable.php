<?php

namespace App\Domain\Snapshot;

interface Snapshotable
{
    public function toSnapshot(): mixed;

    public static function fromSnapshot(mixed $snapshot): self;
}