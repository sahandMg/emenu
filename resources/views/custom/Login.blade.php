<!DOCTYPE html>
<html>
<head>
	<title>Hot</title>
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
	<script type="text/javascript" src="js/jquery.js"></script>
	<link type="text/css" rel="stylesheet" href="css/bootstrap.min.css">
	<link type="text/css" rel="stylesheet" href="css/main.css">
	<link rel="stylesheet" href="css/font-awesome.min.css">
</head>
<body>
  <div class="login-page">
      @if($errors->all())
          <div class="alert alert-danger" role="alert">
              @foreach($errors->all() as $error)
                  <li style="list-style: none">{{$error}}</li>
              @endforeach
          </div>
      @endif
          @if(session('message'))
              <div class="alert alert-danger" role="alert">
                  {{session('message')}}
              </div>
          @endif
  <div class="form" id="user">
         <form class="login-form" style="padding: 20px;" method="POST" action="{{route('login')}}">
                  <input type="hidden" name="_token" value="{{csrf_token()}}">
               <div class="form-group">
                 <label for="exampleInputEmail1">ایمیل</label>
                 <input name="email" type="email" class="form-control" value="{{Request::old('email')}}" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="ایمیل خود را وارد کنید">
               </div>

               <div class="form-group">
                 <label for="exampleInputPassword1">رمز</label>
                 <input name="password" type="password" class="form-control" id="exampleInputPassword1" placeholder="رمز">
               </div>
               <button type="submit" class="btn btn-primary form-button">ورود  </button>
               <br/><br/>
               <button type="button" id="adminBtn" class="btn btn-primary form-button" style="background-color: blue;">پنل ورود مدیر</button>
         </form>
  </div>
  <div class="form" id="admin">
         <form class="login-form" style="padding: 20px;" method="POST" action="{{route('login')}}">
                  <input type="hidden" name="_token" value="{{csrf_token()}}">
               <div class="form-group">
                 <label for="exampleInputEmail2">ایمیل</label>
                 <input name="email" type="email" class="form-control" value="{{Request::old('email')}}" id="exampleInputEmail2" aria-describedby="emailHelp" placeholder="ایمیل خود را وارد کنید">
               </div>

               <div class="form-group">
                 <label for="exampleInputPassword2">رمز</label>
                 <input name="password" type="password" class="form-control" id="exampleInputPassword2" placeholder="رمز">
               </div>
               <button type="submit" class="btn btn-primary form-button">ورود  </button>
               <br/><br/>
               <button type="button" id="userBtn" class="btn btn-primary form-button" style="background-color: blue;">پنل ورود کاربر</button>
         </form>
  </div>
</div>
<script type="text/javascript">
  $("#adminBtn").click(function() {
    $("#admin").show();$("#user").hide();   
  })
  $("#userBtn").click(function() {
    $("#admin").hide();$("#user").show();
  })
</script>


<style type="text/css">
	@import url(https://fonts.googleapis.com/css?family=Roboto:300);

.login-page {
  width: 360px;
  padding: 8% 0 0;
  margin: auto;
}
.form {
  position: relative;
  z-index: 1;
  background: #FFFFFF;
  max-width: 360px;
  margin: 0 auto 100px;
  padding: 45px;
  text-align: center;direction: rtl;
  box-shadow: 0 0 20px 0 rgba(0, 0, 0, 0.2), 0 5px 5px 0 rgba(0, 0, 0, 0.24);
}
.form input {
  font-family: "Roboto", sans-serif;
  outline: 0;
  background: #f2f2f2;
  width: 100%;
  border: 0;
  margin: 0 0 15px;
  padding: 15px;
  box-sizing: border-box;
  font-size: 14px;
}
.form-button {
  font-family: "Roboto", sans-serif;
  text-transform: uppercase;
  outline: 0;
  background: #4CAF50;
  width: 100%;
  border: 0;
  padding: 15px;
  color: #FFFFFF;
  font-size: 14px;
  -webkit-transition: all 0.3 ease;
  transition: all 0.3 ease;
  cursor: pointer;
}
.form-button:hover,.form-button:active,.form-button:focus {
  background: #43A047;
}
.form .message {
  margin: 15px 0 0;
  color: #b3b3b3;
  font-size: 12px;
}
.form .message a {
  color: #4CAF50;
  text-decoration: none;
}
.form .register-form {
  display: none;
}
.container {
  position: relative;
  z-index: 1;
  max-width: 300px;
  margin: 0 auto;
}
.container:before, .container:after {
  content: "";
  display: block;
  clear: both;
}
.container .info {
  margin: 50px auto;
  text-align: center;
}
.container .info h1 {
  margin: 0 0 15px;
  padding: 0;
  font-size: 36px;
  font-weight: 300;
  color: #1a1a1a;
}
.container .info span {
  color: #4d4d4d;
  font-size: 12px;
}
.container .info span a {
  color: #000000;
  text-decoration: none;
}
.container .info span .fa {
  color: #EF3B3A;
}
body {
  background: #76b852; /* fallback for old browsers */
  background: -webkit-linear-gradient(right, #76b852, #8DC26F);
  background: -moz-linear-gradient(right, #76b852, #8DC26F);
  background: -o-linear-gradient(right, #76b852, #8DC26F);
  background: linear-gradient(to left, #76b852, #8DC26F);
  font-family: "Roboto", sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;      
}
</style>
</body>
</html>