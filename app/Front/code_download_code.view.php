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

        .hl-keyword {
            color: #4285F4;
        }

        .hl-property {
            color: #34A853;
        }

        .hl-attribute {
            font-style: italic;
        }

        .hl-type {
            color: #EA4334;
        }

        .hl-generic {
            color: #9d3af6;
        }

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

        .hl-gutter {
            display: inline-block;
            font-size: 0.9em;
            color: #555;
            padding: 0 1ch;
            user-select: none;
        }

        .hl-gutter-addition {
            background-color: #34A853;
            color: #fff;
        }

        .hl-gutter-deletion {
            background-color: #EA4334;
            color: #fff;
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
