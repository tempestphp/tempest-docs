<?php

namespace App\Highlight\Languages;

use App\Highlight\Language;
use App\Highlight\TokenType;

final class PhpLanguage implements Language
{
    public function getLineRules(): array
    {
        return [
            '\#\[(.*?)\]' => TokenType::ATTRIBUTE, // single-line attributes
        ];
    }

    public function getTokenRules(): array
    {
        return [
            // COMMENTS
            '^[\s]*(?<match>\/\*\*)' => TokenType::COMMENT, // /**
            '^[\s]*(?<match>\*.*)' => TokenType::COMMENT, // * and */
            '(?<match>\/\*.*\*\/)' => TokenType::COMMENT, // * and */
            '(?<match>\/\/.*)' => TokenType::COMMENT, // //

            // TYPES
            '\#\[(?<match>[\w]+)' => TokenType::TYPE, // Attributes
            'implements\s(?<match>[\w]+)' => TokenType::TYPE, // implements Foo
            ',\s(?<match>[\w]+)' => TokenType::TYPE, // , Foo
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
            '->(?<match>[\w]+)' => TokenType::PROPERTY, // property access
            'function (?<match>[\w]+)' => TokenType::PROPERTY, // function names
            '\:\:(?<match>[\w]+)' => TokenType::PROPERTY, // ::PROP
            '\b(?<match>[a-z][\w]+)\(' => TokenType::PROPERTY, // function call

            // KEYWORDS
            '(?<match>__halt_compiler)\s' => TokenType::KEYWORD,
            '(?<match>abstract)\s' => TokenType::KEYWORD,
            '(?<match>and)\s' => TokenType::KEYWORD,
            '(?<match>array)\s' => TokenType::KEYWORD,
            '(?<match>as)\s' => TokenType::KEYWORD,
            '(?<match>break)\s' => TokenType::KEYWORD,
            '(?<match>callable)\s' => TokenType::KEYWORD,
            '(?<match>case)\s' => TokenType::KEYWORD,
            '(?<match>catch)\s' => TokenType::KEYWORD,
            '(?<match>class)\s' => TokenType::KEYWORD,
            '(?<match>clone)\s' => TokenType::KEYWORD,
            '(?<match>const)\s' => TokenType::KEYWORD,
            '(?<match>continue)\s' => TokenType::KEYWORD,
            '(?<match>declare)\s' => TokenType::KEYWORD,
            '(?<match>default)\s' => TokenType::KEYWORD,
            '(?<match>die)\s' => TokenType::KEYWORD,
            '(?<match>do)\s' => TokenType::KEYWORD,
            '(?<match>echo)\s' => TokenType::KEYWORD,
            '(?<match>else)\s' => TokenType::KEYWORD,
            '(?<match>elseif)\s' => TokenType::KEYWORD,
            '(?<match>empty)\s' => TokenType::KEYWORD,
            '(?<match>enddeclare)\s' => TokenType::KEYWORD,
            '(?<match>endfor)\s' => TokenType::KEYWORD,
            '(?<match>endforeach)\s' => TokenType::KEYWORD,
            '(?<match>endif)\s' => TokenType::KEYWORD,
            '(?<match>endswitch)\s' => TokenType::KEYWORD,
            '(?<match>endwhile)\s' => TokenType::KEYWORD,
            '(?<match>eval)\s' => TokenType::KEYWORD,
            '(?<match>exit)\s' => TokenType::KEYWORD,
            '(?<match>extends)\s' => TokenType::KEYWORD,
            '(?<match>final)\s' => TokenType::KEYWORD,
            '(?<match>finally)\s' => TokenType::KEYWORD,
            '(?<match>fn)\s' => TokenType::KEYWORD,
            '(?<match>for)\s' => TokenType::KEYWORD,
            '(?<match>foreach)\s' => TokenType::KEYWORD,
            '(?<match>function)\s' => TokenType::KEYWORD,
            '(?<match>global)\s' => TokenType::KEYWORD,
            '(?<match>goto)\s' => TokenType::KEYWORD,
            '(?<match>if)\s' => TokenType::KEYWORD,
            '(?<match>implements)\s' => TokenType::KEYWORD,
            '(?<match>include)\s' => TokenType::KEYWORD,
            '(?<match>include_once)\s' => TokenType::KEYWORD,
            '(?<match>instanceof)\s' => TokenType::KEYWORD,
            '(?<match>insteadof)\s' => TokenType::KEYWORD,
            '(?<match>interface)\s' => TokenType::KEYWORD,
            '(?<match>isset)\s' => TokenType::KEYWORD,
            '(?<match>list)\s' => TokenType::KEYWORD,
            '(?<match>match)\s' => TokenType::KEYWORD,
            '(?<match>namespace)\s' => TokenType::KEYWORD,
            '(?<match>new)\s' => TokenType::KEYWORD,
            '(?<match>or)\s' => TokenType::KEYWORD,
            '(?<match>print)\s' => TokenType::KEYWORD,
            '(?<match>private)\s' => TokenType::KEYWORD,
            '(?<match>protected)\s' => TokenType::KEYWORD,
            '(?<match>public)\s' => TokenType::KEYWORD,
            '(?<match>readonly)\s' => TokenType::KEYWORD,
            '(?<match>require)\s' => TokenType::KEYWORD,
            '(?<match>require_once)\s' => TokenType::KEYWORD,
            '(?<match>return)\s' => TokenType::KEYWORD,
            '(?<match>static)\s' => TokenType::KEYWORD,
            '(?<match>switch)\s' => TokenType::KEYWORD,
            '(?<match>throw)\s' => TokenType::KEYWORD,
            '(?<match>trait)\s' => TokenType::KEYWORD,
            '(?<match>try)\s' => TokenType::KEYWORD,
            '(?<match>unset)\s' => TokenType::KEYWORD,
            '(?<match>use)\s' => TokenType::KEYWORD,
            '(?<match>var)\s' => TokenType::KEYWORD,
            '(?<match>while)\s' => TokenType::KEYWORD,
            '(?<match>xor)\s' => TokenType::KEYWORD,
            '(?<match>yield)\s' => TokenType::KEYWORD,
            '(?<match>yield from)\s' => TokenType::KEYWORD,
        ];
    }
}