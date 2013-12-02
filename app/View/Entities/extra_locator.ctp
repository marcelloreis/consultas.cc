                <!-- Telefone 1 -->
                <form style="border: 1px solid #DDDDDD;" class="form-horizontal form-column form-bordered" method="POST" action="#">
                    <?php foreach($entity as $k => $v):?>

                        <!-- Divisoria -->
                        <div class="control-group"></div>

                        <div class="control-group">
                            <label style="width:100%" class="control-label">Atualizado em <?php echo $this->element('Index/Entities/tag-year', array('year' => $v['Association'][0]['year']))?></label>
                        </div>

                        <div class="control-group">
                            <label class="control-label"><?php echo !empty($v['Address']['type_address'])?$v['Address']['type_address']:$this->element('Components/Entities/notfound');?></label>
                            <div class="controls">
                                <?php echo !empty($v['Address']['street'])?$v['Address']['street']:$this->element('Components/Entities/notfound');?>
                            </div>
                        </div>

                        <div class="span6">
                            <div class="control-group">
                                <label class="control-label">Número</label>
                                <div class="controls">
                                    <?php echo !empty($v['Address']['number'])?$v['Address']['number']:$this->element('Components/Entities/notfound');?>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">Complemento</label>
                                <div class="controls">
                                    <?php echo !empty($v['Address']['complement'])?$v['Address']['complement']:$this->element('Components/Entities/notfound');?>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">Bairro</label>
                                <div class="controls">
                                    <?php echo !empty($v['Address']['neighborhood'])?$v['Address']['neighborhood']:$this->element('Components/Entities/notfound');?>
                                </div>
                            </div>
                        </div>

                        <div class="span6">

                            <div class="control-group">
                                <label class="control-label">CEP</label>
                                <div class="controls">
                                    <?php echo !empty($v['Address']['zipcode'])?$this->Html->link($this->AppUtils->zipcode($v['Address']['zipcode']), array('action' => 'address', 'zipcode' => $v['Address']['zipcode'])):$this->element('Components/Entities/notfound');?>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">Estado</label>
                                <div class="controls">
                                    <?php echo !empty($v['Address']['state'])?$v['Address']['state']:$this->element('Components/Entities/notfound');?>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">Cidade</label>
                                <div class="controls">
                                    <?php echo !empty($v['Address']['city'])?$v['Address']['city']:$this->element('Components/Entities/notfound');?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach?>
                </form>