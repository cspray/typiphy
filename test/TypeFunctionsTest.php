<?php declare(strict_types=1);

namespace Cspray\Typiphy;

use PHPUnit\Framework\TestCase;

class TypeFunctionsTest extends TestCase {

    public function fullyQualifiedNameProvider() : array {
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
     * @covers \Cspray\Typiphy\stringType
     * @covers \Cspray\Typiphy\intType
     * @covers \Cspray\Typiphy\floatType
     * @covers \Cspray\Typiphy\boolType
     * @covers \Cspray\Typiphy\arrayType
     * @covers \Cspray\Typiphy\mixedType
     * @covers \Cspray\Typiphy\iterableType
     * @covers \Cspray\Typiphy\nullType
     * @covers \Cspray\Typiphy\voidType
     * @covers \Cspray\Typiphy\callableType
     * @covers \Cspray\Typiphy\Internal\NamedType
     */
    public function testTypeFullyQualifiedName(string $name, callable $typeProvider) {
        $this->assertSame($name, $typeProvider()->getName());
    }

    /**
     * @dataProvider fullyQualifiedNameProvider
     * @covers \Cspray\Typiphy\stringType
     * @covers \Cspray\Typiphy\intType
     * @covers \Cspray\Typiphy\floatType
     * @covers \Cspray\Typiphy\boolType
     * @covers \Cspray\Typiphy\arrayType
     * @covers \Cspray\Typiphy\mixedType
     * @covers \Cspray\Typiphy\iterableType
     * @covers \Cspray\Typiphy\nullType
     * @covers \Cspray\Typiphy\voidType
     * @covers \Cspray\Typiphy\callableType
     * @covers \Cspray\Typiphy\Internal\NamedType
     */
    public function testTypeToString(string $name, callable $typeProvider) {
        $this->assertSame($name, (string) $typeProvider());
    }

    /**
     * @dataProvider fullyQualifiedNameProvider
     * @covers \Cspray\Typiphy\stringType
     * @covers \Cspray\Typiphy\intType
     * @covers \Cspray\Typiphy\floatType
     * @covers \Cspray\Typiphy\boolType
     * @covers \Cspray\Typiphy\arrayType
     * @covers \Cspray\Typiphy\mixedType
     * @covers \Cspray\Typiphy\iterableType
     * @covers \Cspray\Typiphy\nullType
     * @covers \Cspray\Typiphy\voidType
     * @covers \Cspray\Typiphy\callableType
     * @covers \Cspray\Typiphy\Internal\NamedType
     */
    public function testTypeSameObject(string $name, callable $typeProvider) {
        $this->assertSame($typeProvider(), $typeProvider());
    }

    /**
     * @return void
     * @covers ::\Cspray\Typiphy\objectType
     * @covers \Cspray\Typiphy\Internal\NamedObjectType
     */
    public function testObjectTypeGetFullyQualifiedName() {
        $this->assertSame($this::class, objectType($this::class)->getName());
    }

    /**
     * @return void
     * @covers ::\Cspray\Typiphy\objectType
     * @covers \Cspray\Typiphy\Internal\NamedObjectType
     */
    public function testObjectTypeToString() {
       $this->assertSame($this::class, (string) objectType($this::class));
    }

    /**
     * @return void
     * @covers ::\Cspray\Typiphy\objectType
     * @covers \Cspray\Typiphy\Internal\NamedObjectType
     */
    public function testObjectTypeAreSame() {
        $this->assertSame(objectType($this::class), objectType($this::class));
    }

    /**
     * @return void
     * @covers ::\Cspray\Typiphy\objectType
     * @covers \Cspray\Typiphy\Internal\NamedObjectType
     */
    public function testObjectTypeAreNotSame() {
        $this->assertNotSame(objectType($this::class), objectType(ObjectType::class));
    }

    /**
     * @return void
     * @covers ::\Cspray\Typiphy\objectType
     * @covers \Cspray\Typiphy\Internal\NamedObjectType
     */
    public function testObjectTypeInstanceOfObjectType() {
        $this->assertInstanceOf(ObjectType::class, objectType($this::class));
    }

    /**
     * @return void
     * @covers ::\Cspray\Typiphy\objectType
     * @covers \Cspray\Typiphy\Internal\NamedObjectType
     */
    public function testObjectTypeIsNotClassThrowsException() {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('The name for an ObjectType must be a valid, loadable class but "NotAClass" was given.');
        objectType('NotAClass');
    }

    public function duplicatedTypesProvider() : array {
        return [
            [[stringType(), stringType()], "string"],
            [[intType(), stringType(), intType()], "int"],
            [[objectType($this::class), arrayType(), intType(), objectType($this::class)], $this::class]
        ];
    }

    /**
     * @return void
     * @covers ::\Cspray\Typiphy\stringType
     * @covers ::\Cspray\Typiphy\intType
     * @covers ::\Cspray\Typiphy\objectType
     * @covers ::\Cspray\Typiphy\arrayType
     * @covers ::\Cspray\Typiphy\typeUnion
     * @covers \Cspray\Typiphy\Internal\NamedType
     * @covers \Cspray\Typiphy\Internal\NamedObjectType
     * @covers \Cspray\Typiphy\Internal\NamedTypeUnion
     * @dataProvider duplicatedTypesProvider
     */
    public function testTypeUnionThrowsExceptionWithDuplicateTypes(array $duplicatedTypes, string $duplicatedType) {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('The type, "' . $duplicatedType . '", is already a part of the given type union and cannot be added again.');
        typeUnion(...$duplicatedTypes);
    }

    /**
     * @return void
     * @covers \Cspray\Typiphy\Internal\NamedType
     * @covers \Cspray\Typiphy\Internal\NamedTypeUnion
     * @covers ::\Cspray\Typiphy\typeUnion
     * @covers ::\Cspray\Typiphy\stringType
     * @covers ::\Cspray\Typiphy\voidType
     */
    public function testTypeUnionThrowsExceptionIfVoidIncluded() {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('The "void" type can only be used alone and cannot be added to a type union.');
        typeUnion(stringType(), voidType());
    }

    /**
     * @return void
     * @covers \Cspray\Typiphy\Internal\NamedTypeUnion
     * @covers \Cspray\Typiphy\Internal\NamedType
     * @covers ::\Cspray\Typiphy\stringType
     * @covers ::\Cspray\Typiphy\floatType
     * @covers ::\Cspray\Typiphy\intType
     * @covers ::\Cspray\Typiphy\typeUnion
     */
    public function testTypeUnionTypes() {
        $typeUnion = typeUnion(stringType(), intType(), floatType());
        $this->assertSame([stringType(), intType(), floatType()], $typeUnion->getTypes());
    }

    public function sameTypeUnions() : array {
        return [
            [[stringType(), intType()], [stringType(), intType()]],
            [[stringType(), floatType(), intType()], [floatType(), intType(), stringType()]]
        ];
    }

    /**
     * @dataProvider sameTypeUnions
     * @return void
     * @covers \Cspray\Typiphy\Internal\NamedTypeUnion
     * @covers \Cspray\Typiphy\Internal\NamedType
     * @covers ::\Cspray\Typiphy\stringType
     * @covers ::\Cspray\Typiphy\intType
     * @covers ::\Cspray\Typiphy\typeUnion
     */
    public function testTypeUnionSameObject(array $aTypes, array $bTypes) {
        $a = typeUnion(...$aTypes);
        $b = typeUnion(...$bTypes);
        $this->assertSame($a, $b);
    }

    /**
     * @return void
     * @covers \Cspray\Typiphy\Internal\NamedTypeUnion
     * @covers \Cspray\Typiphy\Internal\NamedType
     * @covers ::\Cspray\Typiphy\stringType
     * @covers ::\Cspray\Typiphy\floatType
     * @covers ::\Cspray\Typiphy\intType
     * @covers ::\Cspray\Typiphy\typeUnion
     */
    public function testTypeUnionName() {
        $typeUnion = typeUnion(stringType(), intType(), floatType());
        $this->assertSame('string|int|float', $typeUnion->getName());
    }

    /**
     * @return void
     * @covers \Cspray\Typiphy\Internal\NamedTypeIntersection
     * @covers \Cspray\Typiphy\Internal\NamedObjectType
     * @covers ::\Cspray\Typiphy\objectType
     * @covers ::\Cspray\Typiphy\typeIntersect
     */
    public function testTypeIntersectThrowsExceptionWithDuplicateTypes() {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('The type, "' . $this::class . '", is already a part of the given type intersect and cannot be added again.');
        typeIntersect(objectType($this::class), objectType($this::class));
    }

    /**
     * @return void
     * @covers \Cspray\Typiphy\Internal\NamedTypeIntersection
     * @covers \Cspray\Typiphy\Internal\NamedObjectType
     * @covers ::\Cspray\Typiphy\objectType
     * @covers ::\Cspray\Typiphy\typeIntersect
     */
    public function testTypeIntersectGetTypes() {
        $typeIntersect = typeIntersect($a = objectType($this::class), $b = objectType(\ReflectionClass::class), $c = objectType('stdClass'));
        $this->assertSame([$a, $b, $c], $typeIntersect->getTypes());
    }

    /**
     * @return void
     * @covers \Cspray\Typiphy\Internal\NamedTypeIntersection
     * @covers \Cspray\Typiphy\Internal\NamedObjectType
     * @covers ::\Cspray\Typiphy\objectType
     * @covers ::\Cspray\Typiphy\typeIntersect
     */
    public function testTypeIntersectGetName() {
        $typeIntersect = typeIntersect(objectType($this::class), objectType(\ReflectionClass::class), objectType('stdClass'));
        $this->assertSame($this::class . '&' . \ReflectionClass::class . '&stdClass', $typeIntersect->getName());
    }

    public function sameTypeIntersects() : array {
        return [
            [[objectType($this::class), objectType(Type::class)], [objectType(Type::class), objectType($this::class)]],
            [[objectType(Type::class), objectType(TypeIntersect::class), objectType(TypeUnion::class)], [objectType(TypeUnion::class), objectType(Type::class), objectType(TypeIntersect::class)]]
        ];
    }

    /**
     * @param array $aTypes
     * @param array $bTypes
     * @return void
     * @dataProvider sameTypeIntersects
     * @covers \Cspray\Typiphy\Internal\NamedTypeIntersection
     * @covers \Cspray\Typiphy\Internal\NamedObjectType
     * @covers ::\Cspray\Typiphy\objectType
     * @covers ::\Cspray\Typiphy\typeIntersect
     */
    public function testTypeIntersectSameObject(array $aTypes, array $bTypes) {
        $a = typeIntersect(...$aTypes);
        $b = typeIntersect(...$bTypes);
        $this->assertSame($a, $b);
    }

}