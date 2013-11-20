$(document).ready(function(){
    /**
     * Configurações do plugin ACL
     */
     $('.perms-toggle-single').click(function(){
        Acl.toggleAction(this);
        return false;
    });
     $('.perms-toggle').click(function(){
        Acl.toggleActionBox($(this).parents('.box-perms:eq(0)'));
        return false;
    });
     $('.perms-toggle-all').click(function(){
        var link = this;
        $('.box-perms').each(function(){
            Acl.toggleActionBoxAll(this, $(link));
        });
        return false;
    });
})

var Acl = {
    toggleActionBoxAll:function(box, link){
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
    toggleActionBox:function(box){
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
    toggleAction:function(a){
        /**
        * Carrega o link clicado
        */
        var link = $(a);

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
        link.removeClass('btn-success').removeClass('btn-red');
        link.addClass(status);

        /**
        * Carrega a permissao de todos os actions do box
        */
        link.prev().val(perms);

        /**
        * Carrega as permissoes setadas
        */
        var data_perms = link.prev().serialize();

        $.ajax({
            type: 'POST',
            beforeSend:function(){
                link.css({'pointer-events':'none', 'opacity':'0.2'});
            },
            url: "/main/acl/ajax_permissions",
            data: data_perms
            })
        .done(function(data, status) {
            link.removeAttr('style');
        });
    }
}