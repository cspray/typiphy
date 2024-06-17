<?php declare(strict_types=1);

namespace Cspray\Typiphy;

interface Type {

    /**
     * @return non-empty-string
     */
    public function name() : string;

}