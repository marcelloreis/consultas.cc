<?php 
if(!empty($this->params['pass'][0])){
    switch ($process_state) {
        case CAMPAIGN_NOT_PROCESSED:
            ?>
            <div class="alert alert-info">
                <p>
                    <strong>Estamos processando a sua campanha!</strong>
                </p>
                <p>
                    Quando finalizarmos, nós enviaremos um e-mail para <strong><?php $this->data['User']['email']?></strong>. 
                    Você terá <?php echo CAMPAIGN_VALIDITY?> dias para fazer o download.
                </p>
            </div>
            <?php
            break;
        case CAMPAIGN_RUN_PROCESSED:
            ?>
            <div class="alert alert-warning">
                <p>
                    <strong>Atenção!</strong>
                </p>
                <p>
                    Sua campanha esta sendo processada neste exato momento, 
                    dentro de instantes você receberá um email com instruções de como baixar os arquivos gerados.
                </p>
            </div>
            <?php
            break;
        case CAMPAIGN_PROCESSED:
        case CAMPAIGN_DOWNLOADED:
            /**
            * Calcula os dias disponiveis para fazer o download da campanha
            */  
            $remaing = CAMPAIGN_VALIDITY;
            if(!empty($this->data['Campaign']['elapsed'])){
                $remaing -= $this->data['Campaign']['elapsed'];
            }
            ?>

                <?php if($remaing < 0):?>
                <div class="alert alert-error">
                    <p>
                        <strong>Expirado!</strong>
                    </p>
                    <p>
                        Infelizmente o prazo para baixar os arquivos expirou. <br>
                        Para processar novamente a campanha clique aqui: <?php echo $this->Html->link('Recarregar', array('action' => 'reload', $this->data['Campaign']['id']), array(), "Tem certeza que deseja recarregar esta campanha?")?>
                    </p>
                    <p><strong>AO RECARREGAR, A CAMPANHA SERA TARIFADA NOVAMENTE COM OS VALORES VIGENTES.</strong></p>
                </div>
                <?php else:?>
                    <div class="alert alert-success">
                        <p>
                            <strong>A sua campanha já está pronta e disponível para baixar.</strong>
                        </p>
                        <?php if($remaing === 0):?>
                            Só restam algumas horas para baixar os arquivos gerados.
                        <?php else:?>
                            Você tem <strong><?php echo $remaing?></strong> dias para baixar os arquivos gerados.
                        <?php endif?>

                        <p>Para obter os arquivos gerados, clique aqui: <?php echo $this->Html->link($this->data['Campaign']['download_link'])?></p>
                    </div>
                <?php endif?>
            <?php
            break;
    }
}
?>