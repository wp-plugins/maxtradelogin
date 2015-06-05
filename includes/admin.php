<?php

/** add admin menu */
function mtl_admin_actions() {
    add_options_page(__( 'MaxtradeLogin options page', 'mtltextdomain' ), __( 'MTL Options', 'mtltextdomain' ), 1, "mtl-options", "mtl_admin_options");
}

?>