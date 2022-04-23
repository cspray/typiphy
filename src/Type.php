<?php declare(strict_types=1);

namespace Cspray\Typiphy;

use Stringable;

interface Type extends Stringable {

    public function getName() : string;

}