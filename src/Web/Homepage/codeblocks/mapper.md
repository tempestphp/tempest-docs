```php
use function Tempest\map;

map('path/to/books.json')->collection->to(Book::class);

map($book)->to(MapTo::JSON);
```
