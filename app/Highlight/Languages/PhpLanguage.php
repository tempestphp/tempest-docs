<?php

namespace App\Highlight\Languages;

use App\Highlight\Language;
use App\Highlight\Patterns\Php\AttributeTokenPattern;
use App\Highlight\Patterns\Php\ClassNameTokenPattern;
use App\Highlight\Patterns\Php\ClassPropertyTokenPattern;
use App\Highlight\Patterns\Php\ConstantPropertyTokenPattern;
use App\Highlight\Patterns\Php\ExtendsTokenPattern;
use App\Highlight\Patterns\Php\FunctionCallTokenPattern;
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

    public function getLinePatterns(): array
    {
        return [
            '\#\[(.*?)\]' => TokenType::ATTRIBUTE, // single-line attributes
        ];
    }

    public function getTokenPatterns(): array
    {
        return [
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
            '\b(?<match>__halt_compiler)\s' => TokenType::KEYWORD,
            '\b(?<match>abstract)\s' => TokenType::KEYWORD,
            '\b(?<match>and)\s' => TokenType::KEYWORD,
            '\b(?<match>array)\s' => TokenType::KEYWORD,
            '\b(?<match>as)\s' => TokenType::KEYWORD,
            '\b(?<match>break)\s' => TokenType::KEYWORD,
            '\b(?<match>callable)\s' => TokenType::KEYWORD,
            '\b(?<match>case)\s' => TokenType::KEYWORD,
            '\b(?<match>catch)\s' => TokenType::KEYWORD,
            '\b(?<match>class)\s' => TokenType::KEYWORD,
            '\b(?<match>clone)\s' => TokenType::KEYWORD,
            '\b(?<match>const)\s' => TokenType::KEYWORD,
            '\b(?<match>continue)\s' => TokenType::KEYWORD,
            '\b(?<match>declare)\s' => TokenType::KEYWORD,
            '\b(?<match>default)\s' => TokenType::KEYWORD,
            '\b(?<match>die)\s' => TokenType::KEYWORD,
            '\b(?<match>do)\s' => TokenType::KEYWORD,
            '\b(?<match>echo)\s' => TokenType::KEYWORD,
            '\b(?<match>else)\s' => TokenType::KEYWORD,
            '\b(?<match>elseif)\s' => TokenType::KEYWORD,
            '\b(?<match>empty)\s' => TokenType::KEYWORD,
            '\b(?<match>enddeclare)\s' => TokenType::KEYWORD,
            '\b(?<match>endfor)\s' => TokenType::KEYWORD,
            '\b(?<match>endforeach)\s' => TokenType::KEYWORD,
            '\b(?<match>endif)\s' => TokenType::KEYWORD,
            '\b(?<match>endswitch)\s' => TokenType::KEYWORD,
            '\b(?<match>endwhile)\s' => TokenType::KEYWORD,
            '\b(?<match>eval)\s' => TokenType::KEYWORD,
            '\b(?<match>exit)\s' => TokenType::KEYWORD,
            '\b(?<match>extends)\s' => TokenType::KEYWORD,
            '\b(?<match>final)\s' => TokenType::KEYWORD,
            '\b(?<match>finally)\s' => TokenType::KEYWORD,
            '\b(?<match>fn)\s' => TokenType::KEYWORD,
            '\b(?<match>for)\s' => TokenType::KEYWORD,
            '\b(?<match>foreach)\s' => TokenType::KEYWORD,
            '\b(?<match>function)\s' => TokenType::KEYWORD,
            '\b(?<match>global)\s' => TokenType::KEYWORD,
            '\b(?<match>goto)\s' => TokenType::KEYWORD,
            '\b(?<match>if)\s' => TokenType::KEYWORD,
            '\b(?<match>implements)\s' => TokenType::KEYWORD,
            '\b(?<match>include)\s' => TokenType::KEYWORD,
            '\b(?<match>include_once)\s' => TokenType::KEYWORD,
            '\b(?<match>instanceof)\s' => TokenType::KEYWORD,
            '\b(?<match>insteadof)\s' => TokenType::KEYWORD,
            '\b(?<match>interface)\s' => TokenType::KEYWORD,
            '\b(?<match>isset)\s' => TokenType::KEYWORD,
            '\b(?<match>list)\s' => TokenType::KEYWORD,
            '\b(?<match>match)\s' => TokenType::KEYWORD,
            '\b(?<match>namespace)\s' => TokenType::KEYWORD,
            '\b(?<match>new)\s' => TokenType::KEYWORD,
            '\b(?<match>or)\s' => TokenType::KEYWORD,
            '\b(?<match>print)\s' => TokenType::KEYWORD,
            '\b(?<match>private)\s' => TokenType::KEYWORD,
            '\b(?<match>protected)\s' => TokenType::KEYWORD,
            '\b(?<match>public)\s' => TokenType::KEYWORD,
            '\b(?<match>readonly)\s' => TokenType::KEYWORD,
            '\b(?<match>require)\s' => TokenType::KEYWORD,
            '\b(?<match>require_once)\s' => TokenType::KEYWORD,
            '\b(?<match>return)\s' => TokenType::KEYWORD,
            '\b(?<match>static)\s' => TokenType::KEYWORD,
            '\b(?<match>switch)\s' => TokenType::KEYWORD,
            '\b(?<match>throw)\s' => TokenType::KEYWORD,
            '\b(?<match>trait)\s' => TokenType::KEYWORD,
            '\b(?<match>try)\s' => TokenType::KEYWORD,
            '\b(?<match>unset)\s' => TokenType::KEYWORD,
            '\b(?<match>use)\s' => TokenType::KEYWORD,
            '\b(?<match>while)\s' => TokenType::KEYWORD,
            '\b(?<match>xor)\s' => TokenType::KEYWORD,
            '\b(?<match>yield)\s' => TokenType::KEYWORD,
            '\b(?<match>yield from)\s' => TokenType::KEYWORD,
        ];
    }
}