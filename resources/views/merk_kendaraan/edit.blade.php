@extends('welcome')
@section('content')
<div class="row">
    <div class="col-md-6">
        <h5 class="card-title fw-semibold mb-4">Edit Merk Kendaraan</h5>
        <div class="card">
            <div class="card-body">
                <form>
                    <div class="mb-3">
                        <label for="brand_name" class="form-label">Nama</label>
                        <input type="text" class="form-control" id="brand_name" name="brand_name"
                            value="{{$current->brand_name}}">
                    </div>
                    <button type="button" class="btn btn-primary" id="brand_save">Update</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
<script>

    $('#brand_save').click(function(){

        const brand_name = $('#brand_name').val();
        const currentId = "{{ $current->id }}";

        if(brand_name === null || brand_name === ''){
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "Nama wajib diisi",
            });
            return "";
        }


        $.ajax({
            type: "PUT",
            url: "{{url('/merk-kendaraan/update')}}/" + currentId,
            data: {
                _token: "{{ csrf_token() }}",
                brand_name: brand_name
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
                    text: "Data merk berhasil disimpan.",
                    icon: "success"
                });
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


</script>
@endsection