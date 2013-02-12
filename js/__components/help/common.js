$(window).load(function() {

    var articleContainer = '.help-content';
    var articleSearchField = '#help-search';
    var articleForm = '#help-form';
    var articleBackLink = '.help-back-link';
    var height = 440;

    //choosing an article
    $(window.document).on('click', 'a.article-link', function(e) {
        e.preventDefault();
        $.get(
            $(this).attr("href"),
            {},
            function(data) {
                $(articleContainer).html(data);
                $("#help-window .help-content-wrapper").css({
                    "height" : height
                });//l
                $("#help-window .help-content-wrapper").jScrollPane({
                    hideFocus: true,
                    animateScroll: true
                });
                $("#help-window .help-content-wrapper .jspContainer").css("width","600px");
            }
            );
        return false;
    });

    //search for articles
    var articlesSearch = function(){
        //stop previous requests
        if(navigator.appName == "Microsoft Internet Explorer")
            window.document.execCommand('Stop');
        else
            window.stop();

        //sending request
        $.ajax({
            async   : true,
            type    : "POST",
            url     : $(articleForm).attr('action'),
            dataType: "html",
            data    : $(articleForm).serialize(),
            success : function(data){
                $(articleContainer).html(data);
                $("#help-window .help-content-wrapper").css({
                    "height" : auto
                });//l
                $("#help-window .help-content-wrapper").jScrollPane({
                    hideFocus: true,
                    animateScroll: true
                });
                $("#help-window .help-content-wrapper .jspContainer").css("width","600px");
                return false;
            },
            error   : function(data){
                return false;
            }
        })
    };
    $(articleSearchField).live('keyup',articlesSearch);
    $(articleBackLink).live('click',articlesSearch);
});
$(document).ready(function(){
    $('.help-back-link').click(function(){
        $('#help-search').val("");
        $("#help-search").keyup();
        setTimeout('$("#help-window .help-content-wrapper").css({"height" : 220});$("#help-window .help-content-wrapper").jScrollPane({hideFocus: true,animateScroll: true});$("#help-window .help-content-wrapper .jspContainer").css("width","600px");', 200);
        return false;
    });
    $('#cancel-search').click(function(){
        $("#helpDialog").dialog("close"); 
        return false;
    });
});
