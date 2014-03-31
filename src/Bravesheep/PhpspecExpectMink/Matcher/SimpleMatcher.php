<?php

namespace Bravesheep\PhpspecExpectMink\Matcher;

use PhpSpec\Formatter\Presenter\PresenterInterface;
use PhpSpec\Matcher\BasicMatcher;

abstract class SimpleMatcher extends BasicMatcher
{
    /**
     * @var PresenterInterface
     */
    protected $presenter;

    /**
     * @param PresenterInterface $presenter
     */
    public function __construct(PresenterInterface $presenter)
    {
        $this->presenter = $presenter;
    }

    /**
     * Return the supported names.
     * @return string[]
     */
    abstract protected function getNames();

    /**
     * Return the number of arguments required at a minimum
     * @return int
     */
    abstract protected function getRequiredArgumentCount();

    /**
     * @param mixed $subject
     * @return bool
     */
    protected function supportsSubject($subject)
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function supports($name, $subject, array $arguments)
    {
        return $this->supportsSubject($subject) && count($arguments) >= $this->getRequiredArgumentCount() &&
            in_array($name, $this->getNames());
    }
}
