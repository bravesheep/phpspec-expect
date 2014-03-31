<?php

namespace Bravesheep\PhpspecExpectMink\Matcher;

use Behat\Mink\Session;
use PhpSpec\Exception\Example\FailureException;

class MinkRegexUrlMatcher extends MinkUrlMatcher
{
    /**
     * {@inheritdoc}
     */
    protected function matches($subject, array $arguments)
    {
        /** @var Session $session */
        $session = $subject;
        $regex = $arguments[0];
        $currentAddress = $this->cleanPath($session->getCurrentUrl());
        return 1 === preg_match($regex, $currentAddress);
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
            'Expected %s to match %s, but it does not.',
            $this->presenter->presentString($this->cleanPath($subject->getCurrentUrl())),
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
            'Expected %s not to match %s, but it does.',
            $this->presenter->presentString($this->cleanPath($subject->getCurrentUrl())),
            $this->presenter->presentString($arguments[0])
        ));
    }

    protected function getNames()
    {
        return [
            'matchAddress',
            'matchUrl',
        ];
    }
}
