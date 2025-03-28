```php
$books = Book::select()
    ->with('author.publisher', 'chapters')
    ->where('createdAt < :olderThan', olderThan: $olderThan)
    ->orderBy('createdAt DESC')
    ->limit(5)
    ->all();
```
