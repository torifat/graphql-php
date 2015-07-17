<?php

namespace spec\GraphQL;

use GraphQL\Lexer;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class LexerSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('GraphQL\Lexer');
    }

    function it_skips_whitespace()
    {
        $this->setInput(<<<EOL

        foo

EOL
        );
        $token = $this->glimpse();
        $token->shouldHaveKeyWithValue('type', Lexer::NAME);
        $token->shouldHaveKeyWithValue('value', 'foo');
        $token->shouldHaveKeyWithValue('position', 9);

        $this->setInput(<<<EOL
        #comment
        foo#comment
EOL
        );
        $token = $this->glimpse();
        $token->shouldHaveKeyWithValue('type', Lexer::NAME);
        $token->shouldHaveKeyWithValue('value', 'foo');
        $token->shouldHaveKeyWithValue('position', 25);

        $this->setInput(",,,foo,,,");
        $token = $this->glimpse();
        $token->shouldHaveKeyWithValue('type', Lexer::NAME);
        $token->shouldHaveKeyWithValue('value', 'foo');
        $token->shouldHaveKeyWithValue('position', 3);
    }

    function it_lexes_strings()
    {
        $this->setInput('"simple"');
        $token = $this->glimpse();
        $token->shouldHaveKeyWithValue('type', Lexer::STRING);
        $token->shouldHaveKeyWithValue('value', 'simple');
        $token->shouldHaveKeyWithValue('position', 0);
    }
}
