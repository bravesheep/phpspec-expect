<?php

namespace Bravesheep\PhpspecExpectMink\Matcher;

use Behat\Mink\Session;
use PhpSpec\Exception\Example\FailureException;

class MinkCookieExistanceMatcher extends SimpleMatcher
{
    /**
     * {@inheritdoc}
     */
    protected function matches($subject, array $arguments)
    {
        /** @var Session $session */
        $session = $subject;
        return null !== $session->getCookie($arguments[0]);
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
            'Expected to have cookie %s, but one was not found.',
            $this->presenter->presentString($arguments[0])
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
            'Expected not to have cookie %s, but one was found.',
            $this->presenter->presentString($arguments[0])
        ));
    }

    /**
     * {@inheritdoc}
     */
    protected function getNames()
    {
        return [
            'haveCookie',
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function getRequiredArgumentCount()
    {
        return 1;
    }

    /**
     * {@inheritdoc}
     */
    protected function supportsSubject($subject)
    {
        return $subject instanceof Session;
    }
}
