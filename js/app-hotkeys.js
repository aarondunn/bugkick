$(document).bind(
    'keydown',
    'n',
    function (evt){
    $('#bug-form').css('display', 'block');
    $('#createBugDialog').dialog('open');
    return false; }
);
$(document).bind(
    'keydown',
    'p',
    function (evt){
    window.location='/project/index';
    return false; }
);
$(document).bind(
    'keydown',
    'h',
    function (evt){
    window.location='/site/dashboard';
    return false; }
);
$(document).bind(
    'keydown',
    'Shift+/',
    function (evt){
    $('#shortcuts').css('display', 'block');
    return false; }
);