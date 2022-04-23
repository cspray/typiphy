<?php declare(strict_types=1);

namespace Cspray\Typiphy;

interface TypeUnion {

    public function getName() : string;

    /**
     * @return ObjectType[]
     */
    public function getTypes() : array;

}