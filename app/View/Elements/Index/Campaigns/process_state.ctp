<?php 
if(!empty($this->params['pass'][0])){
    switch ($process_state) {
        case CAMPAIGN_NOT_PROCESSED:
            ?>
            <div class="alert alert-error">
                <p>
                    <strong>Atenção!</strong>
                </p>
                <p>
                    Estamos processando a sua campanha. Quando estiver completo, nós lhe enviaremos um e-mail. Você terá 7 dias para fazer o download.
                </p>
            </div>
            <?php
            break;
        case CAMPAIGN_RUN_PROCESSED:
            ?>
            <div class="alert alert-alert">
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
            ?>
            <div class="alert alert-success">
                <p>
                    <strong>Atenção!</strong>
                </p>
                <p>
                    A sua campanha já está pronta e disponível para download. 
                    Você tem X dias para baixar os arquivos gerados.
                </p>
            </div>
            <?php
            break;
    }
}
?>