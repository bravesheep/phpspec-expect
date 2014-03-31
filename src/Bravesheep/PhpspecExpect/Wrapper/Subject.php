<?php

namespace Bravesheep\PhpspecExpect\Wrapper;

use PhpSpec\Wrapper\Subject as BaseSubject;
use PhpSpec\Wrapper\Subject\Expectation\DuringCall;

/**
 * Class Subject
 *
 * @method bool toBe(mixed $value)
 * @method bool toReturn(mixed $value)
 * @method bool toEqual(mixed $value)
 * @method bool toBeEqualTo(mixed $value)
 * @method bool toBeLike(mixed $value)
 * @method DuringCall toThrow(string $exception)
 * @method bool toHaveType(string $type)
 * @method bool toReturnAnInstanceOf(string $type)
 * @method bool toBeAnInstanceOf(string $type)
 * @method bool toImplement(string $type)
 * @method bool toHaveCount(int $count)
 * @method bool toBeString()
 * @method bool toBeArray()
 * @method bool toBeInteger()
 * @method bool toBeInt()
 * @method bool toBeBool()
 * @method bool toBeObject()
 * @method bool toBeResource()
 * @method bool toBeDouble()
 * @method bool toBeFloat()
 * @method bool toBeDecimal()
 * @method bool toBeBoolean()
 * @method bool toBeCallback()
 * @method bool toHaveKey(mixed $key)
 * @method bool toContain(mixed $value)
 * @method bool toStartWith(string $str)
 * @method bool toEndWith(string $str)
 * @method bool toMatch(string $regex)
 * @method bool notToBe(mixed $value)
 * @method bool notToReturn(mixed $value)
 * @method bool notToEqual(mixed $value)
 * @method bool notToBeEqualTo(mixed $value)
 * @method bool notToBeLike(mixed $value)
 * @method DuringCall notToThrow(string $exception)
 * @method bool notToHaveType(string $type)
 * @method bool notToReturnAnInstanceOf(string $type)
 * @method bool notToBeAnInstanceOf(string $type)
 * @method bool notToImplement(string $type)
 * @method bool notToHaveCount(int $count)
 * @method bool notToBeString()
 * @method bool notToBeArray()
 * @method bool notToBeInteger()
 * @method bool notToBeInt()
 * @method bool notToBeBool()
 * @method bool notToBeObject()
 * @method bool notToBeResource()
 * @method bool notToBeDouble()
 * @method bool notToBeFloat()
 * @method bool notToBeDecimal()
 * @method bool notToBeBoolean()
 * @method bool notToBeCallback()
 * @method bool notToHaveKey(mixed $key)
 * @method bool notToContain(mixed $value)
 * @method bool notToStartWith(string $str)
 * @method bool notToEndWith(string $str)
 * @method bool notToMatch(string $regex)
 */
class Subject extends BaseSubject
{
    public function __call($method, array $arguments = array())
    {
        if (preg_match('/^(to|notTo)(.+)$/', $method, $matches)) {
            if ('notTo' === $matches[1]) {
                $method = 'shouldNot' . $matches[2];
            } else {
                $method = 'should' . $matches[2];
            }
        }

        return parent::__call($method, $arguments);
    }
}
