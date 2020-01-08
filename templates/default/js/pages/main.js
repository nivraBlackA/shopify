$(function(){
    $('.scrollbar-outer').scrollbar();
    $('.scrollbar-inner').scrollbar();

    $(".datatable").DataTable({
        oLanguage: {
            oPaginate: {
                sNext: '<span class="pagination-fa"><i class="fa fa-chevron-right" ></i></span>',
                sPrevious: '<span class="pagination-fa"><i class="fa fa-chevron-left" ></i></span>'
            }
        }
    });

    $(".has-treeview").click(function(){
        var test = $(this).find(".nav-treeview").toggleClass("d-block");
        var test = $(this).find(".fa-angle-left").toggleClass("fa-rotate-270");
        console.log(test);
    });

    $("#main_search_box").bindWithDelay("keyup",function(){
        if ($(this).val().length > 0)
            search_me($(this).val());
        else
            $("#main_search_box_list").html("");
    },1000);

    function search_me(keyword){
        
        console.log(keyword);
        $("#main_search_box_list").html("");
        $.post(base_url + "home/home_search",{
            search : keyword
        },function(res){
            res.forEach(function(v,k){
                var newRow = "<li class='list-group-item bg-transparent'><a href='" + v.url + "'>"+ v.code+"</a></li>";
                $("#main_search_box_list").append(newRow);
            })
        },"json")
    }

    function number_format(amount) {
        amount = Number(amount);
        return amount.toLocaleString(undefined, {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
    }
})