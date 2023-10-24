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
    <div class="container mt-4 mb-4">
      <div class="row">
        <div class="col-md-12">
          <h4 class="text-center">Feedbacks</h4>
          @if ($feedbacks->isEmpty())
          <p class="text-center">No feedback found.</p>
          @else
            @foreach($feedbacks as $feedback)
              <div class="container feedback mb-4">  
                <p class="fw-bold">{{$feedback->title}}</p>
                <p class="feedback_desc">{{$feedback->description}}</p>
                <a href="{{URL::to('/feedback/detail',$feedback->id)}}">Read more of this feedback >></a>
              </div>  
            @endforeach
            <span>
                {{$feedbacks->links()}}
            </span>
          @endif
           
        </div>
      </div>
    </div>
    
@endsection
@section('js_section')
<script type='text/javascript'>
  $(function () {
        
  });
</script>

@endsection