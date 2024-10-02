```txt
{:hl-keyword:public function:} {:hl-property:books:}(): {:hl-type:array:}
{
    $sql = <<<SQL
        SELECT * FROM books WHERE published_at <= NOW() 
    SQL;
    
    // …
}
```