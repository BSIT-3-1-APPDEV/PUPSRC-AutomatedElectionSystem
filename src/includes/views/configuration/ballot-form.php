<?php

// var_dump($_SERVER);

?>
<main class="main">
    <?php
    global $configuration_pages;
    global $link_name;
    $secondary_nav = new SecondaryNav($configuration_pages, $link_name, true);
    $secondary_nav->getNavLink();
    ?>

</main>