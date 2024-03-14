<?php

namespace App\Kernel\Persistence\Doctrine\Function;

use Doctrine\ORM\Query\AST\Functions\FunctionNode;
use Doctrine\ORM\Query\Lexer;
use Doctrine\ORM\Query\Parser;
use Doctrine\ORM\Query\QueryException;
use Doctrine\ORM\Query\SqlWalker;
use Doctrine\ORM\Query\TokenType;

/**
 * Custom unique, short ID generator DQL function.
 *
 * @class GenerateShortId
 * @see https://www.doctrine-project.org/projects/doctrine-orm/en/3.1/cookbook/dql-user-defined-functions.html
 */
class GenerateShortId extends FunctionNode
{
    /**
     * The table in which the ID should be unique.
     */
    public $firstArgument = null;

    /**
     * Function SQL name getter
     *
     * @return string
     */
    protected function getFunctionName(): string
    {
        return 'fn_generate_short_id';
    }

    public function getSql(SqlWalker $sqlWalker): string
    {
        return $this->getFunctionName().'(' .
            $this->firstArgument->dispatch($sqlWalker) .
        ')';
    }

    public function parse(Parser $parser): void
    {
        $lexer = $parser->getLexer();

        $parser->match(TokenType::T_IDENTIFIER);
        $parser->match(TokenType::T_OPEN_PARENTHESIS);

        $this->firstArgument = $parser->StringPrimary();

        $parser->match(TokenType::T_CLOSE_PARENTHESIS);
    }
}
