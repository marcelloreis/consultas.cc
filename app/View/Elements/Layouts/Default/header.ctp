<?php echo $this->Html->docType('html5');?>
<html lang="pt-br">
    <head>
        <title><?php echo TITLE_HEADER?></title>
        <?php
        echo $this->Html->charset();

        //Favicon
        echo $this->Html->meta('icon', $this->Html->url('/img/logo.png'));

        // Metas
        echo $this->Html->meta(array('name' => 'base_url', 'content' => $this->Html->url('/', true)));
        echo $this->Html->meta(array('name' => 'viewport', 'content' => 'width=device-width, initial-scale=1, maximum-scale=1'));
        echo $this->Html->meta(array('name' => 'description', 'content' => ''));
        echo $this->Html->meta(array('name' => 'keywords', 'content' => ''));
        echo $this->Html->meta(array('name' => 'author', 'content' => ''));
        echo $this->Html->meta(array('name' => 'robots', 'content' => 'index,follow'));
        echo $this->Html->meta(array('name' => 'content-language', 'content' => 'pt-br'));

        //Reservado para a inserção do CSS da pagina de login
        echo $this->fetch('css-login');

        //Styles da aplicacao
        echo $this->Html->css(array(
            'bootstrap',
            'font-awesome.min',
            'plugin',
            'style',
            'select2/select2',
            'sprIco16',
            'sprIco32',
            'landing',
            'spr-log',
        ));

        //Scrips da aplicacao
        echo $this->Html->script(array(
            'jquery.min',
            'bootstrap',
            'fuelux/fuelux',
            'datepicker/bootstrap-datepicker',
            'file-input/bootstrap.file-input',
            'combodate/moment.min',
            'combodate/combodate',
            'parsley/parsley.min',
            'select2/select2.min',
            'datatables/jquery.dataTables.min',
            'app',
            'app.plugin',
            'app.data',
        ));

        //Reservado para a inserção do Scripts da pagina de login
        // echo $this->fetch('js-login');

        //Scripts dos plugins
        echo $this->Html->script(array(
            '/Main/js/main'
        ));

        //Imprime todas as mensagem que que estiverem fora dos templates padroes da aplicação
        echo __($this->Session->flash(), true);
        ?>

    </head>
    <body>