

$(function(){
      
$(document).on('click','.frame',function(){
    
    href=$(this).attr('href');
    alert(href);
    return false;
    
})

$(document).on('keyup','.price',function(){
   executeComma2(this);
})

$(".frame").click(function(){

    width=$(this).data('width');
    height=$(this).data('height');
    if(!height)height='500';
    if(!width)width='80%';
    $("#myModal").find('.modal-dialog').css('width',width);
      href=$(this).attr('href');
      title=$(this).attr('title');
      $("#myModal").find(".modal-content").empty();
$("#myModal").modal('show');


    var html;
    html='<div class="modal-header">';
    html += '<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>';
    html +='<h4 class="modal-title" id="myModalLabel">'+title+'</h4>';
    html +='</div>';
    html +='<div class="modal-body">';
    html +='<iframe src="" style="border:0;width: 100%; height: '+height+'px;"></iframe>';
    html +='</div>';
    
    $("#myModal").find(".modal-content").append(html);
      $("#myModal").find('iframe').attr('src',href);
      

    return false;
})
});

        $(".ajax").click(function() {

                $url = $(this).attr('href')
                loadding();
                $.ajax({
                        type: "GET",
                        cache: false,
                        url: $url,
                        success: function(html) {
                                loadconplex()
                                $('#myModal .modal-content').html(html);
                        }
                })

                $('#myModal').modal('show');
                return false;
        })
        

jQuery.fn.extend({
        check: function() {
                return this.each(function() {
                        $(this).prop('checked', !($(this).is(':checked')));
                });
        },
        uncheck: function() {
                return this.each(function() {
                        this.checked = false;
                });
        }
});
$.fn.toggleCheck = function() {
        if (this.tagName === 'INPUT') {
                $(this).prop('checked', !($(this).is(':checked')));
        }

}

function number_format(fr) {
        var temp = fr;


        for (i = 0; i < temp.length; i++) {
                for (k = i; k < temp.length; k++) {
                        if (temp.charAt(k) == ',') {
                                temp = temp.replace(',', '');
                        }
                }
        }

        var j = 0;
        var s = "";
        var s1 = "";
        var s2 = "";
        for (i = temp.length - 1; i >= 0; i--) {
                j = j + 1;
                if (j == 3) {
                        j = 0;
                        s1 = temp.substring(0, i);
                        s2 = temp.substring(i, i + 3);
                        s = "," + s2 + s;
                }
        }
        if (s1.length > 0) {
                s = s1 + s;
                return s;
        } else if (s.length > 0 && s2.length > 0) {
                return s.substring(1, s.length);
        }
}




function executeComma2(fr) {
        temp = fr.value;
        /*for (i=0;i<temp.length;i++) {
        if (temp.charAt(i) == ',') {
        temp.charAt(i) = '';
        }
        }*/

        for (i = 0; i < temp.length; i++) {
                for (k = i; k < temp.length; k++) {
                        if (temp.charAt(k) == ',') {
                                temp = temp.replace(',', '');
                        }
                }
        }

        var j = 0;
        var s = "";
        var s1 = "";
        var s2 = "";
        for (i = temp.length - 1; i >= 0; i--) {
                j = j + 1;
                if (j == 3) {
                        j = 0;
                        s1 = temp.substring(0, i);
                        s2 = temp.substring(i, i + 3);
                        s = "," + s2 + s;
                }
        }
        if (s1.length > 0) {
                //alert(s1.length);
                s = s1 + s;
                fr.value = s;
        } else if (s.length > 0 && s2.length > 0) {
                fr.value = s.substring(1, s.length);
        }
}

function executeComma(event, fr) {
        //alert(event.keyCode);
        if ((event.keyCode >= 96 && event.keyCode <= 105)) {
                executeComma2(fr);
        } else if (event.keyCode >= 48 && event.keyCode <= 57) {
                executeComma2(fr);
        } else if (event.keyCode == 8 || event.keyCode == 46 || event.keyCode == 8 || event.keyCode == 9) {
                executeComma2(fr);
        } else {
                //alert("Già� trì£ nhĂ¢̀£p và€o là€ mĂ´̀£t sĂ´̀�!");
                //fr.value = "";
        }


}

function xoadau(alias) {
        var str = alias;
        str = str.toLowerCase();
        str = str.replace(/à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ/g, "a");
        str = str.replace(/è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ/g, "e");
        str = str.replace(/ì|í|ị|ỉ|ĩ/g, "i");
        str = str.replace(/ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ/g, "o");
        str = str.replace(/ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ/g, "u");
        str = str.replace(/ỳ|ý|ỵ|ỷ|ỹ/g, "y");
        str = str.replace(/đ/g, "d");
        str = str.replace(/!|@|%|\^|\*|\(|\)|\+|\=|\<|\>|\?|\/|,|\.|\:|\;|\'| |\"|\&|\#|\[|\]|~|$|_/g, "-");
        /* tìm và thay thế các kí tự đặc biệt trong chuỗi sang kí tự - */
        str = str.replace(/-+-/g, "-"); //thay thế 2- thành 1-
        str = str.replace(/^\-+|\-+$/g, "");
        //cắt bỏ ký tự - ở đầu và cuối chuỗi
        return str;
}



