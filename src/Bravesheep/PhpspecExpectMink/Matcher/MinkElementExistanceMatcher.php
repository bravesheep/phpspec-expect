<?php

namespace Bravesheep\PhpspecExpectMink\Matcher;

use Behat\Mink\Element\ElementInterface;
use Behat\Mink\Session;
use PhpSpec\Exception\Example\FailureException;

class MinkElementExistanceMatcher extends SimpleMatcher
{
    /**
     * {@inheritdoc}
     */
    protected function matches($subject, array $arguments)
    {
        /** @var ElementInterface $element */
        $element = $subject;
        return null !== $element->find($arguments[0], $arguments[1]);
    }

    /**
     * @param string $name
     * @param Session  $subject
     * @param array  $arguments
     *
     * @return FailureException
     */
    protected function getFailureException($name, $subject, array $arguments)
    {
        return new FailureException(sprintf(
            'Expected element to match %s selector %s, but a match was not found.',
            $this->presenter->presentString($arguments[0]),
            $this->presenter->presentValue($arguments[1])
        ));
    }

    /**
     * @param string $name
     * @param Session  $subject
     * @param array  $arguments
     *
     * @return FailureException
     */
    protected function getNegativeFailureException($name, $subject, array $arguments)
    {
        return new FailureException(sprintf(
            'Expected element not to match %s selector %s, but a match was found.',
            $this->presenter->presentString($arguments[0]),
            $this->presenter->presentValue($arguments[1])
        ));
    }

    /**
     * {@inheritdoc}
     */
    protected function getNames()
    {
        return [
            'matchElement',
            'haveMatchingElement',
            'haveElement',
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function getRequiredArgumentCount()
    {
        return 2;
    }

    /**
     * {@inheritdoc}
     */
    protected function supportsSubject($subject)
    {
        return $subject instanceof ElementInterface;
    }
}
