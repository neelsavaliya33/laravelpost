@extends('layouts.front')
@section('content')
    @php
    $userLikeAr = auth()
        ->user()
        ->likes->pluck('post_id')
        ->toArray();
    @endphp
    <div class="container post-container">
        <div class="row">
            @foreach ($posts as $post)

                <div class="col-12 mt-5 post">
                    <div class="px-3">
                        <h4>{{ $post->user->name }}</h4>
                        <p>{{ $post->description }}</p>
                    </div>
                    <div><img src="{{ asset('storage/' . $post->image_path) }}" alt="post image" class="img-fluid"></div>
                    <div class="action-container">
                        <div class="border-left">
                            <span id="like-count-{{ $post->id }}" class="d-block">{{ $post->likes->count() }}</span>
                            <a href="javascript:void(0)" data-id="{{ $post->id }}"
                                class="like {{ in_array($post->id, $userLikeAr) ? 'liked' : '' }}"><i
                                    class="far fa-thumbs-up"></i> Like
                            </a>
                        </div>
                        <div>
                            <a href="javascript:void(0)" data-id="{{ $post->id }}" class="comment"
                                data-id="{{ $post->id }}"><i class="far fa-comments"></i> Comment</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>


    <!-- Modal -->
    <div class="modal fade" id="comment-model" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="comment-haed"><span id="comment-count">0</span> Comment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="comment-container">

                    </div>
                </div>
                <div class="modal-footer">
                    <form action="{{ route('front.post.comment') }}" class="w-100" id="comment-form">
                        <div class="comment-input">
                            <div class="form-group w-100">
                                <input type="text" name="comment" id="comment" class="form-control" placeholder="comment">
                            </div>
                            <div>
                                <button type="submit" id="comment-submit" class="">
                                    <div class="spinner-border text-light" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                    <i class="fas fa-paper-plane"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        $(document).on('click', '.like', function() {
            var id = $(this).data('id');
            var $this = $(this);
            $.ajax({
                type: "get",
                url: url + '/like/' + id,
                dataType: "json",
                success: function(response) {
                    if (response.status) {
                        if (response.type == 0) {
                            $this.addClass('liked');
                        } else {
                            $this.removeClass('liked');
                        }
                        $(`#like-count-${id}`).empty().append(response.count);
                    }
                }
            });
        });

        var myModal = new bootstrap.Modal(document.getElementById('comment-model'), {
            keyboard: false
        })
        var post_id = 0;
        $(document).on('click', '.comment', function() {
            post_id = $(this).data('id');
            $.get(url+'/comment/'+post_id,
                function (data) {
                    var html = '';
                    data.comment.forEach(element => { 
                        html += `<div class="card p-3 mt-2">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <div class="user d-flex flex-row align-items-center">
                                                            <span>
                                                                <small class="font-weight-bold text-primary">@${element.user.name}</small>
                                                                <small class="font-weight-bold">${element.comment}</small>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>`;
                                            });
                    $('#comment-container').empty().append(html);
                    $('#comment-count').empty().append(data.count)
                },
                "json"
            );
            myModal.show();
        });
        $(function() {
            $("#comment-form").validate({
                rules: {
                    comment: "required",
                },
                messages: {
                    comment: {
                        required: "Please enter comment",
                    },
                },
                submitHandler: function(form) {
                    $.ajaxSetup({
                        headers: {
                            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
                        },
                    });
                    $('#comment-submit').attr('disabled', true).addClass('loding');
                    $.ajax({
                        type: "post",
                        url: form.action,
                        data: {
                            comment: $('#comment').val(),
                            post_id
                        },
                        dataType: "json",
                        success: function(response) {
                            if(response.status){

                                var html = `<div class="card p-3 mt-2">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div class="user d-flex flex-row align-items-center">
                                                        <span>
                                                            <small class="font-weight-bold text-primary">@${response.userName}</small>
                                                            <small class="font-weight-bold">${response.comment.comment}</small>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>`;
                                $('#comment-container').prepend(html);
                                $('#comment-count').empty().append(response.count)
                                $("#comment-container").offset().top
                            }else{
                                var notyf = new Notyf();
                                notyf.error({
                                    message: 'something went wrong please try again later',
                                    duration: 5000,
                                    position: {
                                        x: 'center',
                                        y: 'top',
                                    },
                                });
                            }
                            $('#comment-form')[0].reset();
                         
                            $('#comment-submit').attr('disabled', false).removeClass('loding');
                        },

                    });
                }
            });
        });

    </script>
@endsection
