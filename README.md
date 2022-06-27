Ruler
=====

Ruler is a simple stateless production rules engine for PHP 5.3+.

[![Package version](http://img.shields.io/packagist/v/ruler/ruler.svg?style=flat-square)](https://packagist.org/packages/ruler/ruler)
[![Build status](https://img.shields.io/github/workflow/status/bobthecow/Ruler/Unit%20Tests/main.svg?style=flat-square)](https://github.com/bobthecow/Ruler/actions?query=branch:main)
[![StyleCI](https://styleci.io/repos/1906921/shield)](https://styleci.io/repos/1906921)

Ruler has an easy, straightforward DSL
--------------------------------------

... provided by the RuleBuilder:

```php
$rb = new RuleBuilder;
$rule = $rb->create(
    $rb->logicalAnd(
        $rb['minNumPeople']->lessThanOrEqualTo($rb['actualNumPeople']),
        $rb['maxNumPeople']->greaterThanOrEqualTo($rb['actualNumPeople'])
    ),
    function() {
        echo 'YAY!';
    }
);

$context = new Context([
    'minNumPeople' => 5,
    'maxNumPeople' => 25,
    'actualNumPeople' => fn() => 6,
]);

$rule->execute($context); // "Yay!"
```


### Of course, if you're not into the whole brevity thing

... you can use it without a RuleBuilder:

```php
$actualNumPeople = new Variable('actualNumPeople');
$rule = new Rule(
    new Operator\LogicalAnd([
        new Operator\LessThanOrEqualTo(new Variable('minNumPeople'), $actualNumPeople),
        new Operator\GreaterThanOrEqualTo(new Variable('maxNumPeople'), $actualNumPeople)
    ]),
    function() {
        echo 'YAY!';
    }
);

$context = new Context([
    'minNumPeople' => 5,
    'maxNumPeople' => 25,
    'actualNumPeople' => fn() => 6,
]);

$rule->execute($context); // "Yay!"
```

But that doesn't sound too fun, does it?


Things you can do with your Ruler
---------------------------------

### Compare things

```php
// These are Variables. They'll be replaced by terminal values during Rule evaluation.

$a = $rb['a'];
$b = $rb['b'];

// Here are bunch of Propositions. They're not too useful by themselves, but they
// are the building blocks of Rules, so you'll need 'em in a bit.

$a->greaterThan($b);                      // true if $a > $b
$a->greaterThanOrEqualTo($b);             // true if $a >= $b
$a->lessThan($b);                         // true if $a < $b
$a->lessThanOrEqualTo($b);                // true if $a <= $b
$a->equalTo($b);                          // true if $a == $b
$a->notEqualTo($b);                       // true if $a != $b
$a->stringContains($b);                   // true if strpos($b, $a) !== false
$a->stringDoesNotContain($b);             // true if strpos($b, $a) === false
$a->stringContainsInsensitive($b);        // true if stripos($b, $a) !== false
$a->stringDoesNotContainInsensitive($b);  // true if stripos($b, $a) === false
$a->startsWith($b);                       // true if strpos($b, $a) === 0
$a->startsWithInsensitive($b);            // true if stripos($b, $a) === 0
$a->endsWith($b);                         // true if strpos($b, $a) === len($a) - len($b)
$a->endsWithInsensitive($b);              // true if stripos($b, $a) === len($a) - len($b)
$a->sameAs($b);                           // true if $a === $b
$a->notSameAs($b);                        // true if $a !== $b
```


### Math even more things

```php
$c = $rb['c'];
$d = $rb['d'];

// Mathematical operators are a bit different. They're not Propositions, so
// they don't belong in rules all by themselves, but they can be combined
// with Propositions for great justice.

$rb['price']
  ->add($rb['shipping'])
  ->greaterThanOrEqualTo(50)

// Of course, there are more.

$c->add($d);          // $c + $d
$c->subtract($d);     // $c - $d
$c->multiply($d);     // $c * $d
$c->divide($d);       // $c / $d
$c->modulo($d);       // $c % $d
$c->exponentiate($d); // $c ** $d
$c->negate();         // -$c
$c->ceil();           // ceil($c)
$c->floor();          // floor($c)
```


### Reason about sets

```php
$e = $rb['e']; // These should both be arrays
$f = $rb['f'];

// Manipulate sets with set operators

$e->union($f);
$e->intersect($f);
$e->complement($f);
$e->symmetricDifference($f);
$e->min();
$e->max();

// And use set Propositions to include them in Rules.

$e->containsSubset($f);
$e->doesNotContainSubset($f);
$e->setContains($a);
$e->setDoesNotContain($a);
```


### Combine Rules

```php
// Create a Rule with an $a == $b condition
$aEqualsB = $rb->create($a->equalTo($b));

// Create another Rule with an $a != $b condition
$aDoesNotEqualB = $rb->create($a->notEqualTo($b));

// Now combine them for a tautology!
// (Because Rules are also Propositions, they can be combined to make MEGARULES)
$eitherOne = $rb->create($rb->logicalOr($aEqualsB, $aDoesNotEqualB));

// Just to mix things up, we'll populate our evaluation context with completely
// random values...
$context = new Context([
    'a' => rand(),
    'b' => rand(),
]);

// Hint: this is always true!
$eitherOne->evaluate($context);
```


### Combine more Rules

```php
$rb->logicalNot($aEqualsB);                  // The same as $aDoesNotEqualB :)
$rb->logicalAnd($aEqualsB, $aDoesNotEqualB); // True if both conditions are true
$rb->logicalOr($aEqualsB, $aDoesNotEqualB);  // True if either condition is true
$rb->logicalXor($aEqualsB, $aDoesNotEqualB); // True if only one condition is true
```


### `evaluate` and `execute` Rules

`evaluate()` a Rule with Context to figure out whether it is true.

```php
$context = new Context([
    'userName' => fn() => $_SESSION['userName'] ?? null,
]);

$userIsLoggedIn = $rb->create($rb['userName']->notEqualTo(null));

if ($userIsLoggedIn->evaluate($context)) {
    // Do something special for logged in users!
}
```

If a Rule has an action, you can `execute()` it directly and save yourself a
couple of lines of code.

```php
$hiJustin = $rb->create(
    $rb['userName']->equalTo('bobthecow'),
    function() {
        echo "Hi, Justin!";
    }
);

$hiJustin->execute($context);  // "Hi, Justin!"
```


### Even `execute` a whole grip of Rules at once

```php
$hiJon = $rb->create(
    $rb['userName']->equalTo('jwage'),
    function() {
        echo "Hey there Jon!";
    }
);

$hiEveryoneElse = $rb->create(
    $rb->logicalAnd(
        $rb->logicalNot($rb->logicalOr($hiJustin, $hiJon)), // The user is neither Justin nor Jon
        $userIsLoggedIn                                     // ... but a user nonetheless
    ),
    function() use ($context) {
        echo sprintf("Hello, %s", $context['userName']);
    }
);

$rules = new RuleSet([$hiJustin, $hiJon, $hiEveryoneElse]);

// Let's add one more Rule, so non-authenticated users have a chance to log in
$redirectForAuthentication = $rb->create($rb->logicalNot($userIsLoggedIn), function() {
    header('Location: /login');
    exit;
});

$rules->addRule($redirectForAuthentication);

// Now execute() all true Rules.
//
// Astute readers will note that the Rules we defined are mutually exclusive, so
// at most one of them will evaluate to true and execute an action...
$rules->executeRules($context);
```


Dynamically populate your evaluation Context
--------------------------------------------

Several of our examples above use static values for the context Variables. While
that's good for examples, it's not as useful in the Real World. You'll probably
want to evaluate Rules based on all sorts of things...

You can think of the Context as a ViewModel for Rule evaluation. You provide the
static values, or even code for lazily evaluating the Variables needed by your
Rules.

```php
$context = new Context;

// Some static values...
$context['reallyAnnoyingUsers'] = ['bobthecow', 'jwage'];

// You'll remember this one from before
$context['userName'] = fn() => $_SESSION['userName'] ?? null;

// Let's pretend you have an EntityManager named `$em`...
$context['user'] = function() use ($em, $context) {
    if ($userName = $context['userName']) {
        return $em->getRepository('Users')->findByUserName($userName);
    }
};

$context['orderCount'] = function() use ($em, $context) {
    if ($user = $context['user']) {
        return $em->getRepository('Orders')->findByUser($user)->count();
    }

    return 0;
};
```

Now you have all the information you need to make Rules based on Order count or
the current User, or any number of other crazy things. I dunno, maybe this is
for a shipping price calculator?

> If the current User has placed 5 or more orders, but isn't "really annoying",
> give 'em free shipping.

```php
$rb->create(
    $rb->logicalAnd(
        $rb['orderCount']->greaterThanOrEqualTo(5),
        $rb['reallyAnnoyingUsers']->doesNotContain($rb['userName'])
    ),
    function() use ($shipManager, $context) {
        $shipManager->giveFreeShippingTo($context['user']);
    }
);
```


Access variable properties
--------------------------

As an added bonus, Ruler lets you access properties, methods and offsets on your
Context Variable values. This can come in really handy.

Say we wanted to log the current user's name if they are an administrator:

```php
// Reusing our $context from the last example...

// We'll define a few context variables for determining what roles a user has,
// and their full name:

$context['userRoles'] = function() use ($em, $context) {
    if ($user = $context['user']) {
        return $user->roles();
    } else {
        // return a default "anonymous" role if there is no current user
        return ['anonymous'];
    }
};

$context['userFullName'] = function() use ($em, $context) {
    if ($user = $context['user']) {
        return $user->fullName;
    }
};


// Now we'll create a rule to write the log message

$rb->create(
    $rb->logicalAnd(
        $userIsLoggedIn,
        $rb['userRoles']->contains('admin')
    ),
    function() use ($context, $logger) {
        $logger->info(sprintf("Admin user %s did a thing!", $context['userFullName']));
    }
);
```

That was a bit of a mouthful. Instead of creating context Variables for
everything we might need to access in a rule, we can use VariableProperties, and
their convenient RuleBuilder interface:

```php
// We can skip over the Context Variable building above. We'll simply set our,
// default roles on the VariableProperty itself, then go right to writing rules:

$rb['user']['roles'] = ['anonymous'];

$rb->create(
    $rb->logicalAnd(
        $userIsLoggedIn,
        $rb['user']['roles']->contains('admin')
    ),
    function() use ($context, $logger) {
        $logger->info(sprintf("Admin user %s did a thing!", $context['user']['fullName']);
    }
);
```

If the parent Variable resolves to an object, and this VariableProperty name is
"bar", it will do a prioritized lookup for:

  1. A method named `bar`
  2. A public property named `bar`
  3. ArrayAccess + offsetExists named `bar`

If the Variable resolves to an array it will return:

  1. Array index `bar`

If none of the above are true, it will return the default value for this
VariableProperty.


Add your own Operators
----------------------

If none of the default Ruler Operators fit your needs, you can write your own! Just define
additional operators like this:

```php

namespace My\Ruler\Operators;

use Ruler\Context;
use Ruler\Operator\VariableOperator;
use Ruler\Proposition;
use Ruler\Value;

class ALotGreaterThan extends VariableOperator implements Proposition
{
    public function evaluate(Context $context): bool
    {
        list($left, $right) = $this->getOperands();
        $value = $right->prepareValue($context)->getValue() * 10;

        return $left->prepareValue($context)->greaterThan(new Value($value));
    }

    protected function getOperandCardinality()
    {
        return static::BINARY;
    }
}
```

Then you can use them with RuleBuilder like this:

```php
$rb->registerOperatorNamespace('My\Ruler\Operators');
$rb->create(
    $rb['a']->aLotGreaterThan(10);
);
```


But that's not all...
---------------------

Check out [the test suite](https://github.com/bobthecow/Ruler/blob/master/tests/Ruler/Test/Functional/RulerTest.php)
for more examples (and some hot CS 320 combinatorial logic action).


Ruler is plumbing. Bring your own porcelain.
============================================

Ruler doesn't bother itself with where Rules come from. Maybe you have a RuleManager
wrapped around an ORM or ODM. Perhaps you write a simple DSL and parse static files.

Whatever your flavor, Ruler will handle the logic.
