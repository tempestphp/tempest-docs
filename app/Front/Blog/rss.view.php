<?php
/** @var \App\Front\Blog\BlogPost[] $posts */
?>

<feed xmlns="http://www.w3.org/2005/Atom">
    <id>https://tempestphp.com/rss</id>
    <link href="https://tempestphp.com/rss"/>
    <title><![CDATA[ tempestphp.com ]]></title>
    <updated><?= date('c')?></updated>

    <?php foreach ($posts as $post): ?>
        <entry>
            <title><![CDATA[ <?= $post->title ?> ]]></title>

            <link rel="alternate" href="<?= $post->getUri() ?>"/>

            <id><?= $post->getUri() ?></id>

            <author>
                <name><![CDATA[ Brent Roose ]]></name>
            </author>

            <summary type="html"><![CDATA[ <?= $post->content ?> ]]></summary>

            <updated><?= $post->createdAt->format('c') ?></updated>
        </entry>
    <?php endforeach; ?>
</feed>
