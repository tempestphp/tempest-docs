<?php

use function Tempest\uri;

?>

<x-component name="x-meta-image">
    <html lang="en">
    <head>
        <title>Meta</title>
        <link href="<?= uri('/main.css') ?>" rel="stylesheet">
    </head>
    <body class="bg-[#333] flex justify-center items-center ">

    <div class="meta-image w-[1200px] h-[628px] flex justify-center items-center text-[60px] text-white relative">
        <div class="m-12">
            <x-slot/>
        </div>
    </div>

    </body>
    </html>
</x-component>