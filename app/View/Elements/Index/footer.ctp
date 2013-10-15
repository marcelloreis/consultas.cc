<footer class="panel-footer">
    <div class="row">
        <div class="col-sm-4 hidden-xs">
            <select style="width:130px" class="input-sm inline form-control">
                <option value="0">Ação em lote</option>
                <option value="1">Apagar selecionados</option>
                <option value="2">Edição em lote</option>
                <option value="3">Exportar</option>
            </select>
            <button class="btn btn-sm btn-white">Aplicar</button>
        </div>
        <div class="col-sm-3 text-center">
            <small class="text-muted inline m-t-small m-b-small"><?php //$this->Paginator->counter('Página {:page} de {:pages}, exibindo {:current} registros do total de {:count}, começando pelo registro {:start} até o {:end}')?></small>
        </div>

        <?php if(isset($paginate) && $paginate):?>
            <div class="col-sm-5 text-right text-center-sm">
                <ul class="pagination pagination-small m-t-none m-b-none">
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
        <?php endif?>
    </div>
</footer>