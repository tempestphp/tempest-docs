<?php

namespace App\Highlight\Languages;

use App\Highlight\Language;
use App\Highlight\Patterns\Php\AttributePattern;
use App\Highlight\Patterns\Php\AttributeTypePattern;
use App\Highlight\Patterns\Php\ClassNamePattern;
use App\Highlight\Patterns\Php\ClassPropertyPattern;
use App\Highlight\Patterns\Php\ConstantPropertyPattern;
use App\Highlight\Patterns\Php\ExtendsPattern;
use App\Highlight\Patterns\Php\FunctionCallPattern;
use App\Highlight\Patterns\Php\KeywordPattern;
use App\Highlight\Patterns\Php\NestedFunctionCallPattern;
use App\Highlight\Patterns\Php\FunctionNamePattern;
use App\Highlight\Patterns\Php\ImplementsPattern;
use App\Highlight\Patterns\Php\MultilineDoubleDocCommentPattern;
use App\Highlight\Patterns\Php\MultilineSingleDocCommentPattern;
use App\Highlight\Patterns\Php\NamedArgumentPattern;
use App\Highlight\Patterns\Php\NamespacePattern;
use App\Highlight\Patterns\Php\NewObjectPattern;
use App\Highlight\Patterns\Php\ParameterTypePattern;
use App\Highlight\Patterns\Php\PropertyAccessPattern;
use App\Highlight\Patterns\Php\PropertyTypesPattern;
use App\Highlight\Patterns\Php\ReturnTypePattern;
use App\Highlight\Patterns\Php\SinglelineDocCommentPattern;
use App\Highlight\Patterns\Php\StaticClassCallPattern;
use App\Highlight\Patterns\Php\UsePattern;

class PhpLanguage implements Language
{
    public function getInjections(): array
    {
        return [];
    }

    public function getPatterns(): array
    {
        return [
            // ATTRIBUTES
            new AttributePattern(),

            // COMMENTS
            new MultilineDoubleDocCommentPattern(),
            new MultilineSingleDocCommentPattern(),
            new SinglelineDocCommentPattern(),

            // TYPES
            new AttributeTypePattern(),
            new ImplementsPattern(),
            new ExtendsPattern(),
            new UsePattern(),
            new NamespacePattern(),
            new PropertyTypesPattern(),
            new ClassNamePattern(),
            new ReturnTypePattern(),
            new StaticClassCallPattern(),
            new ParameterTypePattern(),
            new NewObjectPattern(),

            // PROPERTIES
            new ClassPropertyPattern(),
            new NamedArgumentPattern(),
            new PropertyAccessPattern(),
            new FunctionNamePattern(),
            new NestedFunctionCallPattern(),
            new FunctionCallPattern(),
            new ConstantPropertyPattern(),

            // KEYWORDS
            new KeywordPattern('__halt_compiler'),
            new KeywordPattern('abstract'),
            new KeywordPattern('and'),
            new KeywordPattern('array'),
            new KeywordPattern('as'),
            new KeywordPattern('break'),
            new KeywordPattern('callable'),
            new KeywordPattern('case'),
            new KeywordPattern('catch'),
            new KeywordPattern('class'),
            new KeywordPattern('clone'),
            new KeywordPattern('const'),
            new KeywordPattern('continue'),
            new KeywordPattern('declare'),
            new KeywordPattern('default'),
            new KeywordPattern('die'),
            new KeywordPattern('do'),
            new KeywordPattern('echo'),
            new KeywordPattern('else'),
            new KeywordPattern('elseif'),
            new KeywordPattern('empty'),
            new KeywordPattern('enddeclare'),
            new KeywordPattern('endfor'),
            new KeywordPattern('endforeach'),
            new KeywordPattern('endif'),
            new KeywordPattern('endswitch'),
            new KeywordPattern('endwhile'),
            new KeywordPattern('eval'),
            new KeywordPattern('exit'),
            new KeywordPattern('extends'),
            new KeywordPattern('final'),
            new KeywordPattern('finally'),
            new KeywordPattern('fn'),
            new KeywordPattern('for'),
            new KeywordPattern('foreach'),
            new KeywordPattern('function'),
            new KeywordPattern('global'),
            new KeywordPattern('goto'),
            new KeywordPattern('if'),
            new KeywordPattern('implements'),
            new KeywordPattern('include'),
            new KeywordPattern('include_once'),
            new KeywordPattern('instanceof'),
            new KeywordPattern('insteadof'),
            new KeywordPattern('interface'),
            new KeywordPattern('isset'),
            new KeywordPattern('list'),
            new KeywordPattern('match'),
            new KeywordPattern('namespace'),
            new KeywordPattern('new'),
            new KeywordPattern('or'),
            new KeywordPattern('print'),
            new KeywordPattern('private'),
            new KeywordPattern('protected'),
            new KeywordPattern('public'),
            new KeywordPattern('readonly'),
            new KeywordPattern('require'),
            new KeywordPattern('require_once'),
            new KeywordPattern('return'),
            new KeywordPattern('static'),
            new KeywordPattern('switch'),
            new KeywordPattern('throw'),
            new KeywordPattern('trait'),
            new KeywordPattern('try'),
            new KeywordPattern('unset'),
            new KeywordPattern('use'),
            new KeywordPattern('while'),
            new KeywordPattern('xor'),
            new KeywordPattern('yield'),
            new KeywordPattern('yield from'),
        ];
    }
}