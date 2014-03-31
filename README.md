# phpspec expect
Adds the `expect(value)` function for you to your code, allowing you the same style of assertions that
[phpspec][phpspec] uses. Note that phpspec uses `should[matcher]` and `shouldNot[matcher]`, where this function also
allows `to[matcher]` and `notTo[matcher]` calls. For more information of matchers take a look at the
[phpspec documentation on matchers][phpspec_matchers].

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

## MatchersProviderInterface
If the object in which a call to `expect(value)` is made implements the `PhpSpec\Matcher\MatchersProviderInterface`
then the matchers provided by that object are included in the available matchers.

[phpspec]: http://phpspec.net/
[phpspec_matchers]: http://phpspec.net/cookbook/matchers.html
