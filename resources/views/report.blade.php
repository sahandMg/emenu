@extends('master.layout')
@section('content')
    <div id="table" class="container" style="margin-top: 2%;">
        @if(session()->has('message'))
            <div class="alert alert-danger" role="alert">
                <p>{{session('message')}}</p>
            </div>
        @endif
  <form action="{{route('report')}}" method="post">
      <input type="hidden" value="{{csrf_token()}}" name="_token">
   <div class="flex-row flex-wrap" id="calender">
      <span>از تاریخ</span>
       <select class="form-control" name="day1"  v-model="dayField1">
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

              <select class="form-control" name="month1"  v-model="monthField1">
                  <option disabled selected>ماه</option>
                  <option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option>
                 <option value="5">5</option><option value="6">6</option><option value="7">7</option><option value="8">8</option>
                 <option value="9">9</option><option value="10">10</option><option value="11">11</option><option value="12">12</option>
              </select>
       <select class="form-control" name="year1"  v-model="yearField1">
           <option disabled selected>سال</option>
           @for($i=$firstYear;$i<= $lastYear;$i++)
               <option value="{{$i}}">{{$i}}</option>
           @endfor

       </select>
      <span>تا تاریخ</span>
       <select class="form-control" name="day2" v-model="dayField2">
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

      <select class="form-control" name="month2" v-model="monthField2" >
          <option disabled selected>ماه</option>
         <option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option>
         <option value="5">5</option><option value="6">6</option><option value="7">7</option><option value="8">8</option>
         <option value="9">9</option><option value="10">10</option><option value="11">11</option><option value="12">12</option>
      </select>

       <select class="form-control" name="year2" v-model="yearField2">
           <option disabled selected>سال</option>
           @for($i=$firstYear;$i<= $lastYear;$i++)
               <option value="{{$i}}">{{$i}}</option>
           @endfor
       </select>

   </div>
   <button :disabled="tableBtn" type="submit" class="btn btn-primary" style="margin: auto;display: inline-block;">گزارش</button>
      <a href="{{route('chartSetting')}}"><button type="button" class="btn btn-success" style="margin: auto;display: inline-block;">نمودار</button></a>
  </form>
   <table class="table table-striped" style="margin-top: 2%;">
    <thead>
      <tr>
        <th>نام غذا</th>
        <th>تعداد</th>
        <th>هزینه واحد (تومان)</th>
        <th>فروش کل (تومان)</th>
      </tr>
    </thead>
    <tbody>
    <input type="hidden" value="{{$sum=0}}">
    @foreach($arr as $key=>$value)
      <tr>
        <td>{{$key}}</td>
        <td>{{$value}}</td>
        <td>{{\App\Food::where('name',$key)->first()['price']}}</td>
        <td id="price">

            {{\App\Food::where('name',$key)->first()['price']* $value}}

        </td>
          <input type="hidden" value="{{$sum = $sum + \App\Food::where('name',$key)->first()['price']* $value}}">
      </tr>
      <tr>
    @endforeach
      <tr>
        <td></td>
        <td></td>
        <td></td>
        <td id="total_cost">

            <b>مجموع فروش : {{$sum}}</b>
            @if(!is_null($restaurant->tax))
            <br>
            <b> مجموع فروش با احتساب مالیات %{{$restaurant->tax}} :{{$sum*(1+$restaurant->tax/100)}}</b>
            @endif
        </td>
      </tr>
    {{--<tr>--}}
        {{--<td></td>--}}
        {{--<td></td>--}}
        {{--<td></td>--}}
        {{--<td id="total_cost"><b>مجموع فروش با احتساب مالیات بر ارزش افزوده : {{$sum}}</b></td>--}}
    {{--</tr>--}}

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
#calender span {
   width: 5%;margin-left: 1%;margin-right: 1%;
}
#calender select {
   width: 10%;margin-left: 1%;margin-right: 1%;margin-bottom: 2%;
}

@media screen and (max-width: 840px) {
  #calender span {
   width: 8%;margin-left: 1%;margin-right: 1%;
  }
  #calender select {
   width: 11%;margin-left: 1%;margin-right: 1%;
  }
}

@media screen and (max-width: 600px) {
  #calender span {
   width: 15%;margin-left: 1%;margin-right: 1%;
  }
  #calender select {
   width: 25%;margin-left: 1%;margin-right: 1%;
  }
}
</style>
<script>
    new Vue({
        el:'#table',
        data:{
            dayField1:'روز',
            monthField1:'ماه',
            yearField1:'سال',
            dayField2:'روز',
            monthField2:'ماه',
            yearField2:'سال',
            tableBtn:true
        },
        watch:{
            yearField2:function () {
                if(this.yearField2 != 'سال' && this.monthField2 != 'ماه' &&this.dayField2 != 'روز' && this.yearField1 != 'سال' && this.dayField1 != 'روز' && this.monthField1 != 'ماه'){
                    this.tableBtn = false
                }
            },
            yearField1:function () {
                if(this.yearField2 != 'سال' && this.monthField2 != 'ماه' &&this.dayField2 != 'روز' && this.yearField1 != 'سال' && this.dayField1 != 'روز' && this.monthField1 != 'ماه'){
                    this.tableBtn = false
                }
            },
            dayField1:function () {
                if(this.yearField2 != 'سال' && this.monthField2 != 'ماه' &&this.dayField2 != 'روز' && this.yearField1 != 'سال' && this.dayField1 != 'روز' && this.monthField1 != 'ماه'){
                    this.tableBtn = false
                }
            },
            dayField2:function () {
                if(this.yearField2 != 'سال' && this.monthField2 != 'ماه' &&this.dayField2 != 'روز' && this.yearField1 != 'سال' && this.dayField1 != 'روز' && this.monthField1 != 'ماه'){
                    this.tableBtn = false
                }
            },
            monthField1:function () {
                if(this.yearField2 != 'سال' && this.monthField2 != 'ماه' &&this.dayField2 != 'روز' && this.yearField1 != 'سال' && this.dayField1 != 'روز' && this.monthField1 != 'ماه'){
                    this.tableBtn = false
                }
            },
            monthField2:function () {
                if(this.yearField2 != 'سال' && this.monthField2 != 'ماه' &&this.dayField2 != 'روز' && this.yearField1 != 'سال' && this.dayField1 != 'روز' && this.monthField1 != 'ماه'){
                    this.tableBtn = false
                }
            },
        }
    })
</script>

@endsection