<?php declare(strict_types=1);

namespace Cspray\Typiphy;

interface TypeUnion {

    /**
     * @return non-empty-string
     */
    public function name() : string;

    /**
     * @return list<ObjectType|Type>
     */
    public function types() : array;

}