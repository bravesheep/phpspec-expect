# phpspec expect
Adds the `expect` function for you to your code.

## Adding custom matcher globally
By calling `Bravesheep\PhpspecExpect\ContainerHolder::getInstance()->getContainer()` you can modify
the phpspec ServiceContainer instance. You can add extra matchers for all your tests from that point
on by adding an object starting with `matchers.matcher`, for example:

    $container = \Bravesheep\PhpspecExpect\ContainerHolder::getInstance()->getContainer();
    $container->setShared('matchers.matcher.my.custom.matcher', function (\PhpSpec\ServiceContainer $c) {
        return new My\Custom\Matcher($c->get('formatter.presenter'));
    });

Note that shared objects will only be created once, whereas those added with `$container->set()` will be
constructed again for every call made.
