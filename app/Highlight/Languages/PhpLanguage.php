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
            '-\&gt\;(?<match>[\w]+)' => TokenType::PROPERTY, // property access (escaped)
            'function (?<match>[\w]+)' => TokenType::PROPERTY, // function names
            '\:\:(?<match>[\w]+)' => TokenType::PROPERTY, // ::PROP
            '\b(?<match>[a-z][\w]+)\(' => TokenType::PROPERTY, // function call

            // KEYWORDS
            '\b(?<match>__halt_compiler)\b' => TokenType::KEYWORD,
            '\b(?<match>abstract)\b' => TokenType::KEYWORD,
            '\b(?<match>and)\b' => TokenType::KEYWORD,
            '\b(?<match>array)\b' => TokenType::KEYWORD,
            '\b(?<match>as)\b' => TokenType::KEYWORD,
            '\b(?<match>break)\b' => TokenType::KEYWORD,
            '\b(?<match>callable)\b' => TokenType::KEYWORD,
            '\b(?<match>case)\b' => TokenType::KEYWORD,
            '\b(?<match>catch)\b' => TokenType::KEYWORD,
            '\b(?<match>class)\b' => TokenType::KEYWORD,
            '\b(?<match>clone)\b' => TokenType::KEYWORD,
            '\b(?<match>const)\b' => TokenType::KEYWORD,
            '\b(?<match>continue)\b' => TokenType::KEYWORD,
            '\b(?<match>declare)\b' => TokenType::KEYWORD,
            '\b(?<match>default)\b' => TokenType::KEYWORD,
            '\b(?<match>die)\b' => TokenType::KEYWORD,
            '\b(?<match>do)\b' => TokenType::KEYWORD,
            '\b(?<match>echo)\b' => TokenType::KEYWORD,
            '\b(?<match>else)\b' => TokenType::KEYWORD,
            '\b(?<match>elseif)\b' => TokenType::KEYWORD,
            '\b(?<match>empty)\b' => TokenType::KEYWORD,
            '\b(?<match>enddeclare)\b' => TokenType::KEYWORD,
            '\b(?<match>endfor)\b' => TokenType::KEYWORD,
            '\b(?<match>endforeach)\b' => TokenType::KEYWORD,
            '\b(?<match>endif)\b' => TokenType::KEYWORD,
            '\b(?<match>endswitch)\b' => TokenType::KEYWORD,
            '\b(?<match>endwhile)\b' => TokenType::KEYWORD,
            '\b(?<match>eval)\b' => TokenType::KEYWORD,
            '\b(?<match>exit)\b' => TokenType::KEYWORD,
            '\b(?<match>extends)\b' => TokenType::KEYWORD,
            '\b(?<match>final)\b' => TokenType::KEYWORD,
            '\b(?<match>finally)\b' => TokenType::KEYWORD,
            '\b(?<match>fn)\b' => TokenType::KEYWORD,
            '\b(?<match>for)\b' => TokenType::KEYWORD,
            '\b(?<match>foreach)\b' => TokenType::KEYWORD,
            '\b(?<match>function)\b' => TokenType::KEYWORD,
            '\b(?<match>global)\b' => TokenType::KEYWORD,
            '\b(?<match>goto)\b' => TokenType::KEYWORD,
            '\b(?<match>if)\b' => TokenType::KEYWORD,
            '\b(?<match>implements)\b' => TokenType::KEYWORD,
            '\b(?<match>include)\b' => TokenType::KEYWORD,
            '\b(?<match>include_once)\b' => TokenType::KEYWORD,
            '\b(?<match>instanceof)\b' => TokenType::KEYWORD,
            '\b(?<match>insteadof)\b' => TokenType::KEYWORD,
            '\b(?<match>interface)\b' => TokenType::KEYWORD,
            '\b(?<match>isset)\b' => TokenType::KEYWORD,
            '\b(?<match>list)\b' => TokenType::KEYWORD,
            '\b(?<match>match)\b' => TokenType::KEYWORD,
            '\b(?<match>namespace)\b' => TokenType::KEYWORD,
            '\b(?<match>new)\b' => TokenType::KEYWORD,
            '\b(?<match>or)\b' => TokenType::KEYWORD,
            '\b(?<match>print)\b' => TokenType::KEYWORD,
            '\b(?<match>private)\b' => TokenType::KEYWORD,
            '\b(?<match>protected)\b' => TokenType::KEYWORD,
            '\b(?<match>public)\b' => TokenType::KEYWORD,
            '\b(?<match>readonly)\b' => TokenType::KEYWORD,
            '\b(?<match>require)\b' => TokenType::KEYWORD,
            '\b(?<match>require_once)\b' => TokenType::KEYWORD,
            '\b(?<match>return)\b' => TokenType::KEYWORD,
            '\b(?<match>static)\b' => TokenType::KEYWORD,
            '\b(?<match>switch)\b' => TokenType::KEYWORD,
            '\b(?<match>throw)\b' => TokenType::KEYWORD,
            '\b(?<match>trait)\b' => TokenType::KEYWORD,
            '\b(?<match>try)\b' => TokenType::KEYWORD,
            '\b(?<match>unset)\b' => TokenType::KEYWORD,
            '\b(?<match>use)\b' => TokenType::KEYWORD,
            '\b(?<match>while)\b' => TokenType::KEYWORD,
            '\b(?<match>xor)\b' => TokenType::KEYWORD,
            '\b(?<match>yield)\b' => TokenType::KEYWORD,
            '\b(?<match>yield from)\b' => TokenType::KEYWORD,
        ];
    }
}