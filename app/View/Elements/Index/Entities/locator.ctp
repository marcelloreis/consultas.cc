<?php 
// debug($locator);
?>
<div class="row-fluid">
    <div class="span12">
        <div class="box box-bordered box-color">
            <div class="box-title">
                <h3><i class="glyphicon-globe"></i> Localizador</h3>
            </div>
            <div class="box-content">
                <!-- Telefone 1 -->
                <?php foreach($locator as $k => $v):?>
                    <blockquote>
                        <h4>
                            <i class="glyphicon-clock"></i>
                            <?php 
                            $yearDiff = date('Y') - $v['Address']['year'];
                            switch ($yearDiff) {
                                case 0:
                                    $yearClass = 'btn-success';
                                    break;
                                case 1:
                                case 2:
                                    $yearClass = 'btn-warning';
                                    break;
                                default:
                                    $yearClass = 'btn-red';
                                    break;
                            }
                            echo sprintf('%s atualizado em %s', 'Endereço', $this->Html->link($v['Address']['year'], '#', array('class' => 'btn ' . $yearClass)));
                            ?>
                        </h4>
                        <form style="border: 1px solid #DDDDDD;" class="form-horizontal form-column form-bordered" method="POST" action="#">
                            <div class="control-group">
                                <label class="control-label"><?php echo !empty($v['Address']['type_address'])?$v['Address']['type_address']:'<small>Não Encontrado</small>';?></label>
                                <div class="controls">
                                    <?php echo !empty($v['Address']['street'])?$v['Address']['street']:'<small>Não Encontrado</small>';?>
                                </div>
                            </div>

                            <div class="span6">
                                <div class="control-group">
                                    <label class="control-label">Número</label>
                                    <div class="controls">
                                        <?php echo !empty($v['Address']['number'])?$v['Address']['number']:'<small>Não Encontrado</small>';?>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">Complemento</label>
                                    <div class="controls">
                                        <?php echo !empty($v['Address']['complement'])?$v['Address']['complement']:'<small>Não Encontrado</small>';?>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">Beirro</label>
                                    <div class="controls">
                                        <?php echo !empty($v['Address']['neighborhood'])?$v['Address']['neighborhood']:'<small>Não Encontrado</small>';?>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="span6">
                                <div class="control-group">
                                    <label class="control-label">CEP</label>
                                    <div class="controls">
                                        <?php echo !empty($v['Address']['zipcode'])?$v['Address']['zipcode']:'<small>Não Encontrado</small>';?>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">Estado</label>
                                    <div class="controls">
                                        <?php echo !empty($v['Address']['state'])?$v['Address']['state']:'<small>Não Encontrado</small>';?>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">Cidade</label>
                                    <div class="controls">
                                        <?php echo !empty($v['Address']['city'])?$v['Address']['city']:'<small>Não Encontrado</small>';?>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </blockquote>
                <?php endforeach?>

            </div>
        </div>
    </div>
</div>