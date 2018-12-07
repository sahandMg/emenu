@extends('master.layout')
@section('content')
    <div class="container">
        <form method="post" action="{{route('cashierReceipt')}}">
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
                <input type="hidden" value="{{$sum = 0}}">
                @for($i=0;$i<count($foods);$i++)

                <tr>
                    <td>{{$foods[$i]['foodName']}}</td>
                    <td>{{$foods[$i]['price']}} <span>تومان</span></td>
                    <td>{{$foods[$i]['foodNumber']}}</td>
                </tr>
                <input type="hidden" value="{{$sum = $sum + $foods[$i]['price']*$foods[$i]['foodNumber']}}">
                @endfor

                <tr>
                    <td></td>
                    <td>مالیات بر ارزش افزوده : </td>
                    <td>%{{$restaurant->tax}} = {{($restaurant->tax/100)*$sum}} تومان</td>
                </tr>
                <tr>
                    <td></td>
                    <td>جمع کل: </td>
                    <td>{{$sum*(1+$restaurant->tax/100)}} تومان</td>
                </tr>
                </tbody>
            </table>
            <input type="hidden" name="price" value="{{$sum}}">
            <br/>
            <div class="form-group">
                <label>شماره میز : </label>
                <input  type="number" class="form-control" name="table_id" placeholder="اختیاری">
            </div>
            <br/>
            <div class="form-group">
                <label>توضیحات سفارش : </label>
                <textarea rows="3" type="text" id="info" class="form-control" name="description"> </textarea>
            </div>
            <!-- <div class="flex-row space-around" style="margin-top: 1%;margin-bottom: 1%;">
                   <span>مالیات بر ارزش افزوده :</span><span><b>9%</b></span>
             </div>
             <div class="text-center" style="margin-top: 1%;margin-bottom: 1%;">
                   <span>مجموع سفارش شما :</span><span><b>`+allPrice+` تومان</b></span>
             </div> -->
            <br/>
            <div class="flex-row">
            <button class="btn btn-success" type="submit" style="margin: auto;display: block;">تایید سفارش</button>
            <a href="{{route('indexCashier')}}" class="btn btn-danger" style="margin: auto;display: block;"> بازگشت به منو</a>
            </div>
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