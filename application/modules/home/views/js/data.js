$(document).ready(function() {
    $(".mn-drop-admin>a, .mn-admin").addClass("active");
    $(".mn-drop-admin>.dropdown-collapse").addClass("toggledropdown");

    $(".s2").select2({
        theme: "bootstrap",
        width: "100%",
        placeholder: "pilih...",
    });

    $(".id_level").change(getKabupaten);
    $(".kabupaten_hide").hide();

    $(".form-filter").on('click', '.btn-reset', function(event) {
        event.preventDefault();

        $(".form-filter").find("input").val("");
        $(".form-filter").find(".id_level").select2("val", "");
        $(".form-filter").find(".kabupaten").select2("val", "");
        
        $(".form-filter").find(".s2").select2("val", "");
        refreshTable();
    });

    $(".dataTable").DataTable({
        "ajax": {
            "url"    :"<?php echo base_url("admin/data") ?>",
            "method" :"POST",
            "data"   : function ( d ) {
                d.nama_lengkap = $(".nama_lengkap").val();
                d.username = $(".username").val();
                d.id_level = $(".id_level").val();
                d.kabupaten = $(".kabupaten").val();
            }
        },

        "columns": [
            {"data": "no"},
            {
                "class": "table-button-aksi",
                "data": "aksi",
            },
            {"data": "nama_lengkap"},
            {"data": "username"},
            {"data": "id_level"},
            {"data": "kabupaten"},
        ],

        "responsive": true,
        "pageLength"  : 100,
        "deferRender" : true,
        "serverSide"  : true,
        "processing"  : false,
        "filter"      : false,
        "ordering"    : true,
        "bLengthChange": false,

        "order": [[ 0, "asc" ]],

        "columnDefs": [
            {
                "targets": 0,
                "orderable": false
            },
            {
                "targets": 1,
                "orderable": false
            },
        ],

        "language": {
            "sProcessing"   : "Sedang memproses...",
            "sLengthMenu"   : "Tampilkan _MENU_ entri",
            "sZeroRecords"  : "Tidak ditemukan data",
            "sInfo"         : "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri",
            "sInfoEmpty"    : "Menampilkan 0 sampai 0 dari 0 entri",
            "sInfoFiltered" : "(difilter dari _MAX_ entri keseluruhan)",
            "sInfoPostFix"  : "",
            "sUrl"          : "",
            "oPaginate"     : {
                "sFirst"        : "Pertama",
                "sPrevious"     : "Sebelumnya",
                "sNext"         : "Selanjutnya",
                "sLast"         : "Terakhir"
            }
        },
    });

    $(".form-filter").validate({
        submitHandler : function(form) {
            refreshTable();
            return false;
        }
    });

    $(".dataTable").on("click", ".btn-delete", function(event) {
        var id = $(this).data("id");
        swal({
            title: "Konfirmasi",
            text: "Anda yakin akan menghapus data ini?",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#fb483a",
            cancelButtonColor: "#D9534F",
            confirmButtonText: "Hapus Data",
            cancelButtonText: "Batalkan",
            showLoaderOnConfirm: true,
            closeOnConfirm: true,
            closeOnCancel: true
        },
        function (isConfirm) {
            if (isConfirm) {
                $.ajax({
                    url: "<?php echo base_url("admin/delete_proses") ?>",
                    type: "post",
                    dataType: "json",
                    data: { id: id },
                    success: function (json) {
                        if (json.stat) {
                            refreshTable();
                            swal(
                                'Deleted!',
                                'Data telah dihapus.',
                                'success'
                            );
                        } else {
                            swal(
                                'Gagal!',
                                'Data gagal dihapus.',
                                'error'
                            )
                        }
                    }
                });
            }
        });
    });
});

function refreshTable () {
    var dtable = $(".dataTable").DataTable();
    dtable.draw();
}

function getKabupaten () {
    var parent = $(this).val();

    if (parent == 2) {
        $(".kabupaten_hide").show();
    } else {
        $(".kabupaten_hide").hide();
    }
}