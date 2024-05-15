<?php /** @var \Tempest\View\GenericView $this */ ?>

<html lang="en">
<head>
    <title>Ellison | Tempest</title>

    <style>
        <?= file_get_contents(__DIR__ . '/../../public/main.css') ?>

        pre, code {
            color: #000;
            background-color: #f3f3f3;
        }

        body {
            font-size: 16px;
        }

        pre {
            background: #fff;
            font-family: Georgia,Times,Times New Roman,serif;
            width: 70ch;
        }
    </style>

    <link rel="apple-touch-icon" sizes="180x180" href="/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon/favicon-16x16.png">
    <link rel="manifest" href="/favicon/site.webmanifest">
</head>
<body>
<div class="flex justify-center my-8">
    <pre data-lang="ellison"><?= $this->raw('ellison') ?></pre>
</div>
</body>
</html>
