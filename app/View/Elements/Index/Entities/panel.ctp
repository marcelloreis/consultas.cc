<div class="row-fluid">
    <div class="span12">
        <div class="box box-bordered">
            <div class="box-title">
                <h3>
                    <i class="glyphicon-search"></i>
                    <?php echo __('Searches')?>
                </h3>
                <ul class="tabs">
                    <li class="active">
                        <a href="#t1" data-toggle="tab"><?php echo __('By Document')?></a>
                    </li>
                    <li>
                        <a href="#t2" data-toggle="tab"><?php echo __('By Name')?></a>
                    </li>
                    <li>
                        <a href="#t3" data-toggle="tab"><?php echo __('By Address')?></a>
                    </li>
                    <li>
                        <a href="#t4" data-toggle="tab"><?php echo __('By Telephone')?></a>
                    </li>
                </ul>
            </div>
            <div class="box-content nopadding">
                <div class="tab-content">
                    <div class="tab-pane active" id="t1">
                        <?php echo $this->AppForm->create($modelClass, array('defaultSize' => 'input-xlarge', 'classForm' => 'form-horizontal form-bordered'))?>
                            <?php echo $this->form->hidden('q', array('value' => $requestHandler));?>
                            <?php echo $this->form->hidden('Entity.search_type', array('value' => 'doc'));?>
                            <div class="control-group">
                                <label class="control-label" for="textfield"><?php echo __('Type Document')?></label>
                                <div class="controls">
                                    <div class="input-append input-prepend">
                                        <span class="add-on"><i class="icon-search"></i></span>
                                        <?php $search = isset($this->params['named']['search']) && !empty($this->params['named']['search'])?$this->params['named']['search']:''?>
                                        <?php echo $this->AppForm->input('search', array('template' => 'form-input-clean', 'value' => $search, 'placeholder' => __('type document')))?>
                                        <button type="submit" class="btn"><?php echo __('Search')?></button>
                                    </div>
                                </div>
                            </div>
                        <?php echo $this->AppForm->end()?>
                    </div>
                    <div class="tab-pane" id="t2">
                        <?php echo $this->AppForm->create($modelClass, array('defaultSize' => 'input-xlarge', 'classForm' => 'form-horizontal form-bordered'))?>
                            <?php echo $this->form->hidden('q', array('value' => $requestHandler));?>
                            <?php echo $this->form->hidden('search_type', array('value' => 'name'));?>
                            <div class="control-group">
                                <label class="control-label" for="textfield"><?php echo __('Type Name')?></label>
                                <div class="controls">
                                    <div class="input-append input-prepend">
                                        <span class="add-on"><i class="icon-search"></i></span>
                                        <?php $search = isset($this->params['named']['search']) && !empty($this->params['named']['search'])?$this->params['named']['search']:''?>
                                        <?php echo $this->AppForm->input('search', array('template' => 'form-input-clean', 'value' => $search, 'classInput' => 'tagsinput', 'placeholder' => __('type name')))?>
                                        <button type="submit" class="btn"><?php echo __('Search')?></button>
                                    </div>
                                </div>
                            </div>
                        <?php echo $this->AppForm->end()?>
                    </div>
                    <div class="tab-pane" id="t3">
                        <h4>Another tab</h4>
                        Lorem ipsum commodo dolor sit in sint anim ad ut non et. Lorem ipsum cillum ex sunt ea irure Ut dolore in labore officia nostrud in anim culpa sit esse. Lorem ipsum elit Duis magna et voluptate Duis non pariatur esse laboris nisi laborum nulla. Lorem ipsum et tempor ea ad in id consectetur incididunt velit Excepteur officia. Lorem ipsum non consectetur Excepteur commodo aute anim sunt. Lorem ipsum pariatur esse nulla mollit Duis ex. Lorem ipsum cillum sit in ad consequat in ad enim incididunt ea laborum pariatur Excepteur aliqua nostrud ut. Lorem ipsum et magna laboris reprehenderit mollit reprehenderit aute Duis aliquip officia nulla. Lorem ipsum dolor Ut dolore in laborum elit dolore quis mollit ut sit Excepteur aute. Lorem ipsum quis et eiusmod in irure tempor ea labore cillum dolore labore eiusmod in occaecat qui ea. Lorem ipsum dolor fugiat deserunt incididunt eiusmod sunt magna reprehenderit sed enim ut cillum. Lorem ipsum irure pariatur exercitation sunt eiusmod dolore Ut do ut ut minim. Lorem ipsum do ea pariatur in anim deserunt Excepteur nisi culpa nisi aliquip culpa veniam ut non. 
                    </div>
                    <div class="tab-pane" id="t4">
                        <h4>Another tab</h4>
                        Lorem ipsum commodo dolor sit in sint anim ad ut non et. Lorem ipsum cillum ex sunt ea irure Ut dolore in labore officia nostrud in anim culpa sit esse. Lorem ipsum elit Duis magna et voluptate Duis non pariatur esse laboris nisi laborum nulla. Lorem ipsum et tempor ea ad in id consectetur incididunt velit Excepteur officia. Lorem ipsum non consectetur Excepteur commodo aute anim sunt. Lorem ipsum pariatur esse nulla mollit Duis ex. Lorem ipsum cillum sit in ad consequat in ad enim incididunt ea laborum pariatur Excepteur aliqua nostrud ut. Lorem ipsum et magna laboris reprehenderit mollit reprehenderit aute Duis aliquip officia nulla. Lorem ipsum dolor Ut dolore in laborum elit dolore quis mollit ut sit Excepteur aute. Lorem ipsum quis et eiusmod in irure tempor ea labore cillum dolore labore eiusmod in occaecat qui ea. Lorem ipsum dolor fugiat deserunt incididunt eiusmod sunt magna reprehenderit sed enim ut cillum. Lorem ipsum irure pariatur exercitation sunt eiusmod dolore Ut do ut ut minim. Lorem ipsum do ea pariatur in anim deserunt Excepteur nisi culpa nisi aliquip culpa veniam ut non. 
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>