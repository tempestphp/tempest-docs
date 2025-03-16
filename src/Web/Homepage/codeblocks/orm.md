```php
$books = Book::query()
    ->with('author.publisher')
    ->where('createdAt < :olderThan', olderThan: $olderThan)
    ->orderBy('createdAt DESC')
    ->limit(5)
    ->all();
```
