<?php declare(strict_types=1);

namespace Cspray\Typiphy;

interface TypeUnion {

    public function getName() : string;

    /**
     * @return array<array-key, ObjectType|Type>
     */
    public function getTypes() : array;

}