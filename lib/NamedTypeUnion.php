<?php declare(strict_types=1);

namespace Cspray\Typiphy\Internal;

use Cspray\Typiphy\Type;
use Cspray\Typiphy\TypeUnion;

/**
 * @Internal
 */
final class NamedTypeUnion implements TypeUnion {

    public function __construct(private readonly array $types) {
        $dupes = [];
        foreach (array_count_values(array_map(fn($type) => $type->getName(), $this->types)) as $type => $count) {
            if ($type === 'void') {
                throw new \InvalidArgumentException('The "void" type can only be used alone and cannot be added to a type union.');
            }
            if ($count > 1) {
                $dupes[] = $type;
            }
        }
        if (!empty($dupes)) {
            throw new \InvalidArgumentException(sprintf(
                'The type, "%s", is already a part of the given type union and cannot be added again.',
                join(',', $dupes)
            ));
        }
    }

    public function getName(): string {
        return join('|', array_map(fn(Type $type) => $type->getName(), $this->types));
    }

    public function getTypes(): array {
        return $this->types;
    }
}