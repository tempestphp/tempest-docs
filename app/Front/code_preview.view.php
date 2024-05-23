<?php /** @var \Tempest\View\GenericView $this */ ?>

<html lang="en">
<head>
    <title>Code | Tempest</title>

    <style>
        <?= file_get_contents(__DIR__ . '/../../public/main.css') ?>

        pre, code {
            color: #000;
            background-color: #f3f3f3;
        }

        body {
            font-size: 1.5em;
        }

        body, pre {
            background: #fff;
        }

        pre {
            line-height: 1.6em;
            max-width: 60vw;
        }
    </style>

    <link rel="apple-touch-icon" sizes="180x180" href="/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon/favicon-16x16.png">
    <link rel="manifest" href="/favicon/site.webmanifest">
</head>
<body>

<div class="flex justify-center items-center min-h-full">
    <pre class="p-6 px-8 rounded"><?= $this->raw('code') ?></pre>
</div>
</body>
</html>
