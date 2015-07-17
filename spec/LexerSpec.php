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

        $this->setInput('" white space "');
        $token = $this->glimpse();
        $token->shouldHaveKeyWithValue('type', Lexer::STRING);
        $token->shouldHaveKeyWithValue('value', ' white space ');
        $token->shouldHaveKeyWithValue('position', 0);

        $this->setInput('"quote \\""');
        $token = $this->glimpse();
        $token->shouldHaveKeyWithValue('type', Lexer::STRING);
        $token->shouldHaveKeyWithValue('value', 'quote "');
        $token->shouldHaveKeyWithValue('position', 0);

        $this->setInput('"escaped \\n\\r\\b\\t\\f"');
        $token = $this->glimpse();
        $token->shouldHaveKeyWithValue('type', Lexer::STRING);
        $token->shouldHaveKeyWithValue('value', 'escaped \n\r\b\t\f');
        $token->shouldHaveKeyWithValue('position', 0);

        $this->setInput('"slashes \\\\ \\/"');
        $token = $this->glimpse();
        $token->shouldHaveKeyWithValue('type', Lexer::STRING);
        $token->shouldHaveKeyWithValue('value', 'slashes \\ \/');
        $token->shouldHaveKeyWithValue('position', 0);
    }

    function it_lexes_numbers()
    {
        $this->setInput('4');
        $token = $this->glimpse();
        $token->shouldHaveKeyWithValue('type', Lexer::INT);
        $token->shouldHaveKeyWithValue('value', 4);
        $token->shouldHaveKeyWithValue('position', 0);

        $this->setInput('4.123');
        $token = $this->glimpse();
        $token->shouldHaveKeyWithValue('type', Lexer::FLOAT);
        $token->shouldHaveKeyWithValue('value', 4.123);
        $token->shouldHaveKeyWithValue('position', 0);

        $this->setInput('-4');
        $token = $this->glimpse();
        $token->shouldHaveKeyWithValue('type', Lexer::INT);
        $token->shouldHaveKeyWithValue('value', -4);
        $token->shouldHaveKeyWithValue('position', 0);

        $this->setInput('9');
        $token = $this->glimpse();
        $token->shouldHaveKeyWithValue('type', Lexer::INT);
        $token->shouldHaveKeyWithValue('value', 9);
        $token->shouldHaveKeyWithValue('position', 0);

        $this->setInput('0');
        $token = $this->glimpse();
        $token->shouldHaveKeyWithValue('type', Lexer::INT);
        $token->shouldHaveKeyWithValue('value', 0);
        $token->shouldHaveKeyWithValue('position', 0);

        $this->setInput('00');
        $token = $this->glimpse();
        $token->shouldHaveKeyWithValue('type', Lexer::INT);
        $token->shouldHaveKeyWithValue('value', 0);
        $token->shouldHaveKeyWithValue('position', 0);

        $this->setInput('-4.123');
        $token = $this->glimpse();
        $token->shouldHaveKeyWithValue('type', Lexer::FLOAT);
        $token->shouldHaveKeyWithValue('value', -4.123);
        $token->shouldHaveKeyWithValue('position', 0);

        $this->setInput('0.123');
        $token = $this->glimpse();
        $token->shouldHaveKeyWithValue('type', Lexer::FLOAT);
        $token->shouldHaveKeyWithValue('value', 0.123);
        $token->shouldHaveKeyWithValue('position', 0);

        $this->setInput('123e4');
        $token = $this->glimpse();
        $token->shouldHaveKeyWithValue('type', Lexer::FLOAT);
        $token->shouldHaveKeyWithValue('value', 123e4);
        $token->shouldHaveKeyWithValue('position', 0);

        $this->setInput('123E4');
        $token = $this->glimpse();
        $token->shouldHaveKeyWithValue('type', Lexer::FLOAT);
        $token->shouldHaveKeyWithValue('value', 123E4);
        $token->shouldHaveKeyWithValue('position', 0);

        $this->setInput('123e-4');
        $token = $this->glimpse();
        $token->shouldHaveKeyWithValue('type', Lexer::FLOAT);
        $token->shouldHaveKeyWithValue('value', 123e-4);
        $token->shouldHaveKeyWithValue('position', 0);

        $this->setInput('123e+4');
        $token = $this->glimpse();
        $token->shouldHaveKeyWithValue('type', Lexer::FLOAT);
        $token->shouldHaveKeyWithValue('value', 123e4);
        $token->shouldHaveKeyWithValue('position', 0);

        $this->setInput('-1.123e4');
        $token = $this->glimpse();
        $token->shouldHaveKeyWithValue('type', Lexer::FLOAT);
        $token->shouldHaveKeyWithValue('value', -1.123e4);
        $token->shouldHaveKeyWithValue('position', 0);

        $this->setInput('-1.123E4');
        $token = $this->glimpse();
        $token->shouldHaveKeyWithValue('type', Lexer::FLOAT);
        $token->shouldHaveKeyWithValue('value', -1.123E4);
        $token->shouldHaveKeyWithValue('position', 0);

        $this->setInput('-1.123e-4');
        $token = $this->glimpse();
        $token->shouldHaveKeyWithValue('type', Lexer::FLOAT);
        $token->shouldHaveKeyWithValue('value', -1.123e-4);
        $token->shouldHaveKeyWithValue('position', 0);

        $this->setInput('-1.123e+4');
        $token = $this->glimpse();
        $token->shouldHaveKeyWithValue('type', Lexer::FLOAT);
        $token->shouldHaveKeyWithValue('value', -1.123e+4);
        $token->shouldHaveKeyWithValue('position', 0);

        $this->setInput('-1.123e4567');
        $token = $this->glimpse();
        $token->shouldHaveKeyWithValue('type', Lexer::FLOAT);
        $token->shouldHaveKeyWithValue('value', -1.123e4567);
        $token->shouldHaveKeyWithValue('position', 0);
    }

    function it_lexes_punctuation()
    {
        $this->setInput('!');
        $token = $this->glimpse();
        $token->shouldHaveKeyWithValue('type', Lexer::BANG);
        $token->shouldHaveKeyWithValue('value', null);
        $token->shouldHaveKeyWithValue('position', 0);

        $this->setInput('$');
        $token = $this->glimpse();
        $token->shouldHaveKeyWithValue('type', Lexer::DOLLAR);
        $token->shouldHaveKeyWithValue('value', null);
        $token->shouldHaveKeyWithValue('position', 0);

        $this->setInput('(');
        $token = $this->glimpse();
        $token->shouldHaveKeyWithValue('type', Lexer::PAREN_L);
        $token->shouldHaveKeyWithValue('value', null);
        $token->shouldHaveKeyWithValue('position', 0);

        $this->setInput(')');
        $token = $this->glimpse();
        $token->shouldHaveKeyWithValue('type', Lexer::PAREN_R);
        $token->shouldHaveKeyWithValue('value', null);
        $token->shouldHaveKeyWithValue('position', 0);

        $this->setInput('...');
        $token = $this->glimpse();
        $token->shouldHaveKeyWithValue('type', Lexer::SPREAD);
        $token->shouldHaveKeyWithValue('value', null);
        $token->shouldHaveKeyWithValue('position', 0);

        $this->setInput(':');
        $token = $this->glimpse();
        $token->shouldHaveKeyWithValue('type', Lexer::COLON);
        $token->shouldHaveKeyWithValue('value', null);
        $token->shouldHaveKeyWithValue('position', 0);

        $this->setInput('=');
        $token = $this->glimpse();
        $token->shouldHaveKeyWithValue('type', Lexer::EQUALS);
        $token->shouldHaveKeyWithValue('value', null);
        $token->shouldHaveKeyWithValue('position', 0);

        $this->setInput('@');
        $token = $this->glimpse();
        $token->shouldHaveKeyWithValue('type', Lexer::AT);
        $token->shouldHaveKeyWithValue('value', null);
        $token->shouldHaveKeyWithValue('position', 0);

        $this->setInput('[');
        $token = $this->glimpse();
        $token->shouldHaveKeyWithValue('type', Lexer::BRACKET_L);
        $token->shouldHaveKeyWithValue('value', null);
        $token->shouldHaveKeyWithValue('position', 0);

        $this->setInput(']');
        $token = $this->glimpse();
        $token->shouldHaveKeyWithValue('type', Lexer::BRACKET_R);
        $token->shouldHaveKeyWithValue('value', null);
        $token->shouldHaveKeyWithValue('position', 0);

        $this->setInput('{');
        $token = $this->glimpse();
        $token->shouldHaveKeyWithValue('type', Lexer::BRACE_L);
        $token->shouldHaveKeyWithValue('value', null);
        $token->shouldHaveKeyWithValue('position', 0);

        $this->setInput('|');
        $token = $this->glimpse();
        $token->shouldHaveKeyWithValue('type', Lexer::PIPE);
        $token->shouldHaveKeyWithValue('value', null);
        $token->shouldHaveKeyWithValue('position', 0);

        $this->setInput('}');
        $token = $this->glimpse();
        $token->shouldHaveKeyWithValue('type', Lexer::BRACE_R);
        $token->shouldHaveKeyWithValue('value', null);
        $token->shouldHaveKeyWithValue('position', 0);
    }

    // TODO: Handle errors in Lexer
    /*
    function it_respects_whitespace_in_errors()
    {
        $this->setInput(<<<EOL

        ?

EOL
        );
        $token = $this->glimpse();
        $token->shouldHaveKeyWithValue('type', Lexer::NAME);
        $token->shouldHaveKeyWithValue('value', 'foo');
        $token->shouldHaveKeyWithValue('position', 9);
    }
    */
}
