<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<!DOCTYPE html>
<html lang="en">
    <?=$head?>
    <body>
	    <?= $nav ?>
	    <div class="container">
            <div class='row'>
                <div class='col-md-12 mt-4'>
                    <form class='row' id='colorForm' action='<?= $action ?>' method='POST'>
                        <?= isset($stored->id) ? "<input type='hidden' value='{$stored->id}' name='id'/>"  : '' ?>
                        <span class='col-md-6'>
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input id="name" class="form-control" type="text" name="name" required <?= (isset($stored) AND isset($stored->name)) ? "value='{$stored->name}'" : '' ?> >
                            </div>
                        </span>
                        <span class='col-md-6'>
                            <div class="form-group">
                                <label for="color">Color</label>
                                <input id="color" class="form-control" type="color" name="color" <?= (isset($stored) AND isset($stored->color)) ? "value='{$stored->color}'" : '' ?> >
                            </div>
                        </span>
                        <span class='col-md-6'>
                            <div class="form-group">
                                <label for="pantone">Pantone</label>
                                <input id="pantone" class="form-control" type="text" name="pantone" required <?= (isset($stored) AND isset($stored->pantone)) ? "value='{$stored->pantone}'" : '' ?> >
                            </div>
                        </span>
                        <span class='col-md-6'>
                            <div class="form-group">
                                <label for="year">Year</label>
                                <input id="year" class="form-control" type="number" name="year" min='1960' max='2021' required <?= (isset($stored) AND isset($stored->year)) ? "value='{$stored->year}'" : '' ?> >
                            </div>
                        </span>
                    </span>
                </div>
            </div>
        </div>
        <nav class="navbar fixed-bottom navbar-light bg-light">
            <div class="container-fluid">
                <button form='colorForm' type='submit' class='btn btn-primary'><?= $action_btn ?></button>
                <a href='<?= base_url("/colors") ?>'>Regresar</a>
                <?php if($can_delete) : ?>
                    <button type='button' onclick='colorForm.delete(<?= $stored->id ?>)' class='btn btn-danger'>Borrar</button>
                <?php endif ?>
            </div>
        </nav>
    </body>
</html>