<?php

use Bravesheep\PhpspecExpect\ContainerHolder;
use Bravesheep\PhpspecExpect\Wrapper\Subject;
use Bravesheep\PhpspecExpect\Wrapper\Wrapper;
use PhpSpec\Formatter\Presenter\TaggedPresenter;
use PhpSpec\Loader\Node\ExampleNode;
use PhpSpec\Matcher\CallbackMatcher;
use PhpSpec\Matcher\MatcherInterface;
use PhpSpec\Matcher\MatchersProviderInterface;
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

        // add custom matchers from the class in which the call to expect() took place
        $trace = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT | DEBUG_BACKTRACE_IGNORE_ARGS, 2);
        if (isset($trace[1], $trace[1]['object'])) {
            $object = $trace[1]['object'];
            if ($object instanceof MatchersProviderInterface) {
                $customMatchers = $object->getMatchers();
                if (!is_array($customMatchers)) {
                    throw new \RuntimeException(
                        'PhpSpec\Matcher\MatchersProviderInterface::getMatchers() must return an array of matchers.'
                    );
                }

                foreach ($customMatchers as $name => $matcher) {
                    if ($matcher instanceof MatcherInterface) {
                        $matchers->add($matcher);
                    } elseif (is_callable($matcher)) {
                        $matchers->add(new CallbackMatcher($name, $matcher, $presenter));
                    } else {
                        throw new \RuntimeException(
                            'Custom matcher has to implement "PhpSpec\Matcher\MatcherInterface" or be a callable'
                        );
                    }
                }
            }
        }

        $subjectFactory = new Wrapper($matchers, $presenter, $dispatcher, $example);
        return $subjectFactory->wrap($value);
    }
}
