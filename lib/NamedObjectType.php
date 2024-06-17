<?php declare(strict_types=1);

namespace Cspray\Typiphy\Internal;

use Cspray\Typiphy\ObjectType;

/**
 * @Internal
 */
final class NamedObjectType implements ObjectType {

    public function __construct(
        private readonly string $name
    ) {
        if (!class_exists($name) && !interface_exists($name)) {
            throw new \InvalidArgumentException(sprintf(
                'The name for an ObjectType must be a valid, loadable class but "%s" was given.',
                $name
            ));
        }
    }

    public function __toString(): string {
        return $this->name();
    }

    public function name(): string {
        return $this->name;
    }
}