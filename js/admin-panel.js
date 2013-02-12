$(function(){
    var overviewFn = doT.template($('#overviewFn').html()),
    userselectFn = doT.template($('#userselectFn').html());
    
    var allUsers = [];
    
    $('#tabs a:first').tab('show');
    $('#tabs a').click(function (e) {
      e.preventDefault();
      $(this).tab('show');
    });
    
    $('#email').live('keyup', function(){
       resetSubmitButton()
    });
    $('#password').live('keyup', function(){
       resetSubmitButton()
    });
    $('#userselect').live('change', function(){
      $('#password').val('');
      resetSubmitButton()
    });
    
    $('#submit').live('click', function(e){
      data = {'user_id': $('#userselect').val(), 'email': $('#email').val(), 'password': $('#password').val()};
      $.ajax({
        url: '/user/AdminUserUpdate/'+data.user_id,
        dataType: 'json',
        data: data,
        success: function(data) {
          console.log(data);
          if (data[0] == 'error')
          {
            $('#submit').addClass('btn-danger').text(data[1]);
          }
          else
          { 
            $('#submit').addClass('btn-success').text(data[1]);
          }
        }
      });
    });
    
    $.getJSON("/user/topFiveActive", function(data1){
        $.getJSON("/user/fiveNewest", function(data2){
            var quickStats = {'topFiveActive':data1, 'FiveNewest':data2};  
            $('#loading1').hide(); 
            $('div.sidebar_middle').append(overviewFn(quickStats)).show('blind');
        })
    })

    $('#userusage').hide();
    var data = {};
    var oTable = $('#userusage').dataTable( {
            "bProcessing": true,
            "aaData": data,
            "iDisplayLength": 50,
			"sPaginationType": "full_numbers",	
            "aoColumns": [
                { "sTitle": "Id", "mDataProp": "user_id" },
                { "sTitle": "Name", "mDataProp": "name" },
                { "sTitle": "Email", "mDataProp": "email" },
                { "sTitle": "Total Bug Count", "mDataProp": "bugCount" },
                { "sTitle": "Bug Count Past Month", "mDataProp": "bugCountPastMonth" },
                { "sTitle": "Total Comment Count", "mDataProp": "commentCount" },
                { "sTitle": "Comment Count Past Month", "mDataProp": "commentCountPastMonth" }
            ]
        } );
        
    //$("#dialog-modal").dialog('open');
    $.getJSON('/user/adminUserStats/', function(data){    
        allUsers = data;
        $('#userusage').dataTable().fnAddData(data);
        $('#loading2').hide();
        $('#userusage').show('blind');
        $('#update-user').append(userselectFn(data));
        $('#submit').button();
    });  
    
    $('#userselect').live('change' , function(e){
      var selection = $(this).val();
      
      user = $.grep(allUsers, function(item){
        return item.user_id == selection;
      });
      
      $('#email').val(user[0].email);      
    });
});
    
function resetSubmitButton(){
  console.log('hi');
  $('#submit').removeClass('btn-success').removeClass('btn-danger').text('Submit');
}