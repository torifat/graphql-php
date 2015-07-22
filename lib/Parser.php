<?php

namespace GraphQL;

final class Parser
{
    private $lexer;

    /**
     * Parser constructor.
     */
    public function __construct()
    {
        $this->lexer = new Lexer;
    }

    public function parse($input)
    {
        $this->lexer->setInput($input);

        $definitions = [];
        do {
            if ($this->lexer->isNextToken(Lexer::BRACE_L)) {
                // parseOperationDefinition
            }
            elseif ($this->lexer->isNextToken(Lexer::NAME)) {
                if ($this->lexer->token['value'] === 'query' ||
                    $this->lexer->token['value'] === 'mutation') {
                    // parseOperationDefinition
                }
                elseif ($this->lexer->token['value'] === 'fragment') {
                    // parseFragmentDefinition
                }
                else {
                    // TODO: Unexpected lexed token
                }
            }
            else {
                // TODO: Unexpected lexed token
            }
        } while($this->lexer->moveNext());
        return $definitions;
    }
}
