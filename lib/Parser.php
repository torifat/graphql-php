<?php

namespace GraphQL;

use Doctrine\Common\Lexer\AbstractLexer;

class Parser
{
    private $lexer;

    /**
     * Parser constructor.
     * @param AbstractLexer $lexer
     */
    public function __construct(AbstractLexer $lexer)
    {
        $this->lexer = $lexer;
    }

    public function parse($input)
    {
        $this->lexer->setInput($input);
        $this->lexer->moveNext();

        $definitions = [];
        while (null !== ($token = $this->lexer->lookahead))
        {
            $definitions[] = $token['value'];
            $this->lexer->moveNext();
        }

        return $definitions;
    }
}
