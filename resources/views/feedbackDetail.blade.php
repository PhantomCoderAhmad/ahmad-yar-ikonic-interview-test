@extends('layouts.app')
@section('css_section')
<style>
  .error {
    color: red;
    font-size: 14px;
}
    @media screen and (max-width:540px){
      .centerOnMobile {
        text-align:center
      }
    }
    
</style>
@endsection
@section('content')
    @include('layouts.header')
    <div class="container-fluid bg-secondary p-3">
      @if(!Auth::check())
        <P class="text-white text-center">you want to add Feedback? Click Here to login before Add Feedback. <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a></P>
      @endif
      
      @if(Auth::check())
      <div class="container-fluid text-end">
        <a  type="button" class="btn btn-primary " href="{{route('add-feedback')}}"> Add New Feedback</a>
      </div>
      @endif
    </div>  
    <div class="container alert alert-success d-none success_message mt-4"></div>
    <div class="container alert alert-danger d-none danger_message mt-4"></div>
    <div class="container mt-4 mb-4">
      <div class="row">
        <div class="col-md-12">
            <div class="container feedback mb-4">  
                <p class="fw-bold">{{$feedback->title}}</p>
                <p class="feedback_desc">{{$feedback->description}}</p>
                @if(Auth::check())
                    @if(count($feedback->votes) > 0)
                    <p class="text-end">You already place your vote.</p>
                    @else
                    <div class="vote text-end" >
                        <a href="#" onclick="Vote('1','{{$feedback->id}}')"><i class="far fa-thumbs-up"></i></a>
                        <a href="#" onclick="Vote('0','{{$feedback->id}}')"><i class="far fa-thumbs-down"></i></a>                   
                    </div>
                    @endif
                @else
                    <P class=" text-center">Login before place your vote. <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a></P>
                @endif
                
 
            </div>
        </div>
      </div>
    </div>

    <div class="container">
        @foreach($feedback->comments as $comment)
            <p class="fw-bold">Commented by:</p> 
            <div class="row">
                <div class="col-md-6">
                    {{$comment->name}}
                </div>
                <div class="col-md-6 text-end">
                    <span>posted on: </span><span>{{$comment->created_at}}</span>
                </div>
            </div> 
            <p class="fw-bold">Comment:</p>
            <p>{{$comment->comment}}</p>
            <hr>
        @endforeach
    </div>
    <div class="container">
        @if(!Auth::check())
            <P class=" text-center">you want to add Comment? Click Here to login before Add Feedback. <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a></P>
        @else
            @if(Auth::check() && $feedback->is_commentable == 0)
                <p class="text-center">Comments are Blocked by Admin for this Feedback.</p>        
            @else
                <h4 class="modal-title text-center">Add Comment</h4>
                @if (\Session::has('success'))
                <div class="alert alert-dismissable alert-success session_success">
                    <button type="button" class="close" style="float: right;" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                        {!! session()->get('success') !!}
                </div>
                @endif
                @if (\Session::has('error'))
                <div class="alert alert-dismissable alert-danger session_error">
                        <button type="button" class="close" style="float: right;" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                            {!! session()->get('error') !!}
                    </div>
                @endif
                <form action="{{ route('save-comment') }}" method="post" id="add_comment">
                    @csrf
                    <div class="row">
                        <input type="hidden" name="feedback_id" value="{{$feedback->id}}">
                        <div class="col-md-12 mt-2">
                            <label  class="form-label" for="title">Name:</label>
                            <input type="text" class="form-control" id="name" value="{{Auth::user()->name}}" placeholder="Enter feedback title" name="name">
                            <span class="text-danger validation_error"></span>
                        </div>
                        <div class="col-md-12 mt-2">
                            <label for="date" class="form-label">Date:</label>
                            <input type="date" class="form-control disabled" id="date" name="date" value="<?= date('Y-m-d'); ?>">
                            
                        </div>
                        <div class="col-md-12 mt-2">
                            <label for="description" class="form-label ">Comment:</label>
                            <textarea id="comment" name="comment"></textarea>
                            <span class="text-danger validation_error"></span>
                        </div>
                    </div>
                    <div class="container  text-end mt-4">
                        <input type="submit" id="saveBtn" class="btn btn-primary " value="Save changes">
                    </div>
                </form>
               
            @endif
        
        @endif
        
    </div>



@endsection
@section('js_section')
<script type='text/javascript'>
  $(function () {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
});
    
    function Vote(vote,id)
    {
        var url = '{{ route("post-vote") }}';
        $.ajax({
            url: url,
            type:"POST",
            dataType: 'json',
            data:{
                vote: vote,
                id: id,
            },
            success: function (response) {
                $('.success_message').html(response.message);
                $('.success_message').removeClass('d-none');
                $('.vote').addClass('d-none');
                setTimeout(function() {
                    $('.success_message').addClass('d-none');
                }, 2000);
            },
            error: function (response) {
                $('.danger_message').html("Sorry! Something is Wrong.");
                $('.danger_message').removeClass('d-none');
                $('.vote_placed_message').removeClass('d-none');
                $('#saveBtn').val('Save Changes');
                setTimeout(function() { 
                    $('.danger_message').addClass('d-none');
                }, 2000);
            }


        });
    }
   
    tinymce.init({
        selector: "textarea#comment", // Use a textarea element as your comment
        plugins: "textcolor emoticons",
        toolbar: "bold italic underline forecolor emoticons",
        content_css: "https://cdn.tiny.cloud/1/no-api-key/tinymce/5/tinymce.min.css", // Use a TinyMCE content CSS
        deprecation_warnings: false, 
        toolbar_location: "top",
        menubar: false,
        statusbar: false,
        forced_root_block: "false",

        setup: function (comment) {
            comment.on('change', function () {
                comment.save(); // Trigger the TinyMCE save method on change
            });
        }
    });

    $("#add_comment").validate({
            onkeyup: function(element) {
                var element_id = $(element).attr('id');
                if (this.settings.rules[element_id].onkeyup !== false) {
                    $.validator.defaults.onkeyup.apply(this, arguments);
                }
            },
            rules: {
                name: {
                    required: true,
                    onkeyup: false
                },
                comment: {
                    required: true,
                    minlength: 10
                }
            },
            messages: {
                comment: {
                    required: "Please enter your comment.",
                    minlength: "Comment must be at least 10 characters."
                }
            }
        });
        setTimeout(function() { 
            $('.session_success').hide();
            $('.session_error').hide();
        }, 2000);
</script>

@endsection