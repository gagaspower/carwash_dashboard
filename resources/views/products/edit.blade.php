@extends('welcome')
@section('content')
<div class="row">
    <div class="col-md-6">
        <h5 class="card-title fw-semibold mb-4">Edit Edit Produk</h5>
        <div class="card">
            <div class="card-body">
                <form>
                    <div class="mb-3">
                        <label for="product_name" class="form-label">Nama</label>
                        <input type="text" class="form-control" id="product_name" name="product_name"
                            value="{{$current->product_name}}">
                    </div>
                    <div class="mb-3">
                        <label for="product_price" class="form-label">Harga Produk</label>
                        <input type="text" class="form-control" id="product_price" name="product_price"
                            value="{{$current->product_price}}">
                    </div>
                    <div class="mb-3">
                        <label for="product_note" class="form-label">Catatan</label>
                        <textarea class="form-control" id="product_note"
                            name="product_note">{{$current->product_note}}</textarea>
                    </div>
                    <button type="button" class="btn btn-primary" id="product_save">Update</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
<script>
    let numbericOnly = /^[0-9]*$/;
    $('#product_save').click(function(){

    const product_name = $('#product_name').val();
    const product_price = $('#product_price').val();
    const product_note = $('#product_note').val();
    const currentId = "{{ $current->id }}";

    if(product_name === null || product_name === ''){
        Swal.fire({
            icon: "error",
            title: "Oops...",
            text: "Nama produk wajib diisi",
        });
        return "";
    }
    if(product_price === null || product_price === ''){
        Swal.fire({
            icon: "error",
            title: "Oops...",
            text: "Harga produk wajib diisi",
        });
        return "";
    }
    if(product_price && numbericOnly.test(product_price) === false){
        Swal.fire({
            icon: "error",
            title: "Oops...",
            text: "Harga produk hanya angka",
        });
        return "";
    }

        $.ajax({
            type: "PUT",
            url: "{{url('/product/update')}}/" + currentId,
            data: {
                _token: "{{ csrf_token() }}",
                product_name: product_name,
                product_price : product_price,
                product_note : product_note
            },
            beforeSend : function() {
                Swal.fire({
                    title:'Menyimpan',
                    text: 'Mohon Tunggu...',
                    allowOutsideClick: false,
                    didOpen: function() {
                        Swal.showLoading()
                    }
                })
            },
            success: function (data) {
                Swal.fire({
                    title: "Sukses!",
                    text: "Data berhasil diupdate.",
                    icon: "success"
                });
            },
            error: function(data){
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Something when wrong!",
                });
            }
        });

    });


</script>
@endsection