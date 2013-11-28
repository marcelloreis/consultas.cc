<div id="entity-main" class="row-fluid">
    <div class="span12">
        <div class="box box-bordered box-color">
            <div class="box-title">
                <h3>
                    <i class="glyphicon-vcard"></i>
                    <?php echo __('Document Data')?>
                </h3>
            </div>
            <div class="box-content nopadding">
                <form class="form-horizontal form-column form-bordered" method="POST" action="#">
                    <?php 
                    if(!count($entity)){
                        echo $this->element('Components/Entities/flash-message', array('message' => __('No record found')));
                    }else{
                        switch ($entity['Entity']['type']) {
                            case TP_CPF:
                            case TP_AMBIGUO:
                            case TP_INVALID:
                                echo $this->element('Index/Entities/main-cpf');
                                break;
                            case TP_CNPJ:
                                echo $this->element('Index/Entities/main-cnpj');
                                break;
                        }
                    }
                    ?>                   
                </form>
            </div>
        </div>
    </div>
</div>