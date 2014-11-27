<!DOCTYPE html>
<html lang="">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- Bootstrap CSS -->
	<link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="{{ asset('css/jquery.dataTables.min.css') }}">

	<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
	<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
	<![endif]-->
	
	@yield('head')

	<style type="text/css">

      /* Sticky footer styles
      -------------------------------------------------- */

      html,
      body {
        height: 100%;
        /* The html and body elements cannot have any padding or margin. */
      }

      /* Wrapper for page content to push down footer */
      #wrap {
        min-height: 100%;
        height: auto !important;
        height: 100%;
        /* Negative indent footer by it's height */
        margin: 0 auto -60px;
      }

      /* Set the fixed height of the footer here */
      #push,
      #footer {
      	padding-top: 1%;
      }
      #footer {
        background-color: #f5f5f5;
      }

      /* Lastly, apply responsive CSS fixes as necessary */
      @media (max-width: 767px) {
        #footer {
          margin-left: -20px;
          margin-right: -20px;
          padding-left: 20px;
          padding-right: 20px;
        }
      }



      /* Custom page CSS
      -------------------------------------------------- */
      /* Not required for template or sticky footer method. */

      .container {
        width: auto;
        max-width: 1000px;
      }
      .container .credit {
        margin: 20px 0;
      }
    </style>	
  </head>
  <body>
    <div class="navbar navbar-default">
     <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-responsive-collapse">
       <span class="icon-bar"></span>
       <span class="icon-bar"></span>
       <span class="icon-bar"></span>
     </button>
     <a class="navbar-brand" href="#">LeafBox</a>
   </div>
   <div class="navbar-collapse collapse navbar-responsive-collapse">
    <ul class="nav navbar-nav">
      <!-- class="active" -->
      <li><a href="{{ URL::to('/') }}">ค้นหา</a></li>
      <li><a href="#">สมาชิก</a></li>
      <li class="dropdown">

        <a href="#" class="dropdown-toggle" data-toggle="dropdown">จัดการหนังสือ <b class="caret"></b></a>
        <ul class="dropdown-menu">
         <li><a href="{{Url::to('/book/add')}}">เพิ่ม</a></li>
       </ul>
     </li>
   </ul>
   <form class="navbar-form navbar-left">
     <input class="form-control col-lg-8" placeholder="ค้นหา" type="text">
   </form>
   <ul class="nav navbar-nav navbar-right">
     <li class="dropdown">

      @if(Auth::check())
      <a href="#" class="dropdown-toggle" data-toggle="dropdown">{{ Auth::user()->name }}<b class="caret"></b></a>
      <ul class="dropdown-menu">
        <li><a href="#">Profile</a></li>
        <li><a href="#">Setting</a></li>
        <li class="divider"></li>
        <li><a href="{{ URL::to('logout') }}">Log out</a></li>
      </ul> 
      @else
      <li data-toggle="modal" data-target="#myModal" ><a> Sign in </a></li>
      @endif
      
    </li>
  </ul>
</div>
</div>
<div id ="wrap">
 <div class="container-fluid">
   @if ($errors->has())
   <div class="alert alert-danger" role="alert">
     <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
     {{ $errors->first('email') }}
     {{ $errors->first('password') }}
   </div>
   @else
   @endif
   <div class = "col-md-10 col-md-offset-1">
     @yield('body')
   </div>
 </div>
</div>

<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
 <div class="modal-dialog">
   <div class="modal-content">
     <div class="modal-header">
       <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
       <h4 class="modal-title" id="myModalLabel">Login</h4>
     </div>
     {{ Form::open(array('url' => 'loginUser')) }}
     <div class="modal-body">
      <p>
       {{ Form::label('email', 'Email Address') }}
       {{ Form::text('email', Input::old('email'), array('placeholder' => 'Your Email','class'=> 'form-control')) }}
     </p>
     <p>
       {{ Form::label('password', 'Password') }}
       {{ Form::password('password',array('class'=> 'form-control')) }}
     </p>
   </div>
   <div class="modal-footer">
     <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
     {{ Form::submit('Submit!',array('class'=> 'btn btn-success')) }}
   </div>
   {{ Form::close() }}
 </div>
</div>
</div>


<div id="footer">
  <div class="container-fluid">
   <div class = "col-md-1">
    <img src="{{ asset('/img/logo.png') }}" class = "img-responsive">
  </div>
  <div class = "col-md-8">
    <p class="muted credit"><b>ศูนย์เทคโนโลยีการศึกษาเพื่อคนตาบอด</b></p>
  </div>
</div>
</div>

<!-- jQuery -->
@section('script')
<script type="text/javascript"  src="{{ asset('js/jquery.min.js') }}"></script>
<!-- Bootstrap JavaScript -->
<script type="text/javascript"  src="{{ asset('js/bootstrap.min.js') }}"></script>
<!-- AJAX Databases -->
<script type="text/javascript" src="{{ asset('js/jquery.dataTables.min.js') }}"></script>
@show
</body>
</html>
