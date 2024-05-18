@extends('layout.app')
@section('title', 'Contents')
@section('contents-acitve', 'active')
@section('content')
    <section class="content-header">
        <div class="card">
            <div class="card-header">
                <div class="mb-2 mb-md-0">
                    <input type="hidden" value="{{ $content->id }}" id="content_id">
                    <p class="mb-0">Created by: &nbsp; <a href="{{ url('users/'.$content->user->id) }}">{{ $content->user->name }}</a>.</p>
                    <p class="mb-0">Created on: &nbsp; {{ $content->created_at }}.</p>
                </div>
                <div class="float-md-right">
                    <a href="{{ url('reacts/'.$content->id) }}" class="btn border position-relative">
                        Reacts
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                          {{ count($content->reacts) }}
                        </span>
                    </a>

                    <a href="{{ url('comments/'.$content->id) }}" class="btn border position-relative">
                        Comments
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                          {{ count($content->comments) }}
                        </span>
                    </a>

                    <button class="btn btn-danger" id="deleteContent">Delete</button>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        @if (!empty($content->image))
                            <a href="{{ url('storage/contents/'.$content->image) }}" target="blank"><img class="w-100" src="{{ asset('storage/contents/'.$content->image) }}" alt=""></a>
                        @else
                            <p class="text-center text-lg">No content photo.</p>
                        @endif
                    </div>
                    <div class="col-md-6">
                        <h4>{{ $content->title }}</h4>
                        <p>{{ $content->content }}</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('extra-script')
<script>
    $(document).ready(function(){
        $('#deleteContent').click(function(){
            $content_id = $('#content_id').val();
            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: "btn btn-success",
                    cancelButton: "btn btn-danger m-2"
                },
                buttonsStyling: false
                });
                swalWithBootstrapButtons.fire({
                title: "Are you sure?",
                text: "You want to delete this content.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Delete",
                cancelButtonText: "Cancel",
                reverseButtons: true
                }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                    type: 'get',
                    data: {'id': $content_id},
                    url: "{{ url('delete-content') }}",
                    success: response => {
                        if(response.status == 200){
                            swalWithBootstrapButtons.fire({
                            title: "Removed!",
                            icon: "success"
                            });
                            window.location.href = "{{ url('contents') }}";
                        }else{
                            Swal.fire({
                                icon: "error",
                                title: "Oops...",
                                text: "Something went wrong!",
                            });
                        }
                    }
                    });

                } else if (
                    /* Read more about handling dismissals below */
                    result.dismiss === Swal.DismissReason.cancel
                ) {
                    swalWithBootstrapButtons.fire({
                    title: "Cancelled",
                    text: "Your imaginary file is safe :)",
                    icon: "error"
                    });
                }
                });
        });
    });
</script>
@endsection
