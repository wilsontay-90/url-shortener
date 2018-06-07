@extends('layout.master')

@section('contents')

    <div class="center-container jumbotron">
        <div class="col-sm-12">

            {{ session('success') }}
            @include('layout.include.successbar')
            @include('layout.include.alertbar')

            <h3>Simplify your links</h3>

            <input type="text" name="url" value="{{ old('url') }}" placeholder="Your original URL here" class="form-control"/>

            <button type="submit" class="btn btn-block btnShortenURL">Shorten URL</button>

        </div>
    </div>

@endsection

@section('after_styles')
    <style>
        .btnShortenURL{
            margin-top: 30px;
            padding: 15px;
        }

        .jumbotron {
            position: absolute;
            top: 50%;
            left:50%;
            transform: translate(-50%,-50%);
        }
    </style>
@endsection

@section('after_scripts')
    <script>
        // Set default tab
        $(document).ready(function(){

            $(".btnShortenURL").click(function(){
                $('.btnShortenURL').LoadingOverlay('show');

                axios.post("{{ route('doSimplyUrl') }}", {'url' : $('input[name="url"]').val()})
                .then(function (response) {
                    if(response.data.status == 1){
                        swal(response.data.url,response.data.bitly,'success', {
                            buttons: {
                                copy: true,
                                cancel: "Cancel",
                            },
                        })
                        .then((value) => {
                            switch (value) {
                                case "copy":
                                    $('input[name="url"]').val(response.data.bitly);
                                    $('input[name="url"]').select();
                                    document.execCommand("copy");
                                    swal("Copied");
                                break;
                            }
                            $('.btnShortenURL').LoadingOverlay('hide');
                        });
                    }else{
                        swal({
                            text: response.data.message,
                            icon: "warning",
                            buttons: true,
                            dangerMode: true,
                        });
                        $('.btnShortenURL').LoadingOverlay('hide');
                    }
                });
            });
        });
    </script>
@endsection