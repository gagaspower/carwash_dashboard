@extends('welcome')
@section('content')
<div class="row">
    <div class="col-md-6">
        <h5 class="card-title fw-semibold mb-4">Tambah Pelanggan</h5>
            <div class="card">
              <div class="card-body">
                <form>
                  <div class="mb-3">
                    <label for="customer_name" class="form-label">Nama</label>
                    <input type="text" class="form-control" id="customer_name" name="customer_name">
                  </div>
                  <div class="mb-3">
                    <label for="customer_phone" class="form-label">No. Tlp</label>
                    <input type="text" class="form-control" id="customer_phone" name="customer_phone">
                  </div>
                   <div class="mb-3">
                    <label for="customer_address" class="form-label">Alamat</label>
                    <textarea class="form-control" id="customer_address" name="customer_address"></textarea>
                  </div>
                  <button type="button" class="btn btn-primary" id="customer_save">Submit</button>
                </form>
              </div>
            </div>
    </div>
</div>
@endsection
@section('script')
<script>

    let numberOnly = '/[^0-9]/g';
    $('#customer_save').click(function(){

        const customer_name = $('#customer_name').val();
        const customer_address = $('#customer_address').val();
        const customer_phone = $('#customer_phone').val();

        if(customer_name === null || customer_name === ''){
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "Nama wajib diisi",
            });
            return "";
        }
        if(customer_phone && customer_phone.match(numberOnly) === false){
                Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "Nomor Telp hanya berupa angka",
            });
            return "";
        }
        if(customer_phone && customer_phone.length < 9){
                Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "Nomor Telp Terlalu pendek",
            });
            return "";
        }
        if(customer_phone.length > 13){
                Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "Nomor Telp Terlalu panjang",
            });
            return "";
        }

        $.ajax({
            type: "POST",
            url: "{{url('/customer/add/store')}}",
            data: {
                _token: "{{ csrf_token() }}",
                customer_name: customer_name,
                customer_phone : customer_phone,
                customer_address : customer_address
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
                    text: "Data pelanggan berhasil disimpan.",
                    icon: "success"
                });
                clearForm();
            },
            error: function(data){
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Something went wrong!",
                });
            }
        });

    });


    function clearForm() {
      $('#customer_name').val('');
       $('#customer_address').val('');
       $('#customer_phone').val('');
    }


</script>
@endsection