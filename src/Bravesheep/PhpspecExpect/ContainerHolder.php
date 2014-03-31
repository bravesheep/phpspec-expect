<?php

namespace Bravesheep\PhpspecExpect;

use PhpSpec\Formatter\Presenter\Differ\ArrayEngine;
use PhpSpec\Formatter\Presenter\Differ\Differ;
use PhpSpec\Formatter\Presenter\Differ\StringEngine;
use PhpSpec\Formatter\Presenter\TaggedPresenter;
use PhpSpec\Matcher;
use PhpSpec\Runner\Maintainer\MatchersMaintainer;
use PhpSpec\Runner\Maintainer\SubjectMaintainer;
use PhpSpec\Runner\MatcherManager;
use PhpSpec\ServiceContainer;
use PhpSpec\Wrapper\Unwrapper;
use Symfony\Component\EventDispatcher\EventDispatcher;

class ContainerHolder
{
    /**
     * @var ContainerHolder
     */
    private static $instance = null;

    /**
     * @return ContainerHolder
     */
    public static function getInstance()
    {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * @var ServiceContainer
     */
    private $container;

    public function __construct()
    {
        $this->container = $this->createContainer();
    }

    /**
     * @return ServiceContainer
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * @return ServiceContainer
     */
    public function createContainer()
    {
        $container = new ServiceContainer();

        $this->setupEventDispatcher($container);
        $this->setupPresenter($container);
        $this->setupRunner($container);
        $this->setupMatchers($container);

        return $container;
    }

    private function setupEventDispatcher(ServiceContainer $container)
    {
        $container->setShared('event_dispatcher', function (ServiceContainer $c) {
            $dispatcher = new EventDispatcher();

            array_map(
                array($dispatcher, 'addSubscriber'),
                $c->getByPrefix('event_dispatcher.listeners')
            );

            return $dispatcher;
        });
    }

    private function setupPresenter(ServiceContainer $container)
    {
        $container->setShared('formatter.presenter', function (ServiceContainer $c) {
            return new TaggedPresenter($c->get('formatter.presenter.differ'));
        });

        $container->setShared('formatter.presenter.differ', function (ServiceContainer $c) {
            $differ = new Differ();

            array_map(
                array($differ, 'addEngine'),
                $c->getByPrefix('formatter.presenter.differ.engines')
            );

            return $differ;
        });

        $container->set('formatter.presenter.differ.engines.string', function (ServiceContainer $c) {
            return new StringEngine();
        });

        $container->set('formatter.presenter.differ.engines.array', function (ServiceContainer $c) {
            return new ArrayEngine();
        });
    }

    private function setupRunner(ServiceContainer $container)
    {
        $container->set('runner.maintainers.matchers', function (ServiceContainer $c) {
            return new MatchersMaintainer(
                $c->get('formatter.presenter'),
                $c->get('unwrapper')
            );
        });

        $container->set('runner.maintainers.subject', function (ServiceContainer $c) {
            return new SubjectMaintainer(
                $c->get('formatter.presenter'),
                $c->get('unwrapper'),
                $c->get('event_dispatcher')
            );
        });

        $container->setShared('unwrapper', function (ServiceContainer $c) {
            return new Unwrapper();
        });
    }

    private function setupMatchers(ServiceContainer $container)
    {
        $container->set('matcher_manager', function (ServiceContainer $c) {
            $matchers = new MatcherManager($c->get('formatter.presenter'));

            array_map(
                array($matchers, 'add'),
                $c->getByPrefix('matchers.matcher')
            );
            return $matchers;
        });

        $container->setShared('matchers.matcher.identity', function (ServiceContainer $c) {
            return new Matcher\IdentityMatcher($c->get('formatter.presenter'));
        });

        $container->setShared('matchers.matcher.comparison', function (ServiceContainer $c) {
            return new Matcher\ComparisonMatcher($c->get('formatter.presenter'));
        });

        $container->setShared('matchers.matcher.throw', function (ServiceContainer $c) {
            return new Matcher\ThrowMatcher($c->get('unwrapper'), $c->get('formatter.presenter'));
        });

        $container->setShared('matchers.matcher.type', function (ServiceContainer $c) {
            return new Matcher\TypeMatcher($c->get('formatter.presenter'));
        });

        $container->setShared('matchers.matcher.object_state', function (ServiceContainer $c) {
            return new Matcher\ObjectStateMatcher($c->get('formatter.presenter'));
        });

        $container->setShared('matchers.matcher.scalar', function (ServiceContainer $c) {
            return new Matcher\ScalarMatcher($c->get('formatter.presenter'));
        });

        $container->setShared('matchers.matcher.array_count', function (ServiceContainer $c) {
            return new Matcher\ArrayCountMatcher($c->get('formatter.presenter'));
        });

        $container->setShared('matchers.matcher.array_key', function (ServiceContainer $c) {
            return new Matcher\ArrayKeyMatcher($c->get('formatter.presenter'));
        });

        $container->setShared('matchers.matcher.array_contain', function (ServiceContainer $c) {
            return new Matcher\ArrayContainMatcher($c->get('formatter.presenter'));
        });

        $container->setShared('matchers.matcher.string_start', function (ServiceContainer $c) {
            return new Matcher\StringStartMatcher($c->get('formatter.presenter'));
        });

        $container->setShared('matchers.matcher.string_end', function (ServiceContainer $c) {
            return new Matcher\StringEndMatcher($c->get('formatter.presenter'));
        });

        $container->setShared('matchers.matcher.string_regex', function (ServiceContainer $c) {
            return new Matcher\StringRegexMatcher($c->get('formatter.presenter'));
        });
    }
}
