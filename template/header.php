<?php
$curr_tab = 'basic';
if(isset($_GET['tab'])) {
    $curr_tab = $_GET['tab'];
}
echo yet_upyun_admin_tabs($curr_tab);
//todo white list check
$templatePath = YET_UPYUN_TEMPLATE_DIR . "/$curr_tab.php";
if(! file_exists($templatePath)) {
    include YET_UPYUN_TEMPLATE_DIR . '/basic.php';
} else {
    include $templatePath;
}