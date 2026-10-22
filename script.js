$(document).ready(function (){

    function sembunyikanAlert(){
        $('.alert-notif').delay(3000).fadeOut(500);
    }

    function tampilAlert(jenis, pesan){
        var alertBaru = $('<div class="alert alert-notif"></div>')
            .addClass('alert-' + jenis)
            .html(pesan);

        $('#area-alert').empty().append(alertBaru);
        sembunyikanAlert();
    }

    sembunyikanAlert();

    if (window.location.search.indexOf('status=sukses') !== -1){
        window.history.replaceState(null, '', window.location.pathname);
    }

    $('#form-barang').on('submit', function (e){
        var nama = $.trim($('#form-barang [name="nama_barang"]').val());
        var kategori = $('#form-barang [name="id_kategori"]').val();
        var harga = $('#form-barang [name="harga"]').val();
        var stok = $('#form-barang [name="stok"]').val();
        var pesan = [];

        if (nama === ''){
            pesan.push('Nama barang wajib diisi.');
        }
        if (!kategori){
            pesan.push('Kategori wajib dipilih.');
        }
        if (harga === '' || harga <= 0){
            pesan.push('Harga harus lebih dari 0.');
        }
        if (stok === '' || stok <= 0){
            pesan.push('Stok harus lebih dari 0.');
        }

        if (pesan.length > 0){
            e.preventDefault();
            tampilAlert('danger', pesan.join('<br>'));
        }
    });

});