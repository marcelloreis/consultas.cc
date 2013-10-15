<!-- customer history panel: -->
<!--registro de atendimento:-->
<div id="history-panel" class="col-lg-4 col col-sm-6 col-xs-12">

    <section class="panel">
        <header class="panel-heading">Registrar nova Interação</header>
        <header class="panel-heading">
            <ul class="nav nav-tabs">
                <li class="h4 active">
                    <a data-toggle="tab" href="#intSRW">
                        <i class="icon-headphones icon-large"></i>&nbsp;SRW</a>
                </li>
                <li class="h4">
                    <a data-toggle="tab" href="#intCTW">
                        <i class="icon-truck icon-large"></i>&nbsp;CTW</a>
                </li>
            </ul>
        </header>

        <div class="panel-body">
            <div class="tab-content">
                <div class="tab-pane active" id="intSRW">
                    <form>
                        <ul class="list-group">
                            <li class="list-group-item">
                                <div class="media">
                                    <div class="media-body">
                                        <div class="media-label col-lg-4 col-sm-12 col-xs-12">
                                            <span class="h5">Canal:</span>
                                        </div>
                                        <div class="media-data col-lg-8 col-sm-12 col-xs-12">
                                            <div class="m-b">
                                                <!-- Obs.: O select2 pede definição da largura inline, via style -->
                                                <select class="select2" style="width:200px">
                                                    <option value="0">selecione</option>
                                                    <option value="0">Canal 1</option>
                                                    <option value="0">Canal 2</option>
                                                    <option value="0">Canal 3</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="list-group-item">
                                <div class="media">
                                    <div class="media-body">
                                        <div class="media-label col-lg-4 col-sm-12 col-xs-12">
                                            <span class="h5">Natureza:</span>
                                        </div>
                                        <div class="media-data col-lg-8 col-sm-12 col-xs-12">
                                            <div class="m-b">
                                                <!-- Obs.: O select2 pede definição da largura inline, via style -->
                                                <select class="select2" style="width:200px">
                                                    <option value="0">selecione</option>
                                                    <option value="0">Natureza 1</option>
                                                    <option value="0">Natureza 2</option>
                                                    <option value="0">Natureza 3</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="list-group-item">
                                <div class="media">
                                    <div class="media-body">
                                        <div class="media-label col-lg-4 col-sm-12 col-xs-12">
                                            <span class="h5">Motivo:</span>
                                        </div>
                                        <div class="media-data col-lg-8 col-sm-12 col-xs-12">
                                            <div class="m-b">
                                                <!-- Obs.: O select2 pede definição da largura inline, via style -->
                                                <select class="select2" style="width:200px">
                                                    <option value="0">selecione</option>
                                                    <option value="0">Motivo 1</option>
                                                    <option value="0">Motivo 2</option>
                                                    <option value="0">Motivo 3</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>

                            <li class="list-group-item">
                                <div class="media">
                                    <div class="media-body">

                                        <div class="media-label col-lg-4 col-sm-12 col-xs-12">
                                            <span class="h5">Relacionado a Pedido:</span>
                                        </div>
                                        <div class="media-data col-lg-8 col-sm-12 col-xs-12">

                                            <a class="btn btn-info btn-sm" data-toggle="class:show" data-target="#subpanel-pedidos">
                                                <span class="icon-shopping-cart">&nbsp;</span>SIM
                                            </a>
                                        </div>

                                    </div>
                                </div>

                                <div class="well padder-v m-t m-b-none noshow clearfix" id="subpanel-pedidos">

                                    <a id="ped-1001" class="btn btn-sm" data-toggle="class:btn-success" href="#ped-1001">
                                        <span class="icon-shopping-cart">&nbsp;</span>0001
                                    </a>
                                    <a id="ped-1002" class="btn btn-sm" data-toggle="class:btn-success" href="#ped-1002">
                                        <span class="icon-shopping-cart">&nbsp;</span>0002
                                    </a>

                                    <a id="ped-1003" class="btn btn-sm" data-toggle="class:btn-success" href="#ped-1003">
                                        <span class="icon-shopping-cart">&nbsp;</span>0003
                                    </a>

                                    <a id="ped-1004" class="btn btn-sm" data-toggle="class:btn-success" href="#ped-1004">
                                        <span class="icon-shopping-cart">&nbsp;</span>0004
                                    </a>

                                    <a id="ped-1005" class="btn btn-sm" data-toggle="class:btn-success" href="#ped-1005">
                                        <span class="icon-shopping-cart">&nbsp;</span>0005
                                    </a>

                                </div>
                            </li>

                        </ul>

                    </form>
                </div>

                <!-- interaçnao CTW:-->
                <div class="tab-pane" id="intCTW">
                    <form>
                        <ul class="list-group">
                            <li class="list-group-item">
                                <div class="media">
                                    <div class="media-body">
                                        <div class="media-label col-lg-4 col-sm-12 col-xs-12">
                                            <span class="h5">Assunto:</span>
                                        </div>
                                        <div class="media-data col-lg-8 col-sm-12 col-xs-12">
                                            <div class="m-b">
                                                <select class="select2" style="width:200px">
                                                    <option value="0">selecione</option>
                                                    <option value="0">Assunto 1</option>
                                                    <option value="0">Assunto 2</option>
                                                    <option value="0">Assuto 3</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="list-group-item">
                                <div class="media">
                                    <div class="media-body">
                                        <div class="media-label col-lg-4 col-sm-12 col-xs-12">
                                            <span class="h5">Status SRW:</span>
                                        </div>
                                        <div class="media-data col-lg-8 col-sm-12 col-xs-12">
                                            <div class="m-b">
                                                <!-- Obs.: O select2 pede definição da largura inline, via style -->
                                                <select class="select2" style="width:200px">
                                                    <option value="0">selecione</option>
                                                    <option value="0">status 1</option>
                                                    <option value="0">status 2</option>
                                                    <option value="0">status 3</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="list-group-item">
                                <div class="media">
                                    <div class="media-body">
                                        <div class="media-label col-lg-4 col-sm-12 col-xs-12">
                                            <span class="h5">Status CTW:</span>
                                        </div>
                                        <div class="media-data col-lg-8 col-sm-12 col-xs-12">
                                            <div class="m-b">
                                                <!-- Obs.: O select2 pede definição da largura inline, via style -->
                                                <select class="select2" style="width:200px">
                                                    <option value="0">selecione</option>
                                                    <option value="0">status 1</option>
                                                    <option value="0">status 2</option>
                                                    <option value="0">status 3</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>

                            <li class="list-group-item">
                                <div class="media">
                                    <div class="media-body">
                                        <div class="media-label col-lg-12 col-sm-12 col-xs-12">
                                            <span class="h5">Selecione pedido(s):</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="well padder-v m-t m-b-none clearfix" id="subpanel-pedidos">

                                    <a id="ped-0001" class="btn btn-sm" data-toggle="class:btn-success" href="#ped-0001">
                                        <span class="icon-shopping-cart">&nbsp;</span>0001
                                    </a>
                                    <a id="ped-0002" class="btn btn-sm" data-toggle="class:btn-success" href="#ped-0002">
                                        <span class="icon-shopping-cart">&nbsp;</span>0002
                                    </a>

                                    <a id="ped-0003" class="btn btn-sm" data-toggle="class:btn-success" href="#ped-0003">
                                        <span class="icon-shopping-cart">&nbsp;</span>0003
                                    </a>

                                    <a id="ped-0004" class="btn btn-sm" data-toggle="class:btn-success" href="#ped-0004">
                                        <span class="icon-shopping-cart">&nbsp;</span>0004
                                    </a>

                                    <a id="ped-0005" class="btn btn-sm" data-toggle="class:btn-success" href="#ped-0005">
                                        <span class="icon-shopping-cart">&nbsp;</span>0005
                                    </a>

                                </div>
                            </li>

                            <li class="list-group-item">
                                <div class="media">
                                    <div class="media-body">
                                        <div class="media-label col-lg-4 col-sm-12 col-xs-12">
                                            <span class="h5">Anexo:</span>
                                        </div>
                                        <div class="media-data col-lg-8 col-sm-12 col-xs-12">
                                            <div class="m-b">
                                                <div class="media-body">
                                                    <input type="file" name="file" title="Adicionar" class="btn btn-sm btn-primary "> <button class="btn btn-sm btn-default">Remover</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>

                        </ul>

                    </form>
                </div>
                <!--:interacao CTW -->
            </div>
        </div>
    </section>

</div>
<!--:registro de atendimento: -->

<!-- histórico de atendimento: -->
<div class="col-lg-8 col col-sm-6 col-xs-12">
    <section class="comment-list block panel">
        <header class="panel-heading">Interações Recentes
            <form class="form-inline pull-right" method="">
                <div class="form-group">Filtrar
                    <i class="icon-filter icon-large text-active"></i>
                    <select class="select2" style="width:160px; font-size:80%; font-weight:500">
                        <option value="0">Usuário</option>
                        <option value="0">Barabara Wels</option>
                        <option value="0">Marcio Neiler</option>
                        <option value="0">Raony Ramos</option>
                    </select>
                    <select class="select2" style="width:160px; font-size:80%; font-weight:500">
                        <option value="0">Nº Pedido</option>
                        <option value="0">11987</option>
                        <option value="0">11712</option>
                        <option value="0">940290</option>
                    </select>
                    <button class="btn btn-primary btn-sm" type="submit">filtrar</button>
                </div>
            </form>
        </header>
        <div class="panel-body">
            <article id="comment-id-1" class="comment-item media arrow arrow-left">
                <a class="pull-left thumb-small avatar">
                    <?php echo $this->Html->image('avatar.jpg', array('alt' => 'nome do wineano', 'class' => 'img-circle'))?>
                </a>
                <section class="media-body panel">
                    <header class="panel-heading clearfix">
                        <a href="#user">
                            <strong>Marcio Neiler</strong>
                        </a>
                        <label class="label label-info"><i class="icon-truck"></i> CTW</label>&nbsp;referente ao pedido:
                        <a class="label label-default" href="#linkpedido">
                            <i class="icon-shopping-cart">&nbsp;</i>&nbsp;11712</a>
                        <small class="text-muted m-l-small pull-right lh-18">
                            <i class="icon-calendar">&nbsp;</i>&nbsp;19/08/2013
                            <i class="icon-time">&nbsp;</i>&nbsp;10:35</small>
                    </header>
                    <div class="panel-body">
                        <p class="col-lg-6 col-xs-6">
                            <strong>Canal:</strong>
                            <span><i class="icon-info-sign"></i>&nbsp;Comunicação interna</span>
                        </p>
                        <p class="col-lg-6 col-xs-6">
                            <strong>Natureza:</strong>
                            <span>Entrega</span>
                        </p>
                        <p class="col-lg-6 col-xs-6">
                            <strong>Motivo:</strong>
                            <span>Posição de entrega</span>
                        </p>
                        <p class="col-lg-6 col-xs-6">
                            <strong>Loja:</strong>
                            <span class="label label-primary">Wine</span>
                        </p>
                        <p class="col-lg-12 col-xs-12">Informo que o referido pedido foi entregue a transportadora
                            <a data-original-title="colocar string para sistema da TAM já com o código de rastreamento e login do usuário " data-toggle="tooltip" class="label label-default" href="linkTam">TAM</a>&nbsp;no dia 18/08/2013 as 08:50.</p>
                        <small class="col-lg-12 col-xs-12 text-warning">
                            <span class="icon-warning-sign">&nbsp;</span>&nbsp;Necessita de pós atendimento</small>
                    </div>
                </section>
            </article>
            <article id="comment-id-1" class="comment-item media arrow arrow-left">
                <a class="pull-left thumb-small avatar">
                    <?php echo $this->Html->image('avatar.jpg', array('alt' => 'nome do wineano', 'class' => 'img-circle'))?>
                </a>
                <section class="media-body panel">
                    <header class="panel-heading clearfix">
                        <a href="#user">
                            <span>Barbara Wels</span>
                        </a>
                        <label class="label label-info"><i class="icon-headphones"></i> SRW</label>&nbsp;referente ao pedido:
                        <a class="label label-default" href="#linkpedido">
                            <i class="icon-shopping-cart">&nbsp;</i>&nbsp;11712</a>
                        <small class="text-muted m-l-small pull-right lh-18">
                            <i class="icon-calendar">&nbsp;</i>&nbsp;19/08/2013
                            <i class="icon-time">&nbsp;</i>&nbsp;10:10</small>
                    </header>
                    <div class="panel-body">
                        <p class="col-lg-6 col-xs-6">
                            <strong>Canal:</strong>
                            <span><i class="icon-comments-alt"></i>&nbsp;Chat</span>
                        </p>
                        <p class="col-lg-6 col-xs-6">
                            <strong>Natureza:</strong>
                            <span>Entrega</span>
                        </p>
                        <p class="col-lg-6 col-xs-6">
                            <strong>Motivo:</strong>
                            <span>Atraso</span>
                        </p>
                        <p class="col-lg-6 col-xs-6">
                            <strong>Loja:</strong>
                            <span class="label label-primary">Wine</span>
                        </p>
                        <p class="col-lg-12 col-xs-12">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer posuere erat a ante.</p>
                        <small class="col-lg-12 col-xs-12 text-warning">
                            <span class="icon-warning-sign">&nbsp;</span>&nbsp;Necessita de pós atendimento</small>
                    </div>
                </section>
            </article>
            <article id="comment-id-1" class="comment-item media arrow arrow-left">
                <a class="pull-left thumb-small avatar">
                    <?php echo $this->Html->image('avatar.jpg', array('alt' => 'nome do wineano', 'class' => 'img-circle'))?>
                </a>
                <section class="media-body panel">
                    <header class="panel-heading clearfix">
                        <a href="#user">
                            <span>Raony Ramos</span>
                        </a>
                        <label class="label label-info"><i class="icon-headphones"></i> SRW</label>&nbsp;referente ao pedido:
                        <a href="#linkpedido" class="label label-default">
                            <i class="icon-shopping-cart">&nbsp;</i>&nbsp;11987</a>
                        <small class="text-muted m-l-small pull-right lh-18">
                            <i class="icon-calendar">&nbsp;</i>&nbsp;22/08/2013
                            <i class="icon-time">&nbsp;</i>&nbsp;11:25</small>
                    </header>
                    <div class="panel-body">
                        <p class="col-lg-6 col-xs-6">
                            <strong>Canal:</strong>
                            <span><i class="icon-comments-alt"></i>&nbsp;Chat</span>
                        </p>
                        <p class="col-lg-6 col-xs-6">
                            <strong>Natureza:</strong>
                            <span>Entrega</span>
                        </p>
                        <p class="col-lg-6 col-xs-6">
                            <strong>Motivo:</strong>
                            <span>Atraso</span>
                        </p>
                        <p class="col-lg-6 col-xs-6">
                            <strong>Loja:</strong>
                            <span class="label label-primary">Wine</span>
                        </p>
                        <p class="col-lg-12 col-xs-12">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer posuere erat a ante.</p>
                        <small class="col-lg-12 col-xs-12 text-warning">
                            <span class="icon-warning-sign">&nbsp;</span>&nbsp;Necessita de pós atendimento</small>
                    </div>
                </section>
            </article>
        </div>
        <div class="col-lg-12 text-right">
            <ul class="pagination pagination-sm">
                <li>
                    <a href="#">
                        <i class="icon-chevron-left"></i>
                    </a>
                </li>
                <li>
                    <a href="#">1</a>
                </li>
                <li>
                    <a href="#">2</a>
                </li>
                <li>
                    <a href="#">3</a>
                </li>
                <li>
                    <a href="#">4</a>
                </li>
                <li>
                    <a href="#">5</a>
                </li>
                <li>
                    <a href="#">
                        <i class="icon-chevron-right"></i>
                    </a>
                </li>
            </ul>
        </div>
    </section>
</div>
<!--:histórico de atendimento:-->
<!-- :customer history panel -->
