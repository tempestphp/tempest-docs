```php
$book = query(Book::class)
    ->select()
    ->where('title', 'Timeline Taxi')
    ->first();

// …

$json = map($book)->toJson();
```
