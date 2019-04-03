$(function(){
    //シフト希望日のフォーム追加
    var button = document.getElementById("button");
    button.addEventListener("click",plus,false);

    function plus(){
        /*コピーする要素を囲ったdivをcloneする
        親要素の最後にappendする*/
        var parent = document.getElementById("column_wrap");//親要素
        var column = document.getElementsByClassName("column");//コピー元取得
        var first_column = column[0];
        var clone = first_column.cloneNode(true);//コピー(子要素も)
        parent.appendChild(clone);//親要素の末尾に追加
    }

    //個人全体タブ切り替え
    $('.tab_menu li').click(function(){
        var index = $(".tab_menu li").index(this);
        $(".tab_menu li").removeClass("active");
        $(this).addClass("active");
        $(".area .div").removeClass("show").eq(index).addClass("show");
    });

    //全体用の詳細表示
    $('.a_d').click(function(){
        var click_day = $(this).attr("name");//クリックされた日付の取得
        $("#info_p").text(click_day + "日のシフト");
        $(".inner_ul li").remove();
        for(var i in json_arr[click_day]){//a,b,cのシフトを順に見る
             for(var j in json_arr[click_day][i]){
                 var ul_name = "#ul_"+i;
                $(ul_name).append('<li>'+json_arr[click_day][i][j]+'</li>');
             }
        }
        $(".list").css('height','');
        $(".list").height($(".wrap_ul").height());
    });
});