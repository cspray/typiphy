<?php declare(strict_types=1);

namespace Cspray\Typiphy;

/**
 * A Type that can be instantiated as an object.
 *
 * Implementations should take steps to ensure that non-object types cannot be represented by this interface. For example,
 * the type "string" is not an object and therefore should not be able to be represented as an ObjectType.
 */
interface ObjectType extends Type {}