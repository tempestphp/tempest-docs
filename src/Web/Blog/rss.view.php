<?php

/** @var \App\Web\Blog\BlogPost[] $posts */
?>

<feed xmlns="http://www.w3.org/2005/Atom" xmlns:media="http://search.yahoo.com/mrss/">
    <id>https://tempestphp.com/rss</id>
    <link rel="self" type="application/atom+xml" href="https://tempestphp.com/rss" />
    <title>Tempest</title>
    <updated><?= date('c') ?></updated>
    <?php foreach ($posts as $post): ?>
        <entry>
            <title><![CDATA[ <?= $post->title ?> ]]></title>
            <link rel="alternate" href="<?= $post->uri ?>"/>
            <id><?= $post->uri ?></id>
            <category term="PHP" />
            <author>
              <name><?= $post->author->getFullName() ?></name>
              <uri><?= $post->author->getBluesky() ?></uri>
            </author>
            <?php if ($post->description): ?>
              <summary type="html"><![CDATA[ <?= $post->description ?> ]]></summary>
            <?php endif; ?>
            <content type="html"><![CDATA[ <?= $post->content ?> ]]></content>
            <updated><?= $post->createdAt->format('c') ?></updated>
            <published><?= $post->createdAt->format('c') ?></published>
            <media:content url="<?= $post->metaImageUri ?>" medium="image" />
        </entry>
    <?php endforeach; ?>
</feed>
