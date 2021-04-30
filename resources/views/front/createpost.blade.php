@extends('layouts.front')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12 col-md-6 offset-md-3">
                <form action="{{ route('front.post.create') }}" id="post-form" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="form-group mt-3">
                        <label for="">Post Description</label>
                        <textarea name="description" class="form-control">{{ old('description') }}</textarea>
                        @error('description')
                            <span class="error">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group mt-5">
                        <label for="">Post Image</label>
                        <input type="file" class="dropify" name="post_image" accept="image/*">
                        @error('post_image')
                            <span class="error">{{ $message }}</span>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-theme mt-3" id="post-submit">
                        <div class="spinner-border text-light" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <span>Submit</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        $('#post-form').on('submit', function() {
            $('#post-submit').attr('disabled', false).removeClass(
                'loding');
        });

    </script>
@endsection
