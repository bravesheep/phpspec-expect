<?php

namespace Bravesheep\PhpspecExpectMink\Matcher;

use Behat\Mink\Session;
use PhpSpec\Exception\Example\FailureException;

class MinkUrlMatcher extends SimpleMatcher
{
    /**
     * {@inheritdoc}
     */
    protected function matches($subject, array $arguments)
    {
        /** @var Session $session */
        $session = $subject;
        $expectedAddress = $this->cleanPath($arguments[0]);
        $currentAddress = $this->cleanPath($session->getCurrentUrl());

        return $expectedAddress === $currentAddress;
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
            'Expected %s to be %s, but it is not.',
            $this->presenter->presentString($this->cleanPath($subject->getCurrentUrl())),
            $this->presenter->presentString($this->cleanPath($arguments[0]))
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
            'Expected %s not to be %s, but it is.',
            $this->presenter->presentString($this->cleanPath($subject->getCurrentUrl())),
            $this->presenter->presentString($this->cleanPath($arguments[0]))
        ));
    }

    /**
     * {@inheritdoc}
     */
    protected function getNames()
    {
        return [
            'haveAddress',
            'beAtAddress',
            'haveUrl',
            'beAtUrl',
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

    /**
     * Retrieves path from url and trims scriptname from the URL.
     * @param string $path
     * @return string
     */
    protected function cleanPath($path)
    {
        return parse_url(preg_replace('/^\/[^\.\/]+\.php/', '', $path), PHP_URL_PATH);
    }
}
