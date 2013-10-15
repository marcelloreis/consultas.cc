<!-- client profile: -->
            <div class="panel customer">
                <div class="row panel-body">
                    <div class="col-lg-6 col-sm-4 col-xs-12 description-info">
                        <div class="inline-pic">
                            <?php echo $this->Html->image('avatar.jpg', array('alt' => 'cliente', 'width' => 110, 'height' => 110))?>
                        </div>
                        <h3>Leonardo Cabral Quadros</h3>
                        <p>leonardo@wine.com.br</p>
                        <p>
                            <strong>Código do cliente:&nbsp;</strong>135043 &nbsp;
                            <span class="label label-success">Cliente Wine ativo</span>
                        </p>
                        <p>
                            <strong>Data de cadastro:&nbsp;</strong>28/09/2010 às 10:36:23</p>
                    </div>
                    <div class="col-lg-6 col-sm-8 col-xs-12 pull-right">
                        <div class="row quick-info">
                            <div class="col-lg-2 col-xs-2 pull-right" title="Logar na loja">
                                <section class="panel shop-login text-center">
                                    <div class="panel-body">
                                        <i class="icon-share icon-2x"></i>
                                        <div>&nbsp;</div>
                                        <p>
                                            <span>Logar
                                                <br>na loja</span>
                                        </p>
                                    </div>
                                </section>
                            </div>
                            <div class="col-lg-2 col-xs-2 pull-right">
                                <section class="panel text-center">
                                    <div class="panel-body">
                                        <i class="icon-shopping-cart icon-2x text-success"></i>
                                        <div class="line m-l m-r"></div>
                                        <p class="h4">
                                            <strong>300,00</strong>
                                        </p>
                                        <p style="font-size:80%" class="">TKT Médio</p>
                                    </div>
                                </section>
                            </div>
                            <div class="col-lg-2 col-xs-2 pull-right">
                                <section class="panel text-center">
                                    <div class="panel-body">
                                        <i class="icon-meh icon-2x text-warning"></i>
                                        <div class="line m-l m-r"></div>
                                        <p class="h4">
                                            <strong>50</strong>
                                        </p>
                                        <p style="font-size:90%" class="">Satisfação</p>
                                    </div>
                                </section>
                            </div>
                            <div class="col-lg-2 col-xs-2 pull-right">
                                <section class="panel text-center">
                                    <div class="panel-body">
                                        <i class="icon-user icon-2x text-muted"></i>
                                        <div class="line m-l m-r"></div>
                                        <p class="h4">
                                            <strong>38</strong>
                                        </p>
                                        <p class="" style="font-size:90%">Anos</p>
                                    </div>
                                </section>
                            </div>
                            <div class="col-lg-2 col-xs-2 pull-right">
                                <section class="panel text-center">
                                    <div class="panel-body">
                                        <i class="icon-glass icon-2x text-muted"></i>
                                        <div class="line m-l m-r"></div>
                                        <p class="h4">
                                            <a href="">
                                                <strong>94</strong>
                                            </a>
                                        </p>
                                        <p style="font-size:90%" class="">
                                            <a href="">Pedidos</a>
                                        </p>
                                    </div>
                                </section>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- :client profile -->
            </div>
            <!-- app panel: -->

            <section class="customer-tab panel">
                <header class="panel-heading">
                    <ul class="nav nav-tabs">
                        <li class="active">
                            <a data-toggle="tab" href="#customer-data">
                                <i class="icon-user"></i>&nbsp;Cliente</a>
                        </li>
                        <li>
                            <a data-toggle="tab" href="#orders">
                                <i class="icon-shopping-cart"></i>&nbsp;Pedidos&nbsp;
                                <span class="badge">42</span>
                            </a>
                        </li>
                        <li>
                            <a data-toggle="tab" href="#clubew">
                                <i class="icon-star"></i>&nbsp;ClubeW&nbsp;
                                <span class="badge">2</span>
                            </a>
                        </li>
                        <li>
                            <a data-toggle="tab" href="#creditcards">
                                <i class="icon-credit-card"></i>&nbsp;Cartões&nbsp;
                                <span class="badge">2</span>
                            </a>
                        </li>
                        <li>
                            <a data-toggle="tab" href="#addresses">
                                <i class="icon-building"></i>&nbsp;Endereços&nbsp;
                                <span class="badge">2</span>
                            </a>
                        </li>
                        <li>
                            <a data-toggle="tab" href="#history">
                                <i class="icon-reorder"></i>&nbsp;Histórico&nbsp;
                                <span class="badge">6</span>
                            </a>
                        </li>
                    </ul>
                </header>
                <div class="row clearfix">
                    <div class="tab-content col-lg-12 col-sm-12 col-xs-12">
                        <div id="customer-data" class="tab-pane active">
                            <?php echo $this->element('Customers/customer');?>
                        </div>
                        <div id="orders" class="tab-pane">
                            <?php echo $this->element('Customers/orders');?>
                        </div>
                        <div id="clubew" class="tab-pane">
                            <?php echo $this->element('Customers/clubew');?>
                        </div>
                        <div id="creditcards" class="tab-pane">
                            <?php echo $this->element('Customers/cards');?>
                        </div>
                        <div id="addresses" class="tab-pane">
                            <?php echo $this->element('Customers/addresses');?>
                        </div>
                        <div id="history" class="tab-pane">
                            <?php echo $this->element('Customers/history');?>
                        </div>
                    </div>
                </div>
            </section>
            <!-- :app panel -->
