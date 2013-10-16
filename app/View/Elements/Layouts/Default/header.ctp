<?php echo $this->Html->docType('html5');?>
<html lang="pt-br">
    <head>
        <title><?php echo TITLE_HEADER?></title>
        <?php
        echo $this->Html->charset();

        //Favicon
        echo $this->Html->meta('icon', $this->Html->url('/img/favicon.png'));

        /**
        * Metas
        */
        echo $this->Html->meta(array('name' => 'base_url', 'content' => $this->Html->url('/', true)));
        echo $this->Html->meta(array('name' => 'viewport', 'content' => 'width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no'));
        //Apple devices fullscreen
        echo $this->Html->meta(array('name' => 'apple-mobile-web-app-capable', 'content' => 'yes'));
        //Apple devices fullscreen
        echo $this->Html->meta(array('name' => 'apple-mobile-web-app-status-bar-style', 'content' => 'black-translucent'));

        echo $this->Html->meta(array('name' => 'description', 'content' => ''));
        echo $this->Html->meta(array('name' => 'keywords', 'content' => ''));
        echo $this->Html->meta(array('name' => 'author', 'content' => ''));
        echo $this->Html->meta(array('name' => 'robots', 'content' => 'index,follow'));
        echo $this->Html->meta(array('name' => 'content-language', 'content' => 'pt-br'));

        /**
        * Styles da aplicacao
        */
        echo $this->Html->css(array(
            'bootstrap.min.css',
            'bootstrap-responsive.min.css',
            'plugins/jquery-ui/smoothness/jquery-ui',
            'plugins/jquery-ui/smoothness/jquery.ui.theme',
            'plugins/pageguide/pageguide',
            'plugins/fullcalendar/fullcalendar',
            'plugins/fullcalendar/fullcalendar.print',
            'plugins/chosen/chosen',
            'plugins/select2/select2',
            'plugins/icheck/all.css',
            'style.css',
            'themes.css',
        ));

        /**
        * Scrips da aplicacao
        */
        echo $this->Html->script(array(
            'jquery.min',
            'plugins/nicescroll/jquery.nicescroll.min',
            
            'plugins/jquery-ui/jquery.ui.core.min',
            'plugins/jquery-ui/jquery.ui.widget.min',
            'plugins/jquery-ui/jquery.ui.mouse.min',
            'plugins/jquery-ui/jquery.ui.draggable.min',
            'plugins/jquery-ui/jquery.ui.resizable.min',
            'plugins/jquery-ui/jquery.ui.sortable.min',

            'plugins/touch-punch/jquery.touch-punch.min',
            'plugins/slimscroll/jquery.slimscroll.min',

            'bootstrap.min',

            'plugins/vmap/jquery.vmap.min',
            'plugins/vmap/jquery.vmap.world',
            'plugins/vmap/jquery.vmap.sampledata',

            'plugins/bootbox/jquery.bootbox',

            'plugins/flot/jquery.flot.min',
            'plugins/flot/jquery.flot.bar.order.min',
            'plugins/flot/jquery.flot.pie.min',
            'plugins/flot/jquery.flot.resize.min',

            'plugins/imagesLoaded/jquery.imagesloaded.min',

            'plugins/pageguide/jquery.pageguide',

            'plugins/fullcalendar/fullcalendar.min',

            'plugins/chosen/chosen.jquery.min',

            'plugins/select2/select2.min',

            'plugins/icheck/jquery.icheck.min',

            'eakroko.min',

            'application.min',

            'demonstration.min',

            'plugins/validation/jquery.validate.min',
            'plugins/validation/additional-methods.min',
        ));

        //Scripts dos plugins
        echo $this->Html->script(array(
            '/Main/js/main'
        ));


        echo $this->element('Layouts/ie-9');
        ?>

    </head>
    <body data-layout-sidebar="fixed" data-layout-topbar="fixed" class="theme-satblue">