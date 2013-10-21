<div class="row-fluid">
    <div class="span12">
        <div class="box box-bordered box-color">
            <div class="box-title">
                <h3>
                    <i class="glyphicon-vcard"></i>
                    <?php echo __d('app', 'Document Data')?>
                </h3>
            </div>
            <div class="box-content nopadding">
                <form class="form-horizontal form-column form-bordered" method="POST" action="#">
                    <?php 
                    switch ($people['Entity']['type']) {
                        case TP_CPF:
                        case TP_AMBIGUO:
                        case TP_INVALID:
                            echo $this->element('Index/Entities/people-cpf');
                            break;
                        case TP_CNPJ:
                            echo $this->element('Index/Entities/people-cnpj');
                            break;
                    }
                    ?>                    
                </form>
            </div>
        </div>
    </div>
</div>