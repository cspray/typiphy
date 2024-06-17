<?php declare(strict_types=1);

namespace Cspray\Typiphy;

interface TypeIntersect {

    /**
     * @return non-empty-string
     */
    public function name() : string;

    /**
     * @return list<ObjectType>
     */
    public function types() : array;

}