
<?php
use \FMT\Helper\Template;
use \FMT\Helper\Arr;
use FMT\Vista;
    $template = (new \FMT\Template(VISTAS_PATH.'/templates/cron/presentacion_general.html', $vars_template,['CLEAN'=>true]));    

    return $template;
?>
