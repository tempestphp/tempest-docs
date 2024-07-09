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

        .hl-keyword {
            color: #4285F4;
        }

        .hl-property {
            color: #34A853;
        }

        .hl-attribute {
            font-style: italic;
            font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;
        }

        .hl-type {
            color: #EA4334;
        }

        .hl-generic {
            color: #9d3af6;
        }

        .hl-number,
        .hl-boolean,
        .hl-value {
            color: #000;
        }

        .hl-variable {
            color: #000;
        }

        .hl-comment {
            color: #888888;
        }

        .hl-blur {
            filter: blur(2px);
        }

        .hl-strong {
            font-weight: bold;
        }

        .hl-em {
            font-style: italic;
        }

        .hl-addition {
            min-width: 100%;
            background-color: #00FF0022;
        }

        .hl-deletion {
            min-width: 100%;
            background-color: #FF000011;
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
