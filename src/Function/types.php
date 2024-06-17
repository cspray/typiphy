<?php declare(strict_types=1);

namespace Cspray\Typiphy;

use Cspray\Typiphy\Internal\NamedObjectType;
use Cspray\Typiphy\Internal\NamedType;
use Cspray\Typiphy\Internal\NamedTypeIntersection;
use Cspray\Typiphy\Internal\NamedTypeUnion;

require_once dirname(__DIR__, 2) . '/lib/NamedObjectType.php';
require_once dirname(__DIR__, 2) . '/lib/NamedType.php';
require_once dirname(__DIR__, 2) . '/lib/NamedTypeIntersection.php';
require_once dirname(__DIR__, 2) . '/lib/NamedTypeUnion.php';

function stringType() : Type {
    static $type;
    if (!isset($type)) {
        $type = new NamedType('string');
    }
    return $type;
}

function intType() : Type {
    static $type;
    if (!isset($type)) {
        $type = new NamedType('int');
    }
    return $type;
}

function floatType() : Type {
    static $type;
    if (!isset($type)) {
        $type = new NamedType('float');
    }
    return $type;
}

function boolType() : Type {
    static $type;
    if (!isset($type)) {
        $type = new NamedType('bool');
    }
    return $type;
}

function arrayType() : Type {
    static $type;
    if (!isset($type)) {
        $type = new NamedType('array');
    }
    return $type;
}

function callableType() : Type {
    static $type;
    if (!isset($type)) {
        $type = new NamedType('callable');
    }
    return $type;
}

function iterableType() : Type {
    static $type;
    if (!isset($type)) {
        $type = new NamedType('iterable');
    }
    return $type;
}

function nullType() : Type {
    static $type;
    if (!isset($type)) {
        $type = new NamedType('null');
    }
    return $type;
}

function voidType() : Type {
    static $type;
    if (!isset($type)) {
        $type = new NamedType('void');
    }
    return $type;
}

function mixedType() : Type {
    static $type;
    if (!isset($type)) {
        $type = new NamedType('mixed');
    }
    return $type;
}

function typeUnion(Type $firstType, Type $secondType, Type... $additionalTypes) : TypeUnion {
    static $types = [];
    $allTypes = [$firstType, $secondType, ...$additionalTypes];
    $typeNames = array_map(fn($t) => $t->name(), $allTypes);
    sort($typeNames);
    $key = join('', $typeNames);
    if (!isset($types[$key])) {
        $types[$key] = new NamedTypeUnion($allTypes);
    }
    return $types[$key];
}

function typeIntersect(ObjectType $firstType, ObjectType $secondType, ObjectType... $additionalTypes) : TypeIntersect {
    static $types = [];
    $allTypes = [$firstType, $secondType, ...$additionalTypes];
    $typeNames = array_map(fn($t) => $t->name(), $allTypes);
    sort($typeNames);
    $key = join('', $typeNames);
    if (!isset($types[$key])) {
        $types[$key] = new NamedTypeIntersection($allTypes);
    }
    return $types[$key];
}

/**
 * @template T
 * @param class-string<T> $type
 * @return ObjectType<T>
 */
function objectType(string $type) : ObjectType {
    static $types = [];
    if (!isset($types[$type])) {
        $types[$type] = new NamedObjectType($type);
    }
    return $types[$type];
}
