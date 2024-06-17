<?php declare(strict_types=1);

namespace Cspray\Typiphy\Internal;

use Cspray\Typiphy\ObjectType;
use Cspray\Typiphy\TypeIntersect;

/**
 * @Internal
 */
final class NamedTypeIntersection implements TypeIntersect {

    /**
     * @param list<ObjectType> $types
     */
    public function __construct(private readonly array $types) {
        $dupes = [];
        foreach (array_count_values(array_map(static fn(ObjectType $type) => $type->name(), $this->types)) as $type => $count) {
            if ($count > 1) {
                $dupes[] = $type;
            }
        }
        if (!empty($dupes)) {
            throw new \InvalidArgumentException(sprintf(
                'The type, "%s", is already a part of the given type intersect and cannot be added again.',
                join(',', $dupes)
            ));
        }
    }

    public function name(): string {
        return join('&', array_map(static fn(ObjectType $type) => $type->name(), $this->types));
    }

    public function types(): array {
        return $this->types;
    }
}