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


        //Styles da aplicacao
        echo $this->Html->css(array(
            'bootstrap.min.css',
            'bootstrap-responsive.min.css',
            'plugins/icheck/all.css',
            'style.css',
            'themes.css',
        ));

        //Scrips da aplicacao
        echo $this->Html->script(array(
            'jquery.min',
            'plugins/nicescroll/jquery.nicescroll.min',
            'plugins/placeholder/jquery.placeholder.min',
            'bootstrap.min',
            'eakroko',
        ));

        //Scripts dos plugins
        echo $this->Html->script(array(
            '/Main/js/main'
        ));


        echo $this->element('Layouts/ie-9');
        ?>

    </head>
    <body class='error'>