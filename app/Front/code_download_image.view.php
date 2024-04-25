<?php /** @var \Tempest\View\GenericView $this */ ?>

<html lang="en">
<head>
    <link href="/main.css" rel="stylesheet">
    <title>Code</title>

    <style>
        body {
            font-size: 1.1em;
            background-color: #253750;
        }

        pre {
            background: #fff;
            line-height: 1.6em;
            max-height: 90vh;
            overflow-y: scroll;
        }

        img {
            position: absolute;
            width: 50px;
            border-radius: 20%;
            border: 2px solid #fff;
            bottom: 20px;
            right: 20px;
        }
    </style>
</head>
<body>

<img src="/tempest-logo-square.png" alt="">
<div class="flex justify-center items-center h-full">
    <pre class="p-6 px-8 rounded shadow-xl"><?= $this->raw('code') ?></pre>
</div>
</body>
</html>
