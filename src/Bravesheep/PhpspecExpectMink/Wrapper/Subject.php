<?php

namespace Bravesheep\PhpspecExpectMink\Wrapper;

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
 * @method bool toHaveKey(mixed $key)
 * @method bool toContain(mixed $value)
 * @method bool toStartWith(string $str)
 * @method bool toEndWith(string $str)
 * @method bool toMatch(string $regex)
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
