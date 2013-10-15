<form method="get" class="form-horizontal m-t-large">
    <div class="col-lg-12">
        <div class="form-group">
            <label class="col-lg-2 control-label">Email</label>
            <div class="col-lg-10">
                <input type="text" data-type="email" data-required="true" class="form-control" placeholder="test@example.com" name="email">
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg-2 control-label">Date</label>
            <div class="col-lg-10">
                <input type="text" data-date-format="dd-mm-yyyy" value="12-02-2013" size="16" class="form-control datepicker">
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg-2 control-label">Password</label>
            <div class="col-lg-10">
                <input type="password" class="form-control" placeholder="Password" name="password">
                <div class="line line-dashed m-t-large"></div>
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg-2 control-label">Username</label>
            <div class="col-lg-10">
                <input type="text" class="form-control" data-required="true" placeholder="Username" name="username">
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg-2 control-label">Account</label>
            <div class="col-lg-4">
                <select class="form-control" name="account">
                    <option value="1">Editor</option>
                    <option value="0">Admin</option>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg-2 control-label">radios</label>
            <div class="col-lg-3">
                <!-- radio -->
                <div class="radio">
                    <label class="radio-custom">
                        <input type="radio" checked="checked" name="radio">
                        <i class="icon-circle-blank checked"></i>Item one checked</label>
                </div>
                <div class="radio">
                    <label class="radio-custom">
                        <input type="radio" name="radio">
                        <i class="icon-circle-blank"></i>Item two</label>
                </div>
                <div class="radio">
                    <label class="radio-custom">
                        <input type="radio" disabled="disabled" name="radio">
                        <i class="icon-circle-blank disabled"></i>Item three disabled</label>
                </div>
                <div class="radio">
                    <label class="radio-custom">
                        <input type="radio" disabled="disabled" checked="checked">
                        <i class="icon-circle-blank checked disabled"></i>Item four checked disabled</label>
                </div>
            </div>
            <div class="col-lg-3">
                <!-- checkbox -->
                <div class="checkbox">
                    <label class="checkbox-custom">
                        <input type="checkbox" checked="checked" name="checkboxA">
                        <i class="icon-unchecked checked"></i>Item one checked</label>
                </div>
                <div class="checkbox">
                    <label class="checkbox-custom">
                        <input type="checkbox" id="2" name="checkboxB">
                        <i class="icon-unchecked"></i>Item two</label>
                </div>
                <div class="checkbox">
                    <label class="checkbox-custom">
                        <input type="checkbox" disabled="disabled" name="checkboxC">
                        <i class="icon-unchecked disabled"></i>Item three disabled</label>
                </div>
                <div class="checkbox">
                    <label class="checkbox-custom">
                        <input type="checkbox" disabled="disabled" checked="checked" name="checkboxD">
                        <i class="icon-unchecked checked disabled"></i>Item four checked disabled</label>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg-2 control-label">Registered</label>
            <div class="col-lg-9">
                <input type="text" value="21-12-2012" name="datetime" data-template="D  MMM  YYYY" data-format="DD-MM-YYYY" class="combodate form-control" style="display: none;">
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg-2 control-label">Profile</label>
            <div class="col-lg-8">
                <textarea data-rangelength="[20,200]" data-trigger="keyup" class="form-control" rows="5" placeholder="Profile"></textarea>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" name="agree">Agree the
                        <a href="#">terms and policy</a>
                    </label>
                </div>
            </div>
        </div>
        <!-- -->
        <!-- -->
        <div class="form-group">
            <div class="col-lg-9 col-lg-offset-2">
                <button class="btn btn-white" type="submit">Cancel</button>
                <button class="btn btn-primary" type="submit">Save changes</button>
            </div>
        </div>
    </div>
</form>