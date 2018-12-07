@extends('master.layout')
@section('content')

    <div class="login-page">
    @if(session()->has('message'))
        <div class="alert alert-danger" role="alert">
           <p>{{session('message')}}</p>
        </div>
    @endif

    <div class="form" id="apps">
        <h4>اطلاعات نمودار</h4>
        <form class="login-form" style="padding: 20px;" method="POST" action="{{route('chart')}}">
            <input type="hidden" name="_token" value="{{csrf_token()}}">
            <div class="form-group">
                <select v-model="food"  class="form-control" name="food">
                    <option  disabled selected>انتخاب غذا</option>
                    @foreach($foods as $food)
                        <option value="{{$food}}">{{$food}}</option>
                    @endforeach
                </select>
                <br>
                <select v-model="duration"  class="form-control" name="duration">
                    <option disabled selected >برحسب</option>
                    <option value="daily"> برحسب روز</option>
                    <option value="monthly">برحسب ماه</option>
                    <option value="yearly">برحسب سال</option>
                </select>

                <span>از تاریخ</span>
                <select v-if="day" v-model="dayField1" class="form-control" name="day1">
                    <option disabled selected>روز</option>
                    <option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option>
                    <option value="5">5</option><option value="6">6</option><option value="7">7</option><option value="8">8</option>
                    <option value="9">9</option><option value="10">10</option><option value="11">11</option><option value="12">12</option>
                    <option value="13">13</option><option value="14">14</option><option value="15">15</option><option value="16">16</option>
                    <option value="17">17</option><option value="18">18</option><option value="19">19</option><option value="20">20</option>
                    <option value="21">21</option><option value="22">22</option><option value="23">23</option><option value="24">24</option>
                    <option value="25">25</option><option value="26">26</option><option value="27">27</option><option value="28">28</option>
                    <option value="29">29</option><option value="30">30</option><option value="31">31</option>
                </select>
                <br>
                <select v-if="month" v-model="monthField1"  class="form-control" name="month1">
                    <option disabled selected>ماه</option>
                    <option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option>
                    <option value="5">5</option><option value="6">6</option><option value="7">7</option><option value="8">8</option>
                    <option value="9">9</option><option value="10">10</option><option value="11">11</option><option value="12">12</option>
                </select>
                <br>
                <select v-if="year" v-model="yearField1" class="form-control" name="year1">
                    <option disabled selected>سال</option>
                    @for($i=$firstYear;$i<= $lastYear;$i++)
                    <option value="{{$i}}">{{$i}}</option>
                    @endfor
                </select>

                <span>تا تاریخ</span>
                <select v-if="day" v-model="dayField2" class="form-control" name="day2">
                    <option disabled selected>روز</option>
                    <option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option>
                    <option value="5">5</option><option value="6">6</option><option value="7">7</option><option value="8">8</option>
                    <option value="9">9</option><option value="10">10</option><option value="11">11</option><option value="12">12</option>
                    <option value="13">13</option><option value="14">14</option><option value="15">15</option><option value="16">16</option>
                    <option value="17">17</option><option value="18">18</option><option value="19">19</option><option value="20">20</option>
                    <option value="21">21</option><option value="22">22</option><option value="23">23</option><option value="24">24</option>
                    <option value="25">25</option><option value="26">26</option><option value="27">27</option><option value="28">28</option>
                    <option value="29">29</option><option value="30">30</option><option value="31">31</option>
                </select>
                <br>
                <select v-if="month" v-model="monthField2" class="form-control" name="month2">
                    <option disabled selected>ماه</option>
                    <option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option>
                    <option value="5">5</option><option value="6">6</option><option value="7">7</option><option value="8">8</option>
                    <option value="9">9</option><option value="10">10</option><option value="11">11</option><option value="12">12</option>
                </select>
                <br>
                <select v-if="year" v-model="yearField2"  class="form-control" name="year2">
                    <option disabled selected>سال</option>
                    @for($i=$firstYear;$i<= $lastYear;$i++)
                        <option value="{{$i}}">{{$i}}</option>
                    @endfor
                </select>

            </div>
            <button :disabled="btn" type="submit" class="btn btn-success">نمایش نمودار </button>
        </form>
    </div>
</div>

<script>
    new Vue({
        el:'#apps',
        data:{
            month:true,
            day:true,
            year:true,
            dayField1:'روز',
            monthField1:'ماه',
            yearField2:'سال',
            yearField1:'سال',
            dayField2:'روز',
            monthField2:'ماه',
            duration:'برحسب',
            food:'انتخاب غذا',
            btn:true
        },
        watch:{
            duration:function () {
                if(this.duration == 'yearly'){
                    this.day = false
                    this.month = false

                }else if(this.duration == 'monthly'){
                    this.day = false
                    this.month = true

                }else{
                    this.day = true
                    this.month = true
                }
                if(this.yearField2 != 'سال' && this.yearField1 != 'سال' && this.food != 0 && this.duration != 'برحسب'){
                    this.btn = false
                }
            },

            yearField2:function () {
                if(this.yearField2 != 'سال' && this.yearField1 != 'سال' && this.food != 0 && this.duration != 'برحسب'){
                    this.btn = false
                }
            },
            yearField1:function () {
                if(this.yearField2 != 'سال' && this.yearField1 != 'سال' && this.food != 0 && this.duration != 'برحسب'){
                    this.btn = false
                }
            },
            food:function () {
                if(this.yearField2 != 'سال' && this.yearField1 != 'سال' && this.food != 0 && this.duration != 'برحسب'){
                    this.btn = false
                }
            },

        },
        methods:{

        }
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
        text-align: center;
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
        display: inline-block;
    }
    .form button {
        font-family: "Roboto", sans-serif;
        text-transform: uppercase;
        outline: 0;
        background: #4CAF50;
        width: 80%;
        border: 0;
        padding: 10px;
        color: #FFFFFF;
        font-size: 14px;
        -webkit-transition: all 0.3 ease;
        transition: all 0.3 ease;
        cursor: pointer;

    }
    .form button:hover,.form button:active,.form button:focus {
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
    body {
        background: #b5b2b8; /* fallback for old browsers */
        background: -webkit-linear-gradient(right, #76b852, #8DC26F);
        background: -moz-linear-gradient(right, #76b852, #8DC26F);
        background: -o-linear-gradient(right, #76b852, #8DC26F);
        background: linear-gradient(to left, #76b852, #8DC26F);
        font-family: "Roboto", sans-serif;
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
    }
</style>
@endsection