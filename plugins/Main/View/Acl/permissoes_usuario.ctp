<div class="panel">
    <header class="panel-heading">Permissões de usuários</header>
    <div class="panel-body">
        <div class="row text-small">
            <div class="col-sm-4 m-b-mini">
                <select style="width:130px" class="input-sm inline form-control">
                    <option value="0">Ação em lote</option>
                    <option value="1">Apagar selecionados</option>
                    <option value="2">Edição em lote</option>
                    <option value="3">Exportar</option>
                </select>
                <button class="btn btn-sm btn-white">Aplicar</button>
            </div>
            <div class="col-sm-4 m-b-mini">
                <div data-toggle="buttons" class="btn-group">
                    <label class="btn btn-sm btn-white active">
                        <input type="radio" id="option1" name="options">Dia</label>
                    <label class="btn btn-sm btn-white">
                        <input type="radio" id="option2" name="options">Semana</label>
                    <label class="btn btn-sm btn-white">
                        <input type="radio" id="option2" name="options">Mês</label>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="input-group">
                    <input type="text" class="input-sm form-control">
                    <span class="input-group-btn">
                        <button type="button" class="btn btn-sm btn-white">Buscar</button>
                    </span>
                </div>
            </div>
        </div>
    </div>
    <div>
        <table class="table table-striped b-t">
            <thead>
                <tr>
                    <th width="20">
                        <input type="checkbox">
                    </th>
                    <th colspan="2">Usuário</th>
                    <th>E-mail</th>
                    <th>grupo</th>
                    <th>Status</th>
                    <th>Opções</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <input type="checkbox" value="1" name="post[]">
                    </td>
                    <td width="10%">(imagem)</td>
                    <td>Leonardo Cabral</td>
                    <td>leonardo@gmail.com</td>
                    <td>Administrador</td>
                    <td class="text-center">
                        <span class="label label-success">ativo</span>
                    </td>
                    <td>
                        <a data-toggle="class" class="active" href="#">
                            <i class="icon-ok icon-large text-success text-active"></i>
                            <i class="icon-remove icon-large text-danger text"></i>
                        </a>
                    </td>
                </tr>
                <tr>
                    <td>
                        <input type="checkbox" value="2" name="post[]">
                    </td>
                    <td width="10%">(imagem)</td>
                    <td>Formasa</td>
                    <td>Formasa@email.com</td>
                    <td>Fornecedor</td>
                    <td class="text-center">
                        <span class="label label-default">inativo</span>
                    </td>
                    <td>
                        <a data-toggle="class" href="#">
                            <i class="icon-ok icon-large text-success text-active"></i>
                            <i class="icon-remove icon-large text-danger text"></i>
                        </a>
                    </td>
                </tr>
                <tr>
                    <td>
                        <input type="checkbox" value="3" name="post[]">
                    </td>
                    <td width="10%">(imagem)</td>
                    <td>Avatar system</td>
                    <td>Avatarsys@email.com</td>
                    <td>Tester</td>
                    <td class="text-center">
                        <span class="label label-success">ativo</span>
                    </td>
                    <td>
                        <a data-toggle="class" class="active" href="#">
                            <i class="icon-ok icon-large text-success text-active"></i>
                            <i class="icon-remove icon-large text-danger text"></i>
                        </a>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
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
                <small class="text-muted inline m-t-small m-b-small">Exibindo 20-30 de 50 items</small>
            </div>
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
        </div>
    </footer>
</div>
