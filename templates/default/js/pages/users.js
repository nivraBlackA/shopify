$(function(){
    cod = "";
    type = "";
    $(".opt_btn").click(function(){
        $(".opt_btn").removeClass("btn-primary");
        $(".opt_btn").removeClass("btn-danger");
        $(".opt_btn").addClass("btn-danger");

        $(this).removeClass("btn-danger");
        $(this).addClass("btn-primary");

        type = $(this).data('type');
        if (type == 'all')
            $("#download_orders").removeClass("d-none");
        else
            $("#download_orders").addClass("d-none");
            
        get_orders(type,cod);
    });

    $(".cod_btn").click(function(){
        if ($(this).hasClass('active')){
            cod = "";
            $(this).removeClass('btn-primary');
            $(this).addClass('btn-danger');
            $(this).removeClass('active');
        }
        else{
            cod = "cod";
            $(this).addClass('active');
            $(this).addClass('btn-primary');
            $(this).removeClass('btn-danger');
        }
        get_orders(type,cod);
    });

    $("#postpaid_user_table").on("click",".this_range",function(){
        $("#xuser_details").removeClass('d-none');
        var start = $(this).attr("data-start");
        var end = $(this).attr("data-end");
        getweekData(start,end);
    });


    base_url = $("#base_url").val();
    get_orders();
    function get_orders(type = '',cod=''){
        var user_id = $("#user_id").val();
        order_table =  $("#order_table").DataTable({
                "processing": true,
                "serverSide": true,
                "autoWidth" : false,
                "responsive" : true,
                "bDestroy": true,
                "ajax":{
                        url : base_url + "users/get_orders/" + type + "/" + cod, // json datasource
                        type: "post",  // method  , by default get,
                        data : {"user_id" : user_id},
                        error: function(){  
                        }
                },
                "initComplete" : function(){
                    var xlabel = "Pickup Date";
                    if (type == 'for_pickup' || type == 'active_carts' || type == '')
                        xlabel = "Pickup Schedule";
                        $("#pickup_date_label").text(xlabel);
                },
                'columnDefs': [
                        {
                                'targets': 0,   
                                'searchable':true,
                                'orderable':true,
                                'visibily':true,
                                'className': '',
                                'render': function (data, type, full, meta){
                                        var view = "<a href='"+ base_url +"orders/id/"+ data.id +"' class='font-weight-bold text-light'>" + data.tracking_no + "</a>";
                                        return view;
                                }
                        },
                        {
                            'targets': 4,
                            'render': function (data, type, full, meta){
                                return number_format(data);
                            }
                        } 
                ],
                oLanguage: {
                oPaginate: {
                    sNext: '<span class="pagination-fa"><i class="fa fa-chevron-right" ></i></span>',
                    sPrevious: '<span class="pagination-fa"><i class="fa fa-chevron-left" ></i></span>'
                }
            }
        });
        
    }

    
    function number_format(amount) {
        amount = Number(amount);
        return amount.toLocaleString(undefined, {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
    }

    function getweekData(date_start="",date_end="") {
        var user_id = $("#user_id").val();
        $.get(base_url + "users/get_postpaid_transaction",{
            ajax  : 1,
            start : date_start,
            end : date_end,
            user_id : user_id
        },function(res){

            var this_list = [];
            var tot_amount_paid = 0;
            var tot_amount_pending = 0;
            res.forEach(list => {
                tot_amount_paid += Math.ceil(list.credit);
                tot_amount_pending += Math.ceil(list.debit);
                this_list += "<tr>";
                    this_list += "<td>"+ moment(list.trans_date).format('ll') +"</td>";
                    this_list += "<td>"+ list.trans_no +"</td>";
                    this_list += "<td class='text-right'>"+ list.debit + "</td>";
                    this_list += "<td class='text-right'>"+ list.credit + "</td>";
                this_list += "</tr>";
            });
                this_list += "<tr>";
                    this_list += "<td colspan='2'>Total</td>";
                    this_list += "<td class='text-right'>"+ tot_amount_pending.toFixed(2) + "</td>";
                    this_list += "<td class='text-right'>"+ tot_amount_paid.toFixed(2) +"</td>";
                this_list += "</tr>";
            $("#userpostpaid").html(this_list);
            
            $("#week_range").html("Week of " + moment(date_start).format('ll') + " - " + moment(date_end).format('ll'));

        },'json');
    }
})