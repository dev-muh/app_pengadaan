$(function() {
    $('#datetimepicker6').datetimepicker({
        format: 'YYYY-MM-DD'
    });
    $('#datetimepicker7').datetimepicker({
        format: 'YYYY-MM-DD',
        useCurrent: false //Important! See issue #1075
    });
    $("#datetimepicker6").on("dp.change", function(e) {
        $('#datetimepicker7').data("DateTimePicker").minDate(e.date);
    });
    $("#datetimepicker7").on("dp.change", function(e) {
        $('#datetimepicker6').data("DateTimePicker").maxDate(e.date);
    });
});

function submit(x) {
    if (!x.hasClass('disabled')) {
        let sdate = $('#startper').val();
        let edate = $('#endper').val();
        let sla_type = $('#sla_act').val();
        let opt = {
            startper: sdate,
            endper: edate,
            sla_type: sla_type
        }
        if (sdate == '' || edate == '') {
            $.alert('Tanggal tidak boleh kosong.');
        } else {
            if (sla_type == '' || sla_type == null) {
                $.alert('Anda belum memilih jenis laporan.');
            } else {
                x.addClass('disabled');
                startloading('Mohon tunggu...');
                $.post(URL + 'report/tb_sla_penerimaan', opt).done(function(data) {
                    x.removeClass('disabled');
                    endloading();
                    $('#tb_sla').html(data);
                }).fail(function() {
                    endloading();
                    x.removeClass('disabled');
                    $('#tb_sla').html("Error Saat mengambil data.");
                });
            }
        }
    }
}

function export_pdf_report_sla_penerimaan(x) {
    if (!x.hasClass('disabled')) {
        let sdate = $('#startper').val();
        let edate = $('#endper').val();
        let sla_type = $('#sla_act').val();
        let opt = 'sla_type=' + sla_type;
        opt += ('&service_level=' + $("#sla_act option:selected").html());
        opt += ('&startper=' + sdate);
        opt += ('&endper=' + edate);
        if (sdate == '' || edate == '') {
            $.alert('Tanggal tidak boleh kosong.');
        } else {
            if (sla_type == '' || sla_type == null) {
                $.alert('Anda belum memilih jenis laporan.');
            } else {
                window.location.href = URL + 'report/export_pdf_report_sla_penerimaan?' + opt;
            }
        }
    }
}

function export_excel_report_sla_penerimaan(x) {
    if (!x.hasClass('disabled')) {
        let sdate = $('#startper').val();
        let edate = $('#endper').val();
        let sla_type = $('#sla_act').val();
        let opt = 'sla_type=' + sla_type;
        opt += ('&service_level=' + $("#sla_act option:selected").html());
        opt += ('&startper=' + sdate);
        opt += ('&endper=' + edate);
        if (sdate == '' || edate == '') {
            $.alert('Tanggal tidak boleh kosong.');
        } else {
            if (sla_type == '' || sla_type == null) {
                $.alert('Anda belum memilih jenis laporan.');
            } else {
                window.location.href = URL + 'report/export_excel_report_sla_penerimaan?' + opt;
            }
        }
    }
}