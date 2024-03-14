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
            '-\&gt\;(?<match>[\w]+)' => TokenType::PROPERTY, // property access and object function calls
            'function (?<match>[\w]+)' => TokenType::PROPERTY, // function names
            '\:\:(?<match>[\w]+)' => TokenType::PROPERTY, // ::PROP
            '\s(?<match>[a-z][\w]+)\(' => TokenType::PROPERTY, // function call

            // KEYWORDS
            '(\s|^)(?<match>__halt_compiler)\s' => TokenType::KEYWORD,
            '(\s|^)(?<match>abstract)\s' => TokenType::KEYWORD,
            '(\s|^)(?<match>and)\s' => TokenType::KEYWORD,
            '(\s|^)(?<match>array)\s' => TokenType::KEYWORD,
            '(\s|^)(?<match>as)\s' => TokenType::KEYWORD,
            '(\s|^)(?<match>break)\s' => TokenType::KEYWORD,
            '(\s|^)(?<match>callable)\s' => TokenType::KEYWORD,
            '(\s|^)(?<match>case)\s' => TokenType::KEYWORD,
            '(\s|^)(?<match>catch)\s' => TokenType::KEYWORD,
            '(\s|^)(?<match>class)\s' => TokenType::KEYWORD,
            '(\s|^)(?<match>clone)\s' => TokenType::KEYWORD,
            '(\s|^)(?<match>const)\s' => TokenType::KEYWORD,
            '(\s|^)(?<match>continue)\s' => TokenType::KEYWORD,
            '(\s|^)(?<match>declare)\s' => TokenType::KEYWORD,
            '(\s|^)(?<match>default)\s' => TokenType::KEYWORD,
            '(\s|^)(?<match>die)\s' => TokenType::KEYWORD,
            '(\s|^)(?<match>do)\s' => TokenType::KEYWORD,
            '(\s|^)(?<match>echo)\s' => TokenType::KEYWORD,
            '(\s|^)(?<match>else)\s' => TokenType::KEYWORD,
            '(\s|^)(?<match>elseif)\s' => TokenType::KEYWORD,
            '(\s|^)(?<match>empty)\s' => TokenType::KEYWORD,
            '(\s|^)(?<match>enddeclare)\s' => TokenType::KEYWORD,
            '(\s|^)(?<match>endfor)\s' => TokenType::KEYWORD,
            '(\s|^)(?<match>endforeach)\s' => TokenType::KEYWORD,
            '(\s|^)(?<match>endif)\s' => TokenType::KEYWORD,
            '(\s|^)(?<match>endswitch)\s' => TokenType::KEYWORD,
            '(\s|^)(?<match>endwhile)\s' => TokenType::KEYWORD,
            '(\s|^)(?<match>eval)\s' => TokenType::KEYWORD,
            '(\s|^)(?<match>exit)\s' => TokenType::KEYWORD,
            '(\s|^)(?<match>extends)\s' => TokenType::KEYWORD,
            '(\s|^)(?<match>final)\s' => TokenType::KEYWORD,
            '(\s|^)(?<match>finally)\s' => TokenType::KEYWORD,
            '(\s|^)(?<match>fn)\s' => TokenType::KEYWORD,
            '(\s|^)(?<match>for)\s' => TokenType::KEYWORD,
            '(\s|^)(?<match>foreach)\s' => TokenType::KEYWORD,
            '(\s|^)(?<match>function)\s' => TokenType::KEYWORD,
            '(\s|^)(?<match>global)\s' => TokenType::KEYWORD,
            '(\s|^)(?<match>goto)\s' => TokenType::KEYWORD,
            '(\s|^)(?<match>if)\s' => TokenType::KEYWORD,
            '(\s|^)(?<match>implements)\s' => TokenType::KEYWORD,
            '(\s|^)(?<match>include)\s' => TokenType::KEYWORD,
            '(\s|^)(?<match>include_once)\s' => TokenType::KEYWORD,
            '(\s|^)(?<match>instanceof)\s' => TokenType::KEYWORD,
            '(\s|^)(?<match>insteadof)\s' => TokenType::KEYWORD,
            '(\s|^)(?<match>interface)\s' => TokenType::KEYWORD,
            '(\s|^)(?<match>isset)\s' => TokenType::KEYWORD,
            '(\s|^)(?<match>list)\s' => TokenType::KEYWORD,
            '(\s|^)(?<match>match)\s' => TokenType::KEYWORD,
            '(\s|^)(?<match>namespace)\s' => TokenType::KEYWORD,
            '(\s|^)(?<match>new)\s' => TokenType::KEYWORD,
            '(\s|^)(?<match>or)\s' => TokenType::KEYWORD,
            '(\s|^)(?<match>print)\s' => TokenType::KEYWORD,
            '(\s|^)(?<match>private)\s' => TokenType::KEYWORD,
            '(\s|^)(?<match>protected)\s' => TokenType::KEYWORD,
            '(\s|^)(?<match>public)\s' => TokenType::KEYWORD,
            '(\s|^)(?<match>readonly)\s' => TokenType::KEYWORD,
            '(\s|^)(?<match>require)\s' => TokenType::KEYWORD,
            '(\s|^)(?<match>require_once)\s' => TokenType::KEYWORD,
            '(\s|^)(?<match>return)\s' => TokenType::KEYWORD,
            '(\s|^)(?<match>static)\s' => TokenType::KEYWORD,
            '(\s|^)(?<match>switch)\s' => TokenType::KEYWORD,
            '(\s|^)(?<match>throw)\s' => TokenType::KEYWORD,
            '(\s|^)(?<match>trait)\s' => TokenType::KEYWORD,
            '(\s|^)(?<match>try)\s' => TokenType::KEYWORD,
            '(\s|^)(?<match>unset)\s' => TokenType::KEYWORD,
            '(\s|^)(?<match>use)\s' => TokenType::KEYWORD,
            '(\s|^)(?<match>while)\s' => TokenType::KEYWORD,
            '(\s|^)(?<match>xor)\s' => TokenType::KEYWORD,
            '(\s|^)(?<match>yield)\s' => TokenType::KEYWORD,
            '(\s|^)(?<match>yield from)\s' => TokenType::KEYWORD,
        ];
    }
}