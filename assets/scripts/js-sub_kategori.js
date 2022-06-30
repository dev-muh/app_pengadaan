var URL = $('#BASE_URL').val();
var TYPE = $('#type_pos').val();

function del(id){
    $.confirm({
        title: 'Confirmation!',
        content: 'Delete data?',
        buttons: {
            confirm: function () {     
                startloading('Menghapus Sub Kategori...');           
                $.ajax({ 
                    url: URL + 'produk/sub_kategori/del', 
                    type : "post",      
                    dataType : "json",                                               
                    data:{id:id},
                    error: function(result){      
                        endloading();              
                        console.log(result.responseText);
                        $.alert('Server Error: delete failed');
                        return false;
                    },
                    success: function(result) {  
                        endloading();
                    	console.log(result);                                                                    
                        if(result.status=='success'){
                            
                            window.location = URL + 'produk/sub_kategori/view';
                        }else{
                            $.alert(result.message);
                        }                       
                    }
                }); 
            },
            cancel: function () {               
            }            
        }
    });
}

function edit(el,id){
    var dt = el.val().split(',');
    var md = $('#add-sub_kategori');
    md.find('#id_sub_kat').val(id);
    md.find('#kat_id').val(dt[2]);
    $('.katName').select2({width: '100%'});
    md.find('#code').val(dt[0]);
    md.find('#name_txt').val(dt[1]);
    md.modal('show');
}


$(document).ready(function() {
    var t = $("#tblSubKategori").DataTable({pageLength:100});
    $('.katName').select2({width: '100%'});

    $('#add-sub_kategori').on('hidden.bs.modal', function (e) {
      $(this)
        .find("input,textarea,select")
           .val('')
           .end()
        .find("input[type=checkbox], input[type=radio]")
           .prop("checked", "")
           .end();
        $(this).find("select").prop('selectedIndex',0);
        $(this).find("select").select2({width:'100%'});
    })
 
    $('#save').on( 'click', function () {
        var id_kat = $('#kat_id').val();
        var code = $('#code').val();
        var nama = $('#name_txt').val();

        if($('#add-sub_kategori').find('#id_sub_kat').val()==''){
            startloading('Menambah Sub Kategori...');
            $.ajax({ 
                url: URL + 'produk/sub_kategori/add', 
                type : "post",                                                  
                data:{mode:'add',id_kat:id_kat,code:code,nama:nama},
                success: function(result) {  
                    endloading();
                    window.location = URL + 'produk/sub_kategori/view';
                    var res = $.parseJSON(result);  
                    var cRow = $('.cSubKat').val();
                    t.row.add( [
                        cRow,
                        res.deskripsi_kategori,
                        res.sub_kategori_code,
                        res.sub_description,
                        res.update_by_username,
                        res.update_date,
                        `<button class="btn btn-warning" onclick="edit($(this),`+res.id+`)" value="`+res.sub_kategori_code+`,`+res.sub_description+`,`+res.id_kat+`"><span class="glyphicon glyphicon-edit"></span></button>
                                <button class="btn btn-danger" onclick="del(`+res.id+`)"><span class="glyphicon glyphicon-trash"></span></button>`
                    ] ).draw( false ); 
                    $('.cSubKat').val(parseInt(cRow) + 1);
                    $('#add-sub_kategori').modal('hide');

                },
                error: function(result){    
                    endloading();                
                    alert('error');
                }
                
            });
        }
        else{
            var id_sub_kat = $('#add-sub_kategori').find('#id_sub_kat').val();
            startloading('Mengubah Sub Kategori...');
            $.ajax({ 
                url: URL + 'produk/sub_kategori/edit', 
                type : "post",  
                dataType:"json",
                data:{mode:'add',id_sub_kat:id_sub_kat,id_kat:id_kat,code:code,nama:nama},
                success: function(result) {
                    endloading(); 
                    try {
                        $.alert(result.message);
                    } catch(e) {
                        // statements
                        console.log(e);
                    } finally {
                        window.location = URL + 'produk/sub_kategori/view';
                    }  
                }
            });
        }
    } );
} );