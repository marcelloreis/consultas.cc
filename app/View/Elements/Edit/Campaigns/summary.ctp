<div class="control-group">
    <label class="control-label">Pessoas encontradas na lista telefônica</label>
        <div class="controls">

            <div class="row-fluid">
                <div class="span12">
                    <ul class="stats">
                        <li class="satblue">
                            <i class="glyphicon-group" style="margin-top:10px;"></i>
                            <div class="details">
                                <span class="big"><?php echo $this->AppUtils->num2qt($this->request->data['SmsCampaign']['people'])?></span>
                                <span>Pessoas</span>
                            </div>
                        </li>

                        <li class="pink">
                            <i class="glyphicon-woman" style="margin-top:10px;"></i>
                            <div class="details">
                                <span class="big"><?php echo $this->AppUtils->num2qt($this->request->data['SmsCampaign']['female'])?></span>
                                <span>Feminino</span>
                            </div>
                        </li>
                        
                        <li class="blue">
                            <i class="glyphicon-old_man" style="margin-top:10px;"></i>
                            <div class="details">
                                <span class="big"><?php echo $this->AppUtils->num2qt($this->request->data['SmsCampaign']['male'])?></span>
                                <span>Masculino</span>
                            </div>
                        </li>
                        
                        <li class="lightgrey">
                            <i class="glyphicon-user" style="margin-top:10px;"></i>
                            <div class="details">
                                <span class="big"><?php echo $this->AppUtils->num2qt($this->request->data['SmsCampaign']['individual'])?></span>
                                <span>Pessoas Físicas</span>
                            </div>
                        </li>
                        
                        <li class="lightgrey">
                            <i class="glyphicon-building" style="margin-top:10px;"></i>
                            <div class="details">
                                <span class="big"><?php echo $this->AppUtils->num2qt($this->request->data['SmsCampaign']['corporation'])?></span>
                                <span>Pessoas Jurídicas</span>
                            </div>
                        </li>
                        
                    </ul>
                </div>
            </div>

        </div>
</div>
