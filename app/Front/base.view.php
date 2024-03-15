<?php /** @var \Tempest\View\GenericView $this */ ?>

<html lang="en">
<head>
    <title><?= ($this->title ?? null) ? "{$this->title} | Tempest" : "Tempest"?></title>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <?= $this->slot('styles') ?>
    <link href="/main.css" rel="stylesheet">

    <link rel="apple-touch-icon" sizes="180x180" href="favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="favicon/favicon-16x16.png">
    <link rel="manifest" href="favicon/site.webmanifest">
</head>
<body class="relative">

<div class="text-center py-4 bg-[#4f95d1] font-bold text-white w-full z-[99] mb-4">
    Tempest is still a <span class="comment text-white">work in progress</span>. You can <a href="https://github.com/tempestphp/tempest-framework/issues" class="underline hover:no-underline">help out</a>.
</div>

<?= $this->slot() ?>

<?= $this->slot('scripts') ?>
</body>
</html>