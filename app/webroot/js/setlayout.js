$(window).load(function() {
	//Ajaxの設定

    $("#set").click(function(){

        var selector = $("text");

        var x = [];
        var y = [];
        var id = [];
        var count = 0;


        selector.each(function(){

            if(count % 3 == 0) {
                x[count/3] = $(this).attr("x") - 40;
                y[count/3] = $(this).attr("y");
            }
            count ++;
        });

        var selector = $("text");
        var id = [];
        var count = 0;

        selector.each(function(){
            if(count % 3 == 0) {
                id[count/3] = $(this).attr("font-weight");
            }
            count ++;
        });

        

        jQuery.ajax(
            '/api/setlayout/setlayout',
                {
                    dataType: 'json',
                    data: {
                        "x": x,
                        "y": y,
                        "id": id
                    },
                    success: function( data ) {
                        alert("Set Layout");
                    },
                    error: function( data ) {
                        alert("Set Layout");
                    }
                }
            );
    });
});

$(window).load(function() {
    //Ajaxの設定

    $("#setBehavior").click(function(){


        var selector = $(".element");
        var x = [];
        var y = [];
        var position = [];
        var str;
        var main_count = 0;


        selector.each(function(){
            str = $(this).attr("transform");
            if (str !== undefined) {
              str = str.slice(10, -1);
              position = str.split(",");
              x[main_count] = position[0];
              y[main_count] = position[1];
              main_count ++;
            }
        });


        var selector = $(".marker-vertex-group");
        var link_x = [];
        var link_y = [];
        var position = [];
        var str;
        var link_count = 0;


        selector.each(function(){
            str = $(this).attr("transform");
            if (str !== undefined) {
              str = str.slice(10, -1);
              position = str.split(", ");
              link_x[link_count] = position[0];
              link_y[link_count] = position[1];
              link_count ++;
            }
        });

        
        var selector = $("text");
        var id = [];
        var link_id = [];
        var count_1 = 0;
        var count_2 = 0;

        selector.each(function(){
            if(count_1 < main_count) {
                id[count_1] = $(this).attr("font-weight");
                count_1 ++;
            } else {
                link_id[count_2] = $(this).attr("font-weight");
                count_2 ++;
            }
        });


        jQuery.ajax(
            '/api/setlayout/setBehaviorlayout',
                {
                    dataType: 'json',
                    data: {
                        "x": x,
                        "y": y,
                        "link_x": link_x,
                        "link_y": link_y,
                        "id": id,
                        "link_id": link_id
                    },
                    success: function( data ) {
                        alert("Set Layout");
                    },
                    error: function( data ) {
                        alert("Set Layout");
                    }
                }
            );
    });
});


$(window).load(function() {
    //Ajaxの設定

    $("#setPattern").click(function(){

        var selector = $("text");

        var x = [];
        var y = [];
        var id = [];
        var count = 0;


        selector.each(function(){

            if(count % 3 == 0) {
                x[count/3] = $(this).attr("x") - 40;
                y[count/3] = $(this).attr("y");
            }
            count ++;
        });

        var selector = $("text");
        var id = [];
        var count = 0;

        selector.each(function(){
            if(count % 3 == 0) {
                id[count/3] = $(this).attr("font-weight");
            }
            count ++;
        });


        jQuery.ajax(
            '/api/setlayout/setPatternlayout',
                {
                    dataType: 'json',
                    data: {
                        "x": x,
                        "y": y,
                        "id": id
                    },
                    success: function( data ) {
                        alert("Set Layout");
                    },
                    error: function( data ) {
                        alert("Set Layout");
                    }
                }
            );
    });
});


$(window).load(function() {
    //Ajaxの設定

    $("#setPatternBehavior").click(function(){

        console.log("hoge");


        var selector = $(".element");
        var x = [];
        var y = [];
        var position = [];
        var str;
        var main_count = 0;


        selector.each(function(){
            str = $(this).attr("transform");
            if (str !== undefined) {
              str = str.slice(10, -1);
              position = str.split(",");
              x[main_count] = position[0];
              y[main_count] = position[1];
              main_count ++;
            }
        });


        var selector = $(".marker-vertex-group");
        var link_x = [];
        var link_y = [];
        var position = [];
        var str;
        var link_count = 0;


        selector.each(function(){
            str = $(this).attr("transform");
            if (str !== undefined) {
              str = str.slice(10, -1);
              position = str.split(", ");
              link_x[link_count] = position[0];
              link_y[link_count] = position[1];
              link_count ++;
            }
        });

        
        var selector = $("text");
        var id = [];
        var link_id = [];
        var count_1 = 0;
        var count_2 = 0;

        selector.each(function(){
            if(count_1 < main_count) {
                id[count_1] = $(this).attr("font-weight");
                count_1 ++;
            } else {
                link_id[count_2] = $(this).attr("font-weight");
                count_2 ++;
            }
        });


        jQuery.ajax(
            '/api/setlayout/setPatternBehaviorlayout',
                {
                    dataType: 'json',
                    data: {
                        "x": x,
                        "y": y,
                        "link_x": link_x,
                        "link_y": link_y,
                        "id": id,
                        "link_id": link_id
                    },
                    success: function( data ) {
                        alert("Set Layout");
                    },
                    error: function( data ) {
                        alert("Set Layout");
                    }
                }
            );
    });
});