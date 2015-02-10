<?php
$curr_tab = 'basic';
if(isset($_GET['tab'])) {
    $curr_tab = $_GET['tab'];
}
echo yet_upyun_admin_tabs($curr_tab);

include $curr_tab . '.php';
