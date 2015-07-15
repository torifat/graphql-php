<?php

namespace GraphQL;

use Doctrine\Common\Lexer\AbstractLexer;

final class Lexer extends AbstractLexer
{
    const EOF = 1;
    const BANG = 2;
    const DOLLAR = 3;
    const PAREN_L = 4;
    const PAREN_R = 5;
    const SPREAD = 6;
    const COLON = 7;
    const EQUALS = 8;
    const AT = 9;
    const BRACKET_L = 10;
    const BRACKET_R = 11;
    const BRACE_L = 12;
    const PIPE = 13;
    const BRACE_R = 14;
    const NAME = 15;
    const VARIABLE = 16;
    const INT = 17;
    const FLOAT = 18;
    const STRING = 19;

    /**
     * @var array
     */
    protected $mapping = [
        '!' => self::BANG,
        '$' => self::DOLLAR,
        '(' => self::PAREN_L,
        ')' => self::PAREN_R,
        '...' => self::SPREAD,
        ':' => self::COLON,
        '=' => self::EQUALS,
        '@' => self::AT,
        '[' => self::BRACKET_L,
        ']' => self::BRACKET_R,
        '{' => self::BRACE_L,
        '|' => self::PIPE,
        '}' => self::BRACE_R,

    ];

    /**
     * {@inheritdoc}
     */
    protected function getCatchablePatterns()
    {
        return [
            '[\w-]+'
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function getNonCatchablePatterns()
    {
        return [
            // Skip whitespace
            '\s+',
            // Skip comments
            '(\/\/|#)(.*)$'
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function getType(&$value)
    {
        if (isset($this->mapping[$value])) {
            return $this->mapping[$value];
        }

        if($this->isName($value)) {
            return self::NAME;
        }

        if (is_numeric($value)) {
            return is_float($value) ? self::FLOAT : self::INT;
        }
        var_dump($value);
    }

    /**
     * @param $text
     * @return boolean
     */
    protected function isName($text)
    {
        return preg_match('/[_A-Za-z][_0-9A-Za-z]*/', $text);
    }

}
