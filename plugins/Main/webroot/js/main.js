$(document).ready(function(){
    /**
     * Configurações do plugin ACL
     */
     $('.perms-toggle').click(function(){
        Acl.toggleAction($(this).parents('.box-perms:eq(0)'));
        return false;
    });
     $('.perms-toggle-all').click(function(){
        var link = this;
        $('.box-perms').each(function(){
            Acl.toggleActionAll(this, $(link));
        });
        return false;
    });
})

var Acl = {
    toggleActionAll:function(box, link){
        /**
        * Carrega o elemento BOX
        */
        var $el = $(box);

        var link_slide = $('a.perms-toggle:eq(0)', $el);

        /**
        * Carrega a permissao
        */
        var perm;
        if(link.hasClass('allow')){
            perm = 'allow';
        }else if(link.hasClass('deny')){
            perm = 'deny';
        }

        /**
        * Carrega o status conforme a permissao
        */
        var status;
        switch(perm){
            case 'allow':
                status = 'btn-success';
            break;       
            case 'deny':
                status = 'btn-red';
            break;       
        }

        /**
        * Alterna a permissao do link
        */
        link
            .removeClass('allow')
            .removeClass('deny');
        link.addClass(perm);          

        /**
        * Carrega a permissao de todos os actions do box
        */
        $(':hidden[name*=Perms]', $el).val(perm);

        /**
        * Carrega as permissoes setadas
        */
        var data_perms = $(':hidden[name*=Perms]', $el).serialize();

        $.ajax({
            type: 'POST',
            beforeSend:function(){
                link_slide.find("i").addClass("icon-spin");
                $el.css({'pointer-events':'none', 'opacity':'0.2'});
            },
            url: "/main/acl/ajax_permissions",
            data: data_perms
            })
        .done(function(data, request_status) {
            if(request_status == 'success'){
                /**
                * Altera a cor do controle conforme a sua permissao
                */
                $('a[href^=#aco-]', $el)
                    .removeClass('btn-success')
                    .removeClass('btn-red');
                $('a[href^=#aco-]', $el).addClass(status);            

                link_slide.find("i").removeClass("icon-spin");
                $el.removeAttr('style');
            }
        });

        // console.log($(':hidden[name*=Perms]', $el).serialize());
    },
    toggleAction:function(box){
        /**
        * Carrega o elemento BOX
        */
        var $el = $(box);

        /**
        * Carrega o link clicado
        */
        var link = $('a.perms-toggle:eq(0)', $el);

        /**
        * Carrega a permissao
        */
        var perms = link.hasClass('allow')?'allow':'deny';

        /**
        * Carrega o status conforme a permissao
        */
        var status = link.hasClass('allow')?'btn-success':'btn-red';

        /**
        * Alterna a permissao do link
        */
        link.toggleClass('allow');

        /**
        * Altera a cor do controle conforme a sua permissao
        */
        $('a[href^=#aco-]', $el).removeClass('btn-success').removeClass('btn-red');
        $('a[href^=#aco-]', $el).addClass(status);

        /**
        * Carrega a permissao de todos os actions do box
        */
        $(':hidden[name*=Perms]', $el).val(perms);

        /**
        * Carrega as permissoes setadas
        */
        var data_perms = $(':hidden[name*=Perms]', $el).serialize();

        $.ajax({
            type: 'POST',
            beforeSend:function(){
                link.find("i").addClass("icon-spin");
                $el.css({'pointer-events':'none', 'opacity':'0.2'});
            },
            url: "/main/acl/ajax_permissions",
            data: data_perms
            })
        .done(function(data, status) {
            link.find("i").removeClass("icon-spin");
            $el.removeAttr('style');
        });

        // console.log($(':hidden[name*=Perms]', $el).serialize());
    },
    bulkAction:function(a, bulkAction){
        var label;
        var color;
        var dayBlock;
        var action;
        var btn;
        $(a).each(function(){
            btn = this;
            dayBlock = $(btn).parents('.day-block:eq(0)');
            action = (bulkAction)?bulkAction:$(btn).attr('rel');

            dayBlock.find(':hidden:eq(0)').val(action);
            dayBlock.find('a[class*=permsBtn-]')
            .removeClass('custom-green')
            .removeClass('custom-red')
            .removeClass('custom-grey');
            switch(action){
                case 'allow':
                    label = 'Permitir';
                    color = 'green';
                break;
                case 'deny':
                    label = 'Negar';
                    color = 'red';
                break;
                case 'inherit':
                    label = 'Herdar';
                    color = 'grey';
                break;
            }
            dayBlock.find('a[rel=' + action + ']').addClass('custom-' + color);

            dayBlock.find('.txt-info')
            .css('color', color)
            .html(label);
        });
    }
}