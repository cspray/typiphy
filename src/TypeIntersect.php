<?php declare(strict_types=1);

namespace Cspray\Typiphy;

use Countable;
use IteratorAggregate;

interface TypeIntersect {

    public function getName() : string;

    /**
     * @return Type[]
     */
    public function getTypes() : array;

}