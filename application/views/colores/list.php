<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<!DOCTYPE html>
<html lang="en">
    <?=$head?>
    <body>
	    <?= $nav ?>
	    <div class="container" id='listjs'>
            <div class='row list'>
                <?php foreach($colors as $color) : ?>
                    <div class='col-xs-12 col-md-4 col-lg-3 col-xl-2 mt-4'>
                        <span class='card colorcard' style='background:<?= $color->color ?>'>
                            <div>
                            <p class='text-start'>&nbsp;<?= $color->year ?></p>
                            </div>
                            <h4 class='name text-center'><?= $color->name ?>
                                <a class='float-right' href='<?= base_url("colors/edit/{$color->id}") ?>'>
                                    <i class="material-icons">create</i>
                                </a>
                            </h4>
                            <h3 class='text-center'><?= $color->color ?>
                            </h3>
                            <p class='text-end'><?= $color->pantone ?>&nbsp;</p>
                        </span>
                    </div>
                <?php endforeach; ?>
            </div>
            <nav class="navbar fixed-bottom navbar-light bg-light">
                <div class="container-fluid">
                    <a class='btn btn-warning' href="<?= base_url('colors/create') ?>">Agregar nuevo color</a>
                    <nav aria-label="Page navigation example">
                        <ul class="pagination"></ul>
                    </nav>
                </div>
            </nav>
        </div>
    </body>
</html>