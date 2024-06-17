<?php declare(strict_types=1);

namespace Cspray\Typiphy;

use PHPUnit\Framework\TestCase;

/**
 * @covers ::\Cspray\Typiphy\stringType
 * @covers ::\Cspray\Typiphy\intType
 * @covers ::\Cspray\Typiphy\floatType
 * @covers ::\Cspray\Typiphy\boolType
 * @covers ::\Cspray\Typiphy\arrayType
 * @covers ::\Cspray\Typiphy\mixedType
 * @covers ::\Cspray\Typiphy\iterableType
 * @covers ::\Cspray\Typiphy\nullType
 * @covers ::\Cspray\Typiphy\voidType
 * @covers ::\Cspray\Typiphy\callableType
 * @covers ::\Cspray\Typiphy\objectType
 * @covers ::\Cspray\Typiphy\typeUnion
 * @covers ::\Cspray\Typiphy\typeIntersect
 */
class TypeFunctionsTest extends TestCase {

    public static function fullyQualifiedNameProvider() : array {
        return [
            ['string', '\\Cspray\\Typiphy\\stringType'],
            ['int', '\\Cspray\\Typiphy\\intType'],
            ['float', '\\Cspray\\Typiphy\\floatType'],
            ['bool', '\\Cspray\\Typiphy\\boolType'],
            ['array', '\\Cspray\\Typiphy\\arrayType'],
            ['mixed', '\\Cspray\\Typiphy\\mixedType'],
            ['iterable', '\\Cspray\\Typiphy\\iterableType'],
            ['null', '\\Cspray\\Typiphy\\nullType'],
            ['void', '\\Cspray\\Typiphy\\voidType'],
            ['callable', '\\Cspray\\Typiphy\\callableType']
        ];
    }

    /**
     * @return void
     * @dataProvider fullyQualifiedNameProvider
     */
    public function testTypeFullyQualifiedName(string $name, callable $typeProvider) {
        $this->assertSame($name, $typeProvider()->name());
    }

    /**
     * @dataProvider fullyQualifiedNameProvider
     */
    public function testTypeToString(string $name, callable $typeProvider) {
        $this->assertSame($name, (string) $typeProvider());
    }

    /**
     * @dataProvider fullyQualifiedNameProvider
     */
    public function testTypeSameObject(string $name, callable $typeProvider) {
        $this->assertSame($typeProvider(), $typeProvider());
    }

    /**
     * @return void
     */
    public function testObjectTypeGetFullyQualifiedName() {
        $this->assertSame($this::class, objectType($this::class)->name());
    }

    /**
     * @return void
     */
    public function testObjectTypeToString() {
       $this->assertSame($this::class, (string) objectType($this::class));
    }

    /**
     * @return void
     */
    public function testObjectTypeAreSame() {
        $this->assertSame(objectType($this::class), objectType($this::class));
    }

    /**
     * @return void
     */
    public function testObjectTypeAreNotSame() {
        $this->assertNotSame(objectType($this::class), objectType(ObjectType::class));
    }

    /**
     * @return void
     */
    public function testObjectTypeInstanceOfObjectType() {
        $this->assertInstanceOf(ObjectType::class, objectType($this::class));
    }

    /**
     * @return void
     */
    public function testObjectTypeIsNotClassThrowsException() {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('The name for an ObjectType must be a valid, loadable class but "NotAClass" was given.');
        objectType('NotAClass');
    }

    public static function duplicatedTypesProvider() : array {
        return [
            [[stringType(), stringType()], "string"],
            [[intType(), stringType(), intType()], "int"],
            [[objectType(\stdClass::class), arrayType(), intType(), objectType(\stdClass::class)], \stdClass::class]
        ];
    }

    /**
     * @return void
     * @dataProvider duplicatedTypesProvider
     */
    public function testTypeUnionThrowsExceptionWithDuplicateTypes(array $duplicatedTypes, string $duplicatedType) {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('The type, "' . $duplicatedType . '", is already a part of the given type union and cannot be added again.');
        typeUnion(...$duplicatedTypes);
    }

    /**
     * @return void
     */
    public function testTypeUnionThrowsExceptionIfVoidIncluded() {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('The "void" type can only be used alone and cannot be added to a type union.');
        typeUnion(stringType(), voidType());
    }

    /**
     * @return void
     */
    public function testTypeUnionTypes() {
        $typeUnion = typeUnion(stringType(), intType(), floatType());
        $this->assertSame([stringType(), intType(), floatType()], $typeUnion->types());
    }

    public static function sameTypeUnions() : array {
        return [
            [[stringType(), intType()], [stringType(), intType()]],
            [[stringType(), floatType(), intType()], [floatType(), intType(), stringType()]]
        ];
    }

    /**
     * @dataProvider sameTypeUnions
     * @return void
     */
    public function testTypeUnionSameObject(array $aTypes, array $bTypes) {
        $a = typeUnion(...$aTypes);
        $b = typeUnion(...$bTypes);
        $this->assertSame($a, $b);
    }

    /**
     * @return void
     */
    public function testTypeUnionName() {
        $typeUnion = typeUnion(stringType(), intType(), floatType());
        $this->assertSame('string|int|float', $typeUnion->name());
    }

    /**
     * @return void
     */
    public function testTypeIntersectThrowsExceptionWithDuplicateTypes() {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('The type, "' . $this::class . '", is already a part of the given type intersect and cannot be added again.');
        typeIntersect(objectType($this::class), objectType($this::class));
    }

    /**
     * @return void
     */
    public function testTypeIntersectGetTypes() {
        $typeIntersect = typeIntersect($a = objectType($this::class), $b = objectType(\ReflectionClass::class), $c = objectType('stdClass'));
        $this->assertSame([$a, $b, $c], $typeIntersect->types());
    }

    /**
     * @return void
     */
    public function testTypeIntersectGetName() {
        $typeIntersect = typeIntersect(objectType($this::class), objectType(\ReflectionClass::class), objectType('stdClass'));
        $this->assertSame($this::class . '&' . \ReflectionClass::class . '&stdClass', $typeIntersect->name());
    }

    public static function sameTypeIntersects() : array {
        return [
            [[objectType(self::class), objectType(Type::class)], [objectType(Type::class), objectType(self::class)]],
            [[objectType(Type::class), objectType(TypeIntersect::class), objectType(TypeUnion::class)], [objectType(TypeUnion::class), objectType(Type::class), objectType(TypeIntersect::class)]]
        ];
    }

    /**
     * @param array $aTypes
     * @param array $bTypes
     * @return void
     * @dataProvider sameTypeIntersects
     */
    public function testTypeIntersectSameObject(array $aTypes, array $bTypes) {
        $a = typeIntersect(...$aTypes);
        $b = typeIntersect(...$bTypes);
        $this->assertSame($a, $b);
    }

}