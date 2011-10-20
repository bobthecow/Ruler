Ruler
=====

Ruler is a simple stateless production rules engine for PHP 5.3.

Ruler uses a pretty straightforward DSL provided by the RuleBuilder:

``` php
<?php

$rb = new RuleBuilder();
$rule = $rb->create(
    $rb->logicalOr(
        $rb['minNumPeople']->lessThanOrEqualTo($rb['actualNumPeople']),
        $rb['maxNumPeople']->greaterThanOrEqualTo($rb['actualNumPeople'])
    ),
    function() {
        echo 'YAY!';
    }
);

$context = new Context(array(
    'minNumPeople' => 5,
    'maxNumPeople' => 25,
    'actualNumPeople' => function() {
        return 6;
    },
));

$rule->execute($context);

```

Of course, if you're not into the whole brevity thing, you can use it without a RuleBuilder:

``` php
<?php

$actualNumPeople = new Variable('actualNumPeople');
$rule = new Rule(
    new Operator\LogicalAnd(array(
        new Operator\LessThanOrEqualTo(new Variable('minNumPeople'), $actualNumPeople),
        new Operator\GreaterThanOrEqualTo(new Variable('maxNumPeople'), $actualNumPeople)
    )),
    function() {
        echo 'YAY!';
    }
);

$context = new Context(array(
    'minNumPeople' => 5,
    'maxNumPeople' => 25,
    'actualNumPeople' => function() {
        return 6;
    },
));

$rule->execute($context)
```
