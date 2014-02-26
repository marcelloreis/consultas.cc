<?php echo $this->Html->docType('html5');?>
<html lang="pt-br">
    <head>
        <title><?php echo TITLE_HEADER?></title>
        <?php
        echo $this->Html->charset();

        /**
        * Styles da aplicacao
        */
        echo $this->Html->css(array(
            'all-download.min',
        ));

        if(empty($theme)){
            $theme = !empty($userLogged['theme'])?$userLogged['theme']:THEME;
        }
        ?>
    </head>
    <body style="font-size: 9px !important;" data-theme="<?php echo $theme?>" class="<?php echo $theme?>">
