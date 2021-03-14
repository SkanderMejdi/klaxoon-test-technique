<?php

namespace App\Domain;

interface Serializable 
{
    public function serialize(): array;
}