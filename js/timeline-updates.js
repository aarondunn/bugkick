$(function(){
    //Load up the templates
    var notificationFn = doT.template($('#notificationFn').html()),
    dateFn = doT.template($('#dateFn').html()),
    todaysDateFn = doT.template($('#todaysDateFn').html()),
    titleFn = doT.template($('#titleFn').html());
    
    $('#container').masonry({itemSelector : '.item'});
    
    $.getJSON("notification/notifications", function(data){
        var previousDate;
        var todaysDate = new Date();
        var monthNames = [ "January", "February", "March", "April", "May", "June",
    "July", "August", "September", "October", "November", "December" ];
    var dayNames= ["Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday"]
        $.each(data, function(i, val){

            var dateStr = val.date;
            var a=dateStr.split(" ");
            var d=a[0].split("-");
            var t=a[1].split(":");
            d = new Date(d[0],(d[1]-1),d[2],t[0],t[1],t[2]);
            var dateTemp = new Date(d[0],(d[1]-1),d[2],t[0],t[1],t[2]);

            var datetime;
            datetime = val.date.split(" ");
            if (previousDate != datetime[0])
            {
                var inputDate = d,
                displayDate = dayNames[inputDate.getDay()] + " " + inputDate.getDate() + ' ' + monthNames[inputDate.getMonth()] + ' ' + inputDate.getFullYear();

                if(dateTemp.setHours(0,0,0,0) == todaysDate.setHours(0,0,0,0))
                {
                    $('#container').append(todaysDateFn({date:displayDate}));
                }
                else 
                {
                    $('#container').append(dateFn({date:displayDate}));
                }                
            }

            var stringTime,
            h = d.getHours(),
            m = d.getMinutes();

            if (h > 12)
            {
                h = h-12;
                if (m < 10)
                {
                    m = "0"+m;
                }
                if (h < 10)
                {
                    h = "0"+h;
                }
                stringTime = h + ":" + m + "PM";
            } 
            else
            {
                if (m < 10)
                {
                    m = "0"+m;
                }
                if (h < 10)
                {
                    h = "0"+h;
                }
                stringTime = h + ":" + m + "AM";
            }
            val.date = stringTime;
            $('#container').append(notificationFn(val));
            previousDate = datetime[0];
        });
        
        $('#removeMe').remove();
        //Set up the timeline
        $("#container").prepend(titleFn({}));
        $('#dummyItem').hide();
        $('#dummtItem').addClass('dummyItem');
        
        //load masonry
        $('#container').masonry({itemSelector : '.item'});
        $('.rightCorner').hide();
        $('.leftCorner').hide();
        
        var s = $('#container').find('.item');
        $.each(s,function(i,obj){
            if(!$(obj).hasClass('dateItem') && !$(obj).hasClass('dummyItem')){
                var posLeft = $(obj).css("left");
                $(obj).addClass('borderclass');
                
                /*if(posLeft == "0px")
                {
                    if (Math.random() > 0.7)
                    {
                        $(obj).addClass('stagger');
                    }
                }
                else
                {
                    if (Math.random() < 0.3)
                    {
                        $(obj).addClass('stagger');
                    }
                }*/
            }
        });
        
        $('#container').masonry('reload');
        
        s = $('#container').find('.item');
        $.each(s,function(i,obj){
            if(!$(obj).hasClass('dateItem')){
                var posLeft = $(obj).css("left");
                $(obj).addClass('borderclass');

                if(posLeft == "0px")
                {
                    //html = "<span class='rightCorner'></span>";
                    html = "<span class='leftCorner'></span>";
                    $(obj).prepend(html);			
                    //$('#dateTime', obj).addClass('dateTimeLeft');
                }
                else
                {
                    html = "<span class='leftCorner'></span>";
                    $(obj).prepend(html);
                    //$('#dateTime', obj).addClass('dateTimeRight');
                }
            }
        });
        
    });
});

