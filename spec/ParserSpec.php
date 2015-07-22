<?php

namespace spec\GraphQL;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ParserSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('GraphQL\Parser');
    }

    function it_is_a_test()
    {
        $this->parse('{ field }');
    }
}
