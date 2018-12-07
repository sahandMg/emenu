@extends('master.layout')
@section('content')
    <div class="container" style="margin-top: 2%;">
            <h2 class="text-center">پروفایل رستوران</h2>
            @if(session('message'))
                <div class="alert alert-success" role="alert">
                    {{session('message')}}
                </div>
            @endif
            <form action="{{route('settings')}}" method="post" enctype="multipart/form-data" style="margin-bottom: 2%;">
                <input type="hidden" name="_token" value="{{csrf_token()}}">
                <div class="form-group">
                    <label>نام رستوران</label>
                    <input type="text" name="name" class="form-control" placeholder="نام رستوران">
                </div>
                <div class="form-group">
                    <label>عکس لوگو</label>
                    <input type="file" name="logo" class="form-control">
                </div>
                <div class="form-group">
                    <label>عکس بنر منو</label>
                    <input type="file" name="image" class="form-control">
                </div>
                <div class="form-group">
                    <label>آدرس رستوران</label>
                    <input type="text" name="address" class="form-control" placeholder="آدرس رستوران">
                </div>
                <div class="form-group">
                    <label>شماره تلفن</label>
                    <input type="text" name="tel" class="form-control" placeholder=" شماره تلفن">
                </div>
                <!-- <div class="form-check"> -->
                <label class="container-checkBox">پرداخت بعد از ثبت سفارش :
                    <input name="payMethod" type="checkbox" checked="checked">
                    <span class="checkmark"></span>
                </label>
                <!-- <label class="form-check-label">
                  <input type="checkbox" class="form-check-input" value=""> پرداخت قبل از ثبت سفارش
                 </label> -->
                <!-- </div> -->
                <button type="submit" class="btn btn-primary col-md-1 col-sm-1">ثبت</button>
            </form>
            <br/><br/><hr/>
            <h2 class="text-center">مدیریت کاربران</h2>
             <form action="{{route('settings')}}" method="post" enctype="multipart/form-data" style="margin-bottom: 2%;">
                <input type="hidden" name="_token" value="{{csrf_token()}}">
                <div class="form-group">
                    <label>نام کاربر</label>
                    <input type="text" name="name" class="form-control" placeholder="نام کاربر">
                </div>
                <div class="form-group">
                    <label>رمز کاربر</label>
                    <input type="text" name="name" class="form-control" placeholder="رمز">
                </div>
                <button type="submit" class="btn btn-primary col-md-3 col-sm-2">اضافه کردن کاربر جدید</button>
            </form>
            <br/>
            <table class="table table-striped">
    <thead>
      <tr>
        <th>نام کاربر</th>
        <th>رمز عبور</th>
        <th>حذف</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>John</td>
        <td>1234</td>
        <td><a href="">حذف</a></td>
      </tr>
      <tr>
        <td>Mary</td>
        <td>1234</td>
        <td><a href="">حذف</a</td>
      </tr>
      <tr>
        <td>July</td>
        <td>1234</td>
        <td><a href="">حذف</a</td>
      </tr>
    </tbody>
  </table>
        </div>



    <style>
        body {
            margin: 0;
            font-family: Arial, Helvetica, sans-serif;
        }

        .topnav {
            overflow: hidden;
            background-color: #333;
        }

        .topnav a {
            /*float: right;*/
            /*display: inline-block;*/
            /*display: block;*/
            color: #f2f2f2;
            text-align: center;
            padding: 14px 16px;
            text-decoration: none;
            font-size: 17px;
        }

        .topnav a:hover {
            background-color: #ddd;
            color: black;
        }

        .activeNav {
            background-color: #4CAF50;
            color: white;
        }

        .topnav .icon {
            display: none;
        }

        /*@media screen and (max-width: 600px) {
          .topnav a:not(:first-child) {display: none;}
          .topnav a.icon {
            float: right;
            display: block;
          }
        }

        @media screen and (max-width: 600px) {
          .topnav.responsive {position: relative;}
          .topnav.responsive .icon {
            position: absolute;
            right: 0;
            top: 0;
          }
          .topnav.responsive a {
            float: none;
            display: block;
            text-align: left;
          }
        }*/

        /* Style the tab */
        .tab {
            overflow: hidden;
            border: 1px solid #ccc;
            background-color: #f1f1f1;
        }

        /* Style the buttons that are used to open the tab content */
        .tab button {
            background-color: inherit;
            float: right;
            border: none;
            outline: none;
            cursor: pointer;
            padding: 14px 16px;
            transition: 0.3s;
        }

        /* Change background color of buttons on hover */
        .tab button:hover {
            background-color: #ddd;
        }

        /* Create an active/current tablink class */
        .tab button.active {
            background-color: #ccc;
        }

        /* Style the tab content */
        .tabcontent {
            display: none;
            padding: 6px 12px;
            border: 1px solid #ccc;
            border-top: none;
        }
        .tabcontent {
            animation: fadeEffect 1s; /* Fading effect takes 1 second */
        }

        /* Go from zero to full opacity */
        @keyframes fadeEffect {
            from {opacity: 0;}
            to {opacity: 1;}
        }

        #menuCategory li {
            list-style: none;
            margin: 1%;
        }
        #menuCategory li h5{
            margin-right: 2%;margin-left: 2%;
            display: inline-block;
            vertical-align: middle;
        }

        /* The container */
        .container-checkBox {
            display: block;
            position: relative;
            padding-left: 35px;
            margin-bottom: 12px;
            cursor: pointer;
            font-size: 22px;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
            width: 300px;
        }

        /* Hide the browser's default checkbox */
        .container-checkBox input {
            position: absolute;
            opacity: 0;
            cursor: pointer;
        }

        /* Create a custom checkbox */
        .checkmark {
            position: absolute;
            top: 7px;
            left: 0;
            height: 25px;
            width: 25px;
            background-color: #eee;
        }

        /* On mouse-over, add a grey background color */
        .container-checkBox:hover input ~ .checkmark {
            background-color: #ccc;
        }

        /* When the checkbox is checked, add a blue background */
        .container-checkBox input:checked ~ .checkmark {
            background-color: #2196F3;
        }

        /* Create the checkmark/indicator (hidden when not checked) */
        .checkmark:after {
            content: "";
            position: absolute;
            display: none;
        }

        /* Show the checkmark when checked */
        .container-checkBox input:checked ~ .checkmark:after {
            display: block;
        }

        /* Style the checkmark/indicator */
        .container-checkBox .checkmark:after {
            left: 9px;
            top: 5px;
            width: 5px;
            height: 10px;
            border: solid white;
            border-width: 0 3px 3px 0;
            -webkit-transform: rotate(45deg);
            -ms-transform: rotate(45deg);
            transform: rotate(45deg);
        }
    </style>

@endsection