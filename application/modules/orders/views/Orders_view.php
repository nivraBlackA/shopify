<?php if (!defined('BASEPATH')) exit('No direct script access allowed...'); ?>
<div class="header bg-gradient-success pb-9 pt-5 pt-md-8">
    <div class="container-fluid">
        <div class="header-body">
            <h2 class="text-white"><?= $page_title ?></h2>
        </div>
    </div>
</div>
<!-- Page content -->
<div class="container-fluid mt--7 mb-5">
    <div class="row">
        <div class="col mb-5 mb-xl-0">
            <div class="card bg-gradient-default shadow">
                <div class="card-header bg-gradient-default">

                </div>
                <div class="table-responsive scrollbar-inner detailed_table bg-gradient-default">
                    <table class="table align-items-center table-flush table-small-font text-white"
                        id="spf_orders_table">
                        <thead class="thead-white">
                            <tr>
                                <th>Id</th>
                                <th>Sender</th>
                                <th>Recipient</th>
                                <th>remarks</th>
                                <th>Payment</th>
                                <th>oder date</th>
                                <th>last modified</th>
                                <th>cancel date</th>
                                <th>entry date</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
$(function() {
    base_url = "<?= base_url() ?>";

    $('#spf_orders_table').DataTable({
        "processing": true,
        "serverSide": true,
        "responsive": true,
        initComplete: function() {
            var api = this.api();
            delaySearch(api);
        },
        "ajax": {
            url: base_url + "orders/get_orders",
            type: 'GET'

        },
        oLanguage: {
            oPaginate: {
                sNext: '<span class="pagination-fa"><i class="fa fa-chevron-right" ></i></span>',
                sPrevious: '<span class="pagination-fa"><i class="fa fa-chevron-left" ></i></span>'
            }
        }
    });

});

function delaySearch(api) {
    var searchWait = 0;
    var searchWaitInterval;
    $(".dataTables_filter input")
        .unbind()
        .bind("input", function(e) {
            var item = $(this);
            searchWait = 0;
            if (!searchWaitInterval) searchWaitInterval = setInterval(function() {
                searchTerm = $(item).val();
                clearInterval(searchWaitInterval);
                searchWaitInterval = '';
                api.search(searchTerm).draw();
                searchWait = 0;
                searchWait++;
            }, 500);
            return;
        });
}
</script>