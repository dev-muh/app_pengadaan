$.fn.DataTable.ext.errMode = 'none';
$('#tb_in').DataTable({
    "ordering": false,
    "paging": false
});
$('#tb_out').DataTable({
    "ordering": false,
    "paging": false
});
$(function() {
    if (id_item == '' || id_item === null) {} else {
        $('#sel_item').val(id_item);
    }
    $('#sel_item').select2({
        width: "100%"
    });
    $('#opt').fadeIn('fast');
});

function ch_it(x, y, t) {
    if (x === null || y === null || x == '' || y == '') {
        $.alert('Anda belum memilih barang.');
    } else {
        startloading('Mohon tunggu...');
        $('#tb_res').empty();
        $('#res_fifo').hide();
        // $('#tb_res').load(URL + 'report/fifo?item=' + x + '&bulan=' + y + '&tahun=' + t, function(res, stat, xhr) {
        $('#tb_res').load(URL + 'fifoReport/fifo?item=' + x + '&bulan=' + y + '&tahun=' + t, function(res, stat, xhr) {
            if (stat == 'success') {
                $('#res_fifo').fadeIn('fast');
                $('#tb_res').html(res);
                endloading();
            }
            if (stat == 'error') {
                $('#res_fifo').fadeIn('fast');
                $('#tb_res').html('<center>Error memuat data. Pastikan koneksi internet anda lancar. Coba lagi.</center>');
                endloading();
            }
        });
        // location.replace(URL+'report/fifo?item='+x+'&bulan='+y);  
    }
}

function ch_it_tahunan(x, t) {
    if (x === null || x == '') {
        $.alert('Anda belum memilih barang.');
    } else {
        startloading('Mohon tunggu...');
        $('#tb_res').empty();
        $('#res_fifo').hide();
        // $('#tb_res').load(URL + 'report/fifo?tahunan=yes&item=' + x + '&tahun=' + t, function(res, stat, xhr) {
        $('#tb_res').load(URL + 'fifoReport/fifo?tahunan=yes&item=' + x + '&tahun=' + t, function(res, stat, xhr) {
            if (stat == 'success') {
                $('#res_fifo').fadeIn('fast');
                $('#tb_res').html(res);
                endloading();
            }
            if (stat == 'error') {
                $('#res_fifo').fadeIn('fast');
                $('#tb_res').html('<center>Error memuat data. Pastikan koneksi internet anda lancar. Coba lagi.</center>');
                endloading();
            }
        });
        // location.replace(URL+'report/fifo?tahunan=yes&item='+x);  
    }
}

function export_pdf_fifo_bulanan(item, tahun, bulan) {
    window.location.href = URL + 'fifoReport/export_pdf_fifo_bulanan?item=' + item + '&tahun=' + tahun + '&bulan=' + bulan;
}

function export_excel_fifo_bulanan(item, tahun, bulan) {
    window.location.href = URL + 'fifoReport/export_excel_fifo_bulanan?item=' + item + '&tahun=' + tahun + '&bulan=' + bulan;
}

function export_pdf_fifo_tahunan(item, tahun) {
    window.location.href = URL + 'fifoReport/export_pdf_fifo_tahunan?item=' + item + '&tahun=' + tahun + '&tahunan=yes';
}

function export_excel_fifo_tahunan(item, tahun) {
    window.location.href = URL + 'fifoReport/export_excel_fifo_tahunan?item=' + item + '&tahun=' + tahun + '&tahunan=yes';
}