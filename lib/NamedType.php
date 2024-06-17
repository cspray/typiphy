<?php declare(strict_types=1);

namespace Cspray\Typiphy\Internal;

use Cspray\Typiphy\Type;

/**
 * @Internal
 */
final class NamedType implements Type {

    public function __construct(private readonly string $name) {}

    public function __toString(): string {
        return $this->name();
    }

    public function name(): string {
        return $this->name;
    }
}