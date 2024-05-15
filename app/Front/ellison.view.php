<?php

/** @var \Tempest\View\GenericView $this */

use App\Front\EllisonController;
use function Tempest\uri;

$this->extends('Front/base.view.php');
?>

<form action="<?= uri([EllisonController::class, 'submit']) ?>" method="POST">
    <div class="flex justify-center items-center flex-col w-3/5 mx-auto gap-4 font-mono">
        <label for="ellison" class="text-xl">Paste your text to be analysed</label>
        <textarea name="ellison" id="ellison" class="border-2 border-blue-300 w-full p-4 rounded" rows="20" autofocus></textarea>
        <input type="submit" name="Generate" class="bg-[#4f95d1] text-white font-bold p-4 py-3 hover:bg-[#4AB1D1] rounded cursor-pointer"/>
    </div>
</form>

