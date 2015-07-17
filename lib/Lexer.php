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
            // Numbers
            '(?:[+-]?[0-9]+(?:[\.][0-9]+)*)(?:[eE][+-]?[0-9]+)?',
            // String with quote
            '"(?:\\"|.)*"',
            '[\w-]+',
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function getNonCatchablePatterns()
    {
        return [
            // Skip whitespace
            '[\s,]+',
            // Skip comments
            '#.*'
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function getType(&$value)
    {
        if (isset($this->mapping[$value])) {
            $type = $this->mapping[$value];
            $value = null;
            return $type;
        }

        if($this->isName($value)) {
            return self::NAME;
        }

        if (is_numeric($value)) {
            $value = +$value;
            return (is_float($value)) ? self::FLOAT : self::INT;
        }

        if ($this->isString($value)) {
            // Replace escape character
            $value = str_replace('\"', '"', $value);
            // Replace escape character
            $value = str_replace('\\\\', '\\', $value);
            // Remove quotes
            $value = substr($value, 1, -1);
            return self::STRING;
        }

        trigger_error("Unexpected character ${value}", E_WARNING);
    }

    /**
     * @param $text
     * @return boolean
     */
    protected function isName($text)
    {
        return preg_match('/^[_A-Za-z][_0-9A-Za-z]*$/', $text);
    }

    /**
     * @param $text
     * @return boolean
     */
    protected function isString($text)
    {
        return ($text[0] === '"' && $text[strlen($text) - 1] === '"');
    }

}
