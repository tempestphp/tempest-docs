<?php

namespace App\Highlight\Languages;

use App\Highlight\Language;
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
//            '^[\s]*(?<match>\/\*\*)' => TokenType::COMMENT, // /**
//            '^[\s]*(?<match>\*.*)' => TokenType::COMMENT, // * and */
            '(?<match>\/\*.*\*\/)' => TokenType::COMMENT, // * and */
//            '(?<match>\/\/.*)' => TokenType::COMMENT, // //

            // TYPES
            '\#\[(?<match>[\w]+)' => TokenType::TYPE, // Attributes
            'implements\s(?<match>[\w]+)' => TokenType::TYPE, // implements Foo
            'extends\s(?<match>.*)$' => TokenType::TYPE, // extends Foo
            'use (?<match>[\w\\\\]+)' => TokenType::TYPE, // import statements
            '(public|private|protected)\s(?<match>[\(\)\|\&\?\w]+)\s\$' => TokenType::TYPE, // property types
            'class (?<match>[\w]+)' => TokenType::TYPE, // class names
            '\)\:\s(?<match>[\(\)\|\&\?\w]+)' => TokenType::TYPE, // return types
            '(?<match>[\w]+)\:\:' => TokenType::TYPE, // Class::
            '(?<match>[\|\&\?\w]+)\s\$' => TokenType::TYPE, // (Foo $foo)
            'new (?<match>[\w]+)' => TokenType::TYPE, // new Foo

            // PROPERTIES
            '(public|private|protected)\s([\(\)\|\&\?\w]+)\s(?<match>\$[\w]+)' => TokenType::PROPERTY, // class properties
            '(?<match>[\w]+):\s' => TokenType::PROPERTY, // named arguments
            '-\&gt\;(?<match>[\w]+)' => TokenType::PROPERTY, // property access and object function calls
            'function (?<match>[\w]+)' => TokenType::PROPERTY, // function names
            '(\s|\()(?<match>[\w]+)\(' => TokenType::PROPERTY, // function calls
            '\:\:(?<match>[\w]+)' => TokenType::PROPERTY, // ::PROP
            '\s(?<match>[a-z][\w]+)\(' => TokenType::PROPERTY, // function call

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