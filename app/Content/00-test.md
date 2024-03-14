```php
namespace App\Highlight\Languages;

use App\Highlight\Language;
use App\Highlight\Theme;
use App\Highlight\Token;

/**
 * @return hello 
 */
final class PhpLanguage implements Language
{
    #[Get(hi: '/')]
    public function parse(string $content, Theme $theme): string
    {
        $rules = [];

        foreach ($this->getTypeRules() as $rule) {
            $rules[$rule] = Token::TYPE;
        }

        foreach ($this->getKeywordRules() as $rule) {
            $rules[$rule] = Token::KEYWORD;
        }

        foreach ($this->getPropertyRules() as $rule) {
            $rules[$rule] = Token::PROPERTY;
        }

        foreach ($this->getAttributeRules() as $rule) {
            $rules[$rule] = Token::ATTRIBUTE;
        }

        $compiled = [];
        $map = [];
        $id = 'a';

        foreach ($rules as $rule => $token) {
            $rule = str_replace('?<id>', "?<{$id}>", $rule);
            $compiled[] = $rule;
            $map[$id] = $token;
            $id = str_increment($id);
        }

        $compiled = '/(' . implode(')|(', $compiled) . ')/m';

        $compiled = '/((public|private|protected)\s(?<a>[\w]+)\s\$)|((?<b>public)\s)/m';
        $content = 'public bool $hi;';

        $content = preg_replace_callback(
            $compiled,
            function (array $matches) use ($theme, $map) {
                $fullMatch = $matches[0];

                foreach ($matches as $key => $value) {
                    if (is_numeric($key)) {
                        continue;
                    }

//                    $token = $map[$key];
//
//                    $parsedValue = $theme->parse($value, $token);

                    $parsedValue = '>' . $value . '<';

                    $fullMatch = str_replace($value, $parsedValue, $fullMatch);
                }

                return $fullMatch;
            },
            $content,
        );

        return $content;
    }

    private function getKeywordRules(): array
    {
        return [
            '(?<id>__halt_compiler)\s',
            '(?<id>abstract)\s',
            '(?<id>and)\s',
            '(?<id>array)\s',
            '(?<id>as)\s',
            '(?<id>break)\s',
            '(?<id>callable)\s',
            '(?<id>case)\s',
            '(?<id>catch)\s',
            '(?<id>class)\s',
            '(?<id>clone)\s',
            '(?<id>const)\s',
            '(?<id>continue)\s',
            '(?<id>declare)\s',
            '(?<id>default)\s',
            '(?<id>die)\s',
            '(?<id>do)\s',
            '(?<id>echo)\s',
            '(?<id>else)\s',
            '(?<id>elseif)\s',
            '(?<id>empty)\s',
            '(?<id>enddeclare)\s',
            '(?<id>endfor)\s',
            '(?<id>endforeach)\s',
            '(?<id>endif)\s',
            '(?<id>endswitch)\s',
            '(?<id>endwhile)\s',
            '(?<id>eval)\s',
            '(?<id>exit)\s',
            '(?<id>extends)\s',
            '(?<id>final)\s',
            '(?<id>finally)\s',
            '(?<id>fn)\s',
            '(?<id>for)\s',
            '(?<id>foreach)\s',
            '(?<id>function)\s',
            '(?<id>global)\s',
            '(?<id>goto)\s',
            '(?<id>if)\s',
            '(?<id>implements)\s',
            '(?<id>include)\s',
            '(?<id>include_once)\s',
            '(?<id>instanceof)\s',
            '(?<id>insteadof)\s',
            '(?<id>interface)\s',
            '(?<id>isset)\s',
            '(?<id>list)\s',
            '(?<id>match)\s',
            '(?<id>namespace)\s',
            '(?<id>new)\s',
            '(?<id>or)\s',
            '(?<id>print)\s',
            '(?<id>private)\s',
            '(?<id>protected)\s',
            '(?<id>public)\s',
            '(?<id>readonly)\s',
            '(?<id>require)\s',
            '(?<id>require_once)\s',
            '(?<id>return)\s',
            '(?<id>static)\s',
            '(?<id>switch)\s',
            '(?<id>throw)\s',
            '(?<id>trait)\s',
            '(?<id>try)\s',
            '(?<id>unset)\s',
            '(?<id>use)\s',
            '(?<id>var)\s',
            '(?<id>while)\s',
            '(?<id>xor)\s',
            '(?<id>yield)\s',
            '(?<id>yield from)\s',
        ];
    }

    private function getTypeRules(): array
    {
        return [
            '(public|private|protected)\s(?<id>[\w]+)\s\$', // property types
            'class (?<id>[\w]+)', // class names
            '\(\): (?<id>[\w]+)', //  types
        ];
    }

    private function getPropertyRules(): array
    {
        return [];
    }

    private function getAttributeRules(): array
    {
        return [
            '\#\[(?<id>.*)+\]', // single-line attributes
        ];
    }
}
```