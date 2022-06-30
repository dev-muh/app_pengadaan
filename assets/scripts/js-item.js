
var URL = $('#BASE_URL').val();
var TYPE = $('#type_pos').val();

function previewFile(x) {
    
    var file = "";
    var preview = x.parent().find('img'); //selects the query named img
    file = (x)[0].files[0]; //sames as here
    var reader = new FileReader();
    reader.onloadend = function() {
        preview.attr('src', reader.result);
    }

    if (file) {
        reader.readAsDataURL(file); //reads the data as a URL
    } else {
        preview.src = "";
    }
}


function c_barcode(){
    startloading('Generate Barcode...');
    $.ajax({
        url: URL + 'produk/generate_barcode',
        type: "post",
        success:function(data){
            endloading();
            //alert(data);
            $('#it_barcode').val(data);
        },error:function(e){
            endloading();
            console.log(e);
        }
    });
}

function del(id) {
    
    $.confirm({
        title: 'Confirmation!',
        content: 'Inactive Item?',
        buttons: {
            confirm: function() {
                startloading('Menonaktifkan item...');
                $.ajax({
                    url: URL + 'produk/item/del',
                    type: "post",
                    dataType: "json",
                    data: {
                        id: id
                    },
                    error: function(result) {
                        endloading();
                        console.log(result.responseText);
                        $.alert('Server Error: delete failed');
                        return false;
                    },
                    success: function(result) {
                        endloading();
                        console.log(result);
                        if (result.status == 'success') {

                            window.location = URL + 'produk/item/view';
                        } else {
                            $.alert(result.message);
                        }
                    }
                });
            },
            cancel: function() {}
        }
    });
}

function activate(id) {
    
    $.confirm({
        title: 'Confirmation!',
        content: 'Activate Item?',
        buttons: {
            confirm: function() {
                startloading('Mengaktifkan Item...');
                $.ajax({
                    url: URL + 'produk/item/activate',
                    type: "post",
                    dataType: "json",
                    data: {
                        id: id
                    },
                    error: function(result) {
                        endloading();
                        console.log(result.responseText);
                        $.alert('Server Error: delete failed');
                        return false;
                    },
                    success: function(result) {
                        endloading();
                        console.log(result);
                        if (result.status == 'success') {

                            window.location = URL + 'produk/item/view';
                        } else {
                            $.alert(result.message);
                        }
                    }
                });
            },
            cancel: function() {}
        }
    });
}

function edit(el, id) {
    
    

    var dt = el.val().split('|');
    var md = $('#add-item');

    md.find('#it_barcode').val(dt[0]);
    md.find('#it_nama').val(dt[1]);
    // md.find('#it_selling_price').val(dt[4]);
    // md.find('#it_selling_cost').val(dt[5]);
    // md.find('#it_cost_percentage').val(dt[6]);
    md.find('#id_item').val(id);
    md.find('#it_qty').val(dt[4]).attr('disabled',true);

    md.find('#it_sat').val(dt[5]).attr('disabled',true);
    md.find('#it_sat_des').val(dt[6]).attr('disabled',true);

    md.find('#it_min_qty').val(dt[7]).attr('disabled',false);
    md.find('#it_max_qty').val(dt[8]).attr('disabled',false);

    

    $.ajax({
        type:'POST',
        url:URL + 'produk/getPhoto',
        data:{id:id},
        beforeSend:function(){
            startloading('Loading Image');
        },
        success:function(data){

            if (data != '') {
                var jParse = JSON.parse(data);
                try {
                            
                    $('.photo').eq(0).attr('src', URL + 'assets/img/' + jParse[0].img_name);

                    
                    $('.photo').eq(1).attr('src', URL + 'assets/img/' + jParse[1].img_name);

                    
                    $('.photo').eq(2).attr('src', URL + 'assets/img/' + jParse[2].img_name);

                    
                } catch (e) {
                    
                    console.log(e);   
                }
                
            }else{
                endloading();
            }
        },error:function(e){
            $.alert('Connection Error <br>Error Getting Images');
            endloading();
        },complete:function(){
            endloading();
        }
    });


    md.find('#kat_id').val(dt[3]);
    setSubFromEdit(md.find('#kat_id').prop('selectedIndex'), dt[2]);

    $('.select2').select2({
        width: '100%'
    });
    md.find('#code').val(dt[0]);
    md.find('#name_txt').val(dt[1]);
    md.modal('show');
}

function setSubFromEdit(x, idSub) {
    
    var subSel = $('#sub_kat_id');
    subSel.html('');
    indexKat = x;
    sub[indexKat].forEach(function(element, index) {
        subSel.append('<option value=' + sub[indexKat][index].id + '>' + sub[indexKat][index].sub_description + '</option>')
    });
    $('#add-item').find('#sub_kat_id').val(idSub);
}

function setSub(x) {
    
    var subSel = $('#sub_kat_id');
    subSel.html('');
    indexKat = x.prop('selectedIndex');
    sub[indexKat].forEach(function(element, index) {
        subSel.append('<option value=' + sub[indexKat][index].id + '>' + sub[indexKat][index].sub_description + '</option>')
    });
}

function setSubFirst() {
    
    var subSel = $('#sub_kat_id');
    
    subSel.html('');
    
    indexKat = 0;
    
    //if(indexKat>0){
        sub[indexKat].forEach(function(element, index) {
            
            subSel.append('<option value=' + sub[indexKat][index].id + '>' + sub[indexKat][index].sub_description + '</option>')
            
        });
    //}
    
    $('.katName').select2();
    
}

function uploadImage(id) {
    
    $.ajax({
        url: URL + 'produk/upload/' + id, // Url to which the request is send
        type: "POST", // Type of request to be send, called as method
        data: new FormData($('form')[0]), // Data sent to server, a set of key/value pairs (i.e. form fields and values)
        contentType: false, // The content type used when sending data to the server.
        cache: false, // To unable request pages to be cached
        processData: false, // To send DOMDocument or non processed data file it is set to false
        beforeSend:function(){
            startloading('Mohon tunggu, sedang mengupload gambar...');
        },
        success: function(data) {

        },complete:function(){
            endloading();
        },error:function(e){
            endloading();
        }
    });
}

$(document).ready(function() {
    setSubFirst();
    var t = $("#tblItem").DataTable({pageLength:100});
    $('.select2').select2({
        width: '100%'
    });
    // $('.subKatName').select2({width: '100%'});

    $('#add-item').on('hidden.bs.modal', function(e) {
        setSubFirst();
        $(this)
            .find("input,textarea,select")
            .val('')
            .end()
            .find("input[type=checkbox], input[type=radio]")
            .prop("checked", "")
            .end();
        $(this).find("select").prop('selectedIndex', 0);
        $(this).find("#it_qty").prop('disabled', false);
        $(this).find("#it_sat").prop('disabled', false);
        $(this).find("#it_sat_des").prop('disabled', false);
        $(this).find("select").select2({
            width: '100%'
        });
        $('img.photo').attr('src', URL + 'assets/img/add.png');
    });


    $('#save').on('click', function() {

        // var id_kat = $('#kat_id').val();
        // var code = $('#code').val();
        var barcode = $('#it_barcode').val();
        var nama = $('#it_nama').val();
        var kat_id = $('#kat_id').val();
        var sub_kat_id = $('#sub_kat_id').val();
        var it_qty = $('#it_qty').val();
        var it_min_qty = $('#it_min_qty').val();
        var it_max_qty = $('#it_max_qty').val();
        var it_sat = $('#it_sat').val();
        var it_sat_des = $('#it_sat_des').val();

        var inp = $('.req').toArray();
        $('.req').css({"background":"white"});
        var firstInp = [];

        inp.forEach(function(val,i){

            if($('.req:eq('+i+')').val()==''||$('.req:eq('+i+')').val()==null){
                firstInp.push(i);
                $('.req:eq('+i+')').css({"background-color":"red"});
                $('.req:eq('+firstInp[0]+')').focus();
                if(!$('.req:eq('+i+')').hasAttribute('disabled')){
                    $('.req:eq('+i+')').animate({ 'background-color': 'rgba(255, 255, 255,1)' },1000);
                }
            }
        });

        // var selling_price = $('#it_selling_price').val();
        // var selling_cost = $('#it_selling_cost').val();
        // var cost_percentage = $('#it_cost_percentage').val();

        if(firstInp.length==0){
            startloading('Menambah item...');
            $.post(URL + 'produk/cekBarcodeItem/' + barcode).done(function(data, textStatus, xhr) {
                endloading();
                if (data == 'ADA' && $('#add-item').find('#id_item').val() == '') {

                    $.alert({
                        title: 'Warning!',
                        content: 'Barcode Sudah terdaftar'
                    });


                } else {
                    if (data == 'TIDAK' || $('#add-item').find('#id_item').val() != '') {
                        if (barcode == '' ||
                            nama == '' ||
                            kat_id == '' ||
                            sub_kat_id == ''
                            // selling_price == '' ||
                            // selling_cost == '' ||
                            // cost_percentage == ''
                        ) {
                            $.alert('Data tidak Lengkap');
                        } else {
                            if ($('#add-item').find('#id_item').val() == '') {
                                startloading('Mengirim Form...');
                                $.ajax({
                                    url: URL + 'produk/item/add',
                                    type: "post",
                                    data: {
                                        mode: 'add',
                                        barcode: barcode,
                                        nama: nama,
                                        kat_id: kat_id,
                                        sub_kat_id: sub_kat_id,
                                        qty: it_qty,
                                        min_qty:it_min_qty,
                                        max_qty:it_max_qty,
                                        sat: it_sat,
                                        sat_des: it_sat_des
                                            // selling_price:selling_price,
                                            // selling_cost:selling_cost,
                                            // cost_percentage:cost_percentage
                                    },
                                    success: function(result) {
                                        endloading();
                                        var res = $.parseJSON(result);
                                        var cRow = $('.cItem').val();
                                        //var uImg = uploadImage(res.ID_ITEM);

                                        //var JuImg = JSON.parse(uImg);
                                        var k = $.ajax({
                                            url: URL + 'produk/upload/' + res.ID_ITEM, // Url to which the request is send
                                            type: "POST", // Type of request to be send, called as method
                                            data: new FormData($('form')[0]), // Data sent to server, a set of key/value pairs (i.e. form fields and values)
                                            contentType: false, // The content type used when sending data to the server.
                                            cache: false, // To unable request pages to be cached
                                            processData: false, // To send DOMDocument or non processed data file it is set to false
                                            beforeSend: function() {
                                                startloading('Mohon tunggu. Sedang mengupload gambar..');
                                            },
                                            success: function(data) {
                                                endloading();
                                                $.alert({
                                                    title:'Success!',
                                                    content:'Sukses Menambahkan Item',
                                                    buttons:{
                                                        ok:{
                                                            text:"OK",
                                                            action:function(){
                                                                window.location = URL + 'produk/item/view';                
                                                            }
                                                        }
                                                    }
                                                });
                                                t.row.add([
                                                    cRow,
                                                    res.barcode,
                                                    res.nama_item,
                                                    res.jenis_item,
                                                    res.kategori_item,
                                                    res.qty + ' ' + res.satuan,
                                                    // res.selling_cost,
                                                    // res.cost_percentage,
                                                    res.update_by_username,
                                                    res.update_date,'',
                                                    `<center><button class="btn btn-warning" onclick="edit($(this),` + res.ID_ITEM + `)" value="` + res.barcode + `|` + res.nama_item + `|` + res.id_sub + `|` + res.id_kat + `|` + res.qty +
                                                    // +res.selling_price+`|`
                                                    // +res.selling_cost+`|`
                                                    // +res.cost_percentage+
                                                    `"><span class="glyphicon glyphicon-edit"></span></button>
                                                                <button class="btn btn-danger" onclick="del(` + res.ID_ITEM + `)"><span class="glyphicon glyphicon-trash"></span></button>
                                                                <a href="`+ URL + `produk/printbarcode?id=`+ res.ID_ITEM +`"><button data-toggle="tooltip" title="Print Barcode" class="btn btn-success"><span class="glyphicon glyphicon-print"></span></button></a></center>`
                                                ]).draw(false);

                                                $('.cItem').val(parseInt(cRow) + 1);
                                                $('#add-item').modal('hide');
                                            },
                                            complete: function() {
                                                endloading();
                                            }
                                        });
                                    },
                                    error: function(result) {
                                        endloading();
                                        alert('error');
                                    }

                                });
                            } else {
                                var id_item = $('#add-item').find('#id_item').val();
                                startloading('Mengubah data...');
                                $.ajax({
                                    url: URL + 'produk/item/edit',
                                    type: "post",
                                    dataType: "json",
                                    data: {
                                        id: id_item,
                                        mode: 'add',
                                        barcode: barcode,
                                        nama: nama,
                                        kat_id: kat_id,
                                        sub_kat_id: sub_kat_id,
                                        qty: it_qty,
                                        min_qty:it_min_qty,
                                        max_qty:it_max_qty,
                                        sat: it_sat,
                                        sat_des: it_sat_des
                                            // selling_price:selling_price,
                                            // selling_cost:selling_cost,
                                            // cost_percentage:cost_percentage
                                    },
                                    
                                    success: function(result) {
                                        endloading();
                                        var k = $.ajax({
                                            url: URL + 'produk/upload/' + id_item, // Url to which the request is send
                                            type: "POST", // Type of request to be send, called as method
                                            data: new FormData($('form')[0]), // Data sent to server, a set of key/value pairs (i.e. form fields and values)
                                            contentType: false, // The content type used when sending data to the server.
                                            cache: false, // To unable request pages to be cached
                                            processData: false, // To send DOMDocument or non processed data file it is set to false
                                            beforeSend: function() {
                                                startloading('Mohon tunggu. Sedang mengupload gambar..');
                                            },
                                            success: function(data) {
                                                endloading();
                                                $.alert({
                                                    title:'Success!',
                                                    content:'Sukses Mengubah Item',
                                                    buttons:{
                                                        ok:{
                                                            text:"OK",
                                                            action:function(){
                                                                window.location = URL + 'produk/item/view';                
                                                            }
                                                        }
                                                    }
                                                });
                                                
                                            },
                                            complete: function() {
                                                endloading();
                                            }
                                        });

                                    }
                                    
                                });
                            }
                        }
                    } else {
                        $.alert({
                            title: 'Error!',
                            content: 'Silahkan muat ulang halaman dan coba lagi'
                        });
                    }
                }
            }).fail(function(){
                endloading();
            });
        }else{
            // $.alert({
            //     title:'Warning!',
            //     content: 'Data tidak lengkap. Mohon cek ulang form.',
            //     buttons:{
            //         ok:{
            //             text:"OKE"
            //         }
            //     }
            // });
        }


    });
});