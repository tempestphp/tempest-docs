<?php /** @var \Tempest\View\GenericView $this */ ?>

<html lang="en">
<head>
    <title>Code</title>

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
</head>
<body>

<div class="flex justify-center items-center min-h-full my-16">
    <pre class="p-6 px-8 rounded"><?= $this->raw('code') ?></pre>
</div>
</body>
</html>
