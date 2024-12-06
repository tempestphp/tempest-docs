```php
public function test_book_emails_are_sent_to_subscribers()
{
    $book = // …
    
    $testMailer = new TestMailer();
    
    $publishBook = new PublishBook($testMailer);
    
    $publishBook($book, new DateTimeImmutable());
    
    $testMailer
        ->assertSent('brendt@stitcher.io', BookPublishedMail::class)
        ->assertSent('sub-2@example.com', BookPublishedMail::class)
        ->assertNotSent('no-sub@example.com', BookPublishedMail::class)
}
```