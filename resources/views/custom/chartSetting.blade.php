@extends('master.layout')
@section('content')
<div class="container" style="margin-top: 2%;">
        @if(session()->has('message'))
            <div class="alert alert-danger" role="alert">
                <p>{{session('message')}}</p>
            </div>
        @endif
   <a href="{{route('report')}}" class="btn btn-primary" style="margin: auto;display: inline-block;">گزارش</a>
   <a href="{{route('chartSetting')}}" class="btn btn-success" style="margin: auto;display: inline-block;">نمودار</a>
   <br/><br/>
  <form action="{{route('report')}}" method="post" id="apps">
      <input type="hidden" value="{{csrf_token()}}" name="_token">
      <div class="flex-row flex-wrap calender">
                <select v-model="food"  class="form-control" name="food">
                    <option  disabled selected>انتخاب غذا</option>
                    @foreach($foods as $food)
                        <option value="{{$food}}">{{$food}}</option>
                    @endforeach
                </select>
                <select v-model="duration"  class="form-control" name="duration">
                    <option disabled selected >برحسب</option>
                    <option value="daily"> برحسب روز</option>
                    <option value="monthly">برحسب ماه</option>
                    <option value="yearly">برحسب سال</option>
                </select>
        </div> 
        <div class="flex-row flex-wrap calender">
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
                <select v-if="month" v-model="monthField1"  class="form-control" name="month1">
                    <option disabled selected>ماه</option>
                    <option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option>
                    <option value="5">5</option><option value="6">6</option><option value="7">7</option><option value="8">8</option>
                    <option value="9">9</option><option value="10">10</option><option value="11">11</option><option value="12">12</option>
                </select>
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
                <select v-if="month" v-model="monthField2" class="form-control" name="month2">
                    <option disabled selected>ماه</option>
                    <option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option>
                    <option value="5">5</option><option value="6">6</option><option value="7">7</option><option value="8">8</option>
                    <option value="9">9</option><option value="10">10</option><option value="11">11</option><option value="12">12</option>
                </select>
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
.calender span {
   width: 5%;margin-left: 1%;margin-right: 1%;
}
.calender select {
   width: 10%;margin-left: 1%;margin-right: 1%;margin-bottom: 2%;
}

@media screen and (max-width: 840px) {
  .calender span {
   width: 8%;margin-left: 1%;margin-right: 1%;
  }
  .calender select {
   width: 11%;margin-left: 1%;margin-right: 1%;
  }
}

@media screen and (max-width: 600px) {
  .calender span {
   width: 15%;margin-left: 1%;margin-right: 1%;
  }
  .calender select {
   width: 25%;margin-left: 1%;margin-right: 1%;
  }
}
</style>
<script>

</script>


 
@endsection