$(document).ready(function() {
    $('textarea').trumbowyg({
        semantic: true,
        removeformatPasted: true,
        btns: ['viewHTML',
          '|', 'formatting',
          '|', 'btnGrp-design',
          '|', 'link',
          '|', 'insertImage',
          '|', 'btnGrp-lists',
          '|', 'horizontalRule']
    });

    $('[data-toggle="tooltip"]').tooltip();

    $(document).on("click", "#accept", function(e) {
        $('.modal').fadeOut("fast");
    })

    $(document).on("click", "#like", function(e) {
        e.preventDefault();

        var key = $(this).data('topic');
        var name = $(this).data('user');

        $.post( "like.php", {
            topic: key,
            type: "like",
            username: name
        }).done(function(data) {
            $('#like-alert').html(data.toString()).show();
        });
    });

    $(document).on("click", "#dislike", function(e) {
        e.preventDefault();
        var key = $(this).data('topic');
        var name = $(this).data('user');

        $.post( "like.php", {
            topic: key,
            type: "dislike",
            username: name
        }).done(function(data) {
            var status = data.toString();
            $('#like-alert').html(data.toString()).show();
        });
    });

    $(document).on("click", "#like-alert", function(e) {
        e.preventDefault();
        $('#like-alert').stop().hide();
    });

    $('a[href^="#"]').on('click',function (e) {
        e.preventDefault();

        var target = this.hash;
        var $target = $(target);

        $('html, body').stop().animate({
            'scrollTop': $target.offset().top
        }, 900, 'swing');
    });
    
    $("pre").snippet("html");
});