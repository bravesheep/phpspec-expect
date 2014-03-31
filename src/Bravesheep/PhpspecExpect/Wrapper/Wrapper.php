<?php

namespace Bravesheep\PhpspecExpect\Wrapper;

use PhpSpec\Exception\ExceptionFactory;
use PhpSpec\Formatter\Presenter\PresenterInterface;
use PhpSpec\Loader\Node\ExampleNode;
use PhpSpec\Runner\MatcherManager;
use PhpSpec\Wrapper\Subject\Caller;
use PhpSpec\Wrapper\Subject\ExpectationFactory;
use PhpSpec\Wrapper\Subject\SubjectWithArrayAccess;
use PhpSpec\Wrapper\Subject\WrappedObject;
use PhpSpec\Wrapper\Wrapper as BaseWrapper;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class Wrapper extends BaseWrapper
{
    /**
     * @var \PhpSpec\Runner\MatcherManager
     */
    private $matchers;
    /**
     * @var \PhpSpec\Formatter\Presenter\PresenterInterface
     */
    private $presenter;
    /**
     * @var \Symfony\Component\EventDispatcher\EventDispatcherInterface
     */
    private $dispatcher;
    /**
     * @var \PhpSpec\Loader\Node\ExampleNode
     */
    private $example;

    /**
     * @param MatcherManager           $matchers
     * @param PresenterInterface       $presenter
     * @param EventDispatcherInterface $dispatcher
     * @param ExampleNode              $example
     */
    public function __construct(MatcherManager $matchers, PresenterInterface $presenter,
                                EventDispatcherInterface $dispatcher, ExampleNode $example)
    {
        parent::__construct($matchers, $presenter, $dispatcher, $example);
        $this->matchers = $matchers;
        $this->presenter = $presenter;
        $this->dispatcher = $dispatcher;
        $this->example = $example;
    }

    /**
     * @param object $value
     *
     * @return Subject
     */
    public function wrap($value = null)
    {
        $exceptionFactory   = new ExceptionFactory($this->presenter);
        $wrappedObject      = new WrappedObject($value, $this->presenter);
        $caller             = new Caller($wrappedObject, $this->example, $this->dispatcher, $exceptionFactory, $this);
        $arrayAccess        = new SubjectWithArrayAccess($caller, $this->presenter, $this->dispatcher);
        $expectationFactory = new ExpectationFactory($this->example, $this->dispatcher, $this->matchers);

        return new Subject(
            $value, $this, $wrappedObject, $caller, $arrayAccess, $expectationFactory
        );
    }
}
