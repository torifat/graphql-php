<?php

namespace spec\GraphQL;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class LexerSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('GraphQL\Lexer');
    }

    function it_should_be_able_to_parse_a_baisc_query()
    {
        $query = "
          query HeroNameQuery {
            hero {
              name
            }
          }
        ";
        $this->setInput($query);
        $this->lookahead->shouldBe(null);
        $this->moveNext();
        $this->lookahead['value']->shouldBe('query');
        $this->moveNext();
        $this->lookahead['value']->shouldBe('HeroNameQuery');
        $this->moveNext();
        $this->lookahead['value']->shouldBe('{');
        $this->moveNext();
        $this->lookahead['value']->shouldBe('hero');
        $this->moveNext();
        $this->lookahead['value']->shouldBe('{');
        $this->moveNext();
        $this->lookahead['value']->shouldBe('name');
        $this->moveNext();
        $this->lookahead['value']->shouldBe('}');
        $this->moveNext();
        $this->lookahead['value']->shouldBe('}');
    }
}
