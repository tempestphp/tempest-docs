<?php

namespace App\Highlight\Languages;

use App\Highlight\Language;
use App\Highlight\Patterns\Php\AttributeTokenPattern;
use App\Highlight\Patterns\Php\ClassNameTokenPattern;
use App\Highlight\Patterns\Php\ClassPropertyTokenPattern;
use App\Highlight\Patterns\Php\ConstantPropertyTokenPattern;
use App\Highlight\Patterns\Php\ExtendsTokenPattern;
use App\Highlight\Patterns\Php\FunctionCallTokenPattern;
use App\Highlight\Patterns\Php\KeywordTokenPattern;
use App\Highlight\Patterns\Php\NestedFunctionCallTokenPattern;
use App\Highlight\Patterns\Php\FunctionNameTokenPattern;
use App\Highlight\Patterns\Php\ImplementsTokenPattern;
use App\Highlight\Patterns\Php\MultilineDoubleDocCommentTokenPattern;
use App\Highlight\Patterns\Php\MultilineSingleDocCommentTokenPattern;
use App\Highlight\Patterns\Php\NamedArgumentTokenPattern;
use App\Highlight\Patterns\Php\NamespaceTokenPattern;
use App\Highlight\Patterns\Php\NewObjectTokenPattern;
use App\Highlight\Patterns\Php\ParameterTypeTokenPattern;
use App\Highlight\Patterns\Php\PropertyAccessTokenPattern;
use App\Highlight\Patterns\Php\PropertyTypesTokenPattern;
use App\Highlight\Patterns\Php\ReturnTypeTokenPattern;
use App\Highlight\Patterns\Php\SinglelineDocCommentTokenPattern;
use App\Highlight\Patterns\Php\StaticClassCallTokenPattern;
use App\Highlight\Patterns\Php\UseTokenPattern;
use App\Highlight\TokenType;

final class PhpLanguage implements Language
{
    public function getInjectionPatterns(): array
    {
        return [];
    }

    public function getTokenPatterns(): array
    {
        return [
            '(?<match>\#\[(.*?)\])' => TokenType::ATTRIBUTE, // single-line attributes

            // COMMENTS
            new MultilineDoubleDocCommentTokenPattern(),
            new MultilineSingleDocCommentTokenPattern(),
            new SinglelineDocCommentTokenPattern(),

            // TYPES
            new AttributeTokenPattern(),
            new ImplementsTokenPattern(),
            new ExtendsTokenPattern(),
            new UseTokenPattern(),
            new NamespaceTokenPattern(),
            new PropertyTypesTokenPattern(),
            new ClassNameTokenPattern(),
            new ReturnTypeTokenPattern(),
            new StaticClassCallTokenPattern(),
            new ParameterTypeTokenPattern(),
            new NewObjectTokenPattern(),

            // PROPERTIES
            new ClassPropertyTokenPattern(),
            new NamedArgumentTokenPattern(),
            new PropertyAccessTokenPattern(),
            new FunctionNameTokenPattern(),
            new NestedFunctionCallTokenPattern(),
            new FunctionCallTokenPattern(),
            new ConstantPropertyTokenPattern(),

            // KEYWORDS
            new KeywordTokenPattern('__halt_compiler'),
            new KeywordTokenPattern('abstract'),
            new KeywordTokenPattern('and'),
            new KeywordTokenPattern('array'),
            new KeywordTokenPattern('as'),
            new KeywordTokenPattern('break'),
            new KeywordTokenPattern('callable'),
            new KeywordTokenPattern('case'),
            new KeywordTokenPattern('catch'),
            new KeywordTokenPattern('class'),
            new KeywordTokenPattern('clone'),
            new KeywordTokenPattern('const'),
            new KeywordTokenPattern('continue'),
            new KeywordTokenPattern('declare'),
            new KeywordTokenPattern('default'),
            new KeywordTokenPattern('die'),
            new KeywordTokenPattern('do'),
            new KeywordTokenPattern('echo'),
            new KeywordTokenPattern('else'),
            new KeywordTokenPattern('elseif'),
            new KeywordTokenPattern('empty'),
            new KeywordTokenPattern('enddeclare'),
            new KeywordTokenPattern('endfor'),
            new KeywordTokenPattern('endforeach'),
            new KeywordTokenPattern('endif'),
            new KeywordTokenPattern('endswitch'),
            new KeywordTokenPattern('endwhile'),
            new KeywordTokenPattern('eval'),
            new KeywordTokenPattern('exit'),
            new KeywordTokenPattern('extends'),
            new KeywordTokenPattern('final'),
            new KeywordTokenPattern('finally'),
            new KeywordTokenPattern('fn'),
            new KeywordTokenPattern('for'),
            new KeywordTokenPattern('foreach'),
            new KeywordTokenPattern('function'),
            new KeywordTokenPattern('global'),
            new KeywordTokenPattern('goto'),
            new KeywordTokenPattern('if'),
            new KeywordTokenPattern('implements'),
            new KeywordTokenPattern('include'),
            new KeywordTokenPattern('include_once'),
            new KeywordTokenPattern('instanceof'),
            new KeywordTokenPattern('insteadof'),
            new KeywordTokenPattern('interface'),
            new KeywordTokenPattern('isset'),
            new KeywordTokenPattern('list'),
            new KeywordTokenPattern('match'),
            new KeywordTokenPattern('namespace'),
            new KeywordTokenPattern('new'),
            new KeywordTokenPattern('or'),
            new KeywordTokenPattern('print'),
            new KeywordTokenPattern('private'),
            new KeywordTokenPattern('protected'),
            new KeywordTokenPattern('public'),
            new KeywordTokenPattern('readonly'),
            new KeywordTokenPattern('require'),
            new KeywordTokenPattern('require_once'),
            new KeywordTokenPattern('return'),
            new KeywordTokenPattern('static'),
            new KeywordTokenPattern('switch'),
            new KeywordTokenPattern('throw'),
            new KeywordTokenPattern('trait'),
            new KeywordTokenPattern('try'),
            new KeywordTokenPattern('unset'),
            new KeywordTokenPattern('use'),
            new KeywordTokenPattern('while'),
            new KeywordTokenPattern('xor'),
            new KeywordTokenPattern('yield'),
            new KeywordTokenPattern('yield from'),
        ];
    }
}