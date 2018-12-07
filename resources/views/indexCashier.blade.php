@extends('master.layout')
@section('content')

<div class="container">
<br/>
  <h1 class="text-center">ثبت سفارش</h1>
  <br/>
  <form method="post" action="{{route('indexCashier')}}">
      <input type="hidden" name="_token" value="{{csrf_token()}}">
    <table class="table table-striped">
    <thead>
      <tr>
        <th>نام غذا</th>
        <th>قیمت</th>
        <th>تعداد</th>
      </tr>
    </thead>
    <tbody>
    @for($i=0;$i<count($foods);$i++)
        @foreach($foods[$i] as $item)
            <tr>
                <td>{{$item->name}}</td>
                <td>{{$item->price}} تومان</td>
                <td><input min="0" placeholder="0" type="number" class="form-control" name="{{$item->id}}"></td>
            </tr>

        @endforeach

    @endfor
    </tbody>
   </table>
   <br/>
      <button class="btn btn-success" type="submit" style="margin: auto;display: block;">ثبت سفارش</button>
   <br/>
  </form>
</div>

<style>
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
</style>
@endsection