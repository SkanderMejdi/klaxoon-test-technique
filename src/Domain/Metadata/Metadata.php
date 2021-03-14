<?php

namespace App\Domain\Metadata;

use App\Domain\Serializable;

interface Metadata extends Serializable
{
    public function getType(): string;
}