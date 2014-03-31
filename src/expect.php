<?php

use Bravesheep\PhpspecExpectMink\ContainerHolder;
use Bravesheep\PhpspecExpectMink\Wrapper\Subject;
use Bravesheep\PhpspecExpectMink\Wrapper\Wrapper;
use PhpSpec\Formatter\Presenter\TaggedPresenter;
use PhpSpec\Loader\Node\ExampleNode;
use PhpSpec\Runner\MatcherManager;
use Symfony\Component\EventDispatcher\EventDispatcher;

if (!function_exists('expect')) {
    /**
     * @param mixed $value
     * @return Subject
     */
    function expect($value = null)
    {
        $example = new ExampleNode('expect', new \ReflectionFunction(__FUNCTION__));

        $container = ContainerHolder::getInstance()->getContainer();

        /** @var TaggedPresenter $presenter */
        $presenter = $container->get('formatter.presenter');

        /** @var MatcherManager $matchers */
        $matchers = $container->get('matcher_manager');

        /** @var EventDispatcher $dispatcher */
        $dispatcher = $container->get('event_dispatcher');

        $subjectFactory = new Wrapper($matchers, $presenter, $dispatcher, $example);
        return $subjectFactory->wrap($value);
    }
}
