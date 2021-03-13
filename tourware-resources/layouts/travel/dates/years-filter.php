<?php ?>
<div class="years-filter">
    <a class="active" href="#" data-year="">Alle</a>
    <?php foreach ($years as $year) {
        echo '<a href="#" data-year="'.$year.'">'.$year.'</a>';
    } ?>
</div>
