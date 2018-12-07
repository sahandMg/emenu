
        <!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{URL::asset('/css/bootstrap2.min.css')}}">
{{--    <!-- <link type="text/css" rel="stylesheet" href="{{URL::asset('css/main.css')}}"> -->--}}
    <title>سفارش</title>
</head>
<body style="width: 100%;height: 100%;margin: 0;padding: 0;">
<div class="container" id="bill" style="width: 100%;padding: 0;margin: 0;">
            <!-- <div v-if="paid == 1 || method == 0"> -->
                <br/><br/>
                <div class="row">
                    <div class="text-center" style="border: 1px solid;width: 22%;float: left;">
                        <p style="direction: rtl;font-size: 25px;"> {{\Morilog\Jalali\CalendarUtils::strftime('H:i:s', strtotime($order->created_at))}}</p>
                        <p style="direction: rtl;font-size: 25px;"> {{\Morilog\Jalali\CalendarUtils::strftime('Y/m/d', strtotime($order->created_at))}}</p>
                    </div>
                    <div class="text-center" style="width: 50%;float: left;">
                        <h2> {{$restaurant->name}} </h2>
                    </div>   
                    <div class="col-md-4 text-center" style="border: 1px solid;width: 23%;float: left;">
                        <p style="font-size: 25px;">شماره فاکتور </p>
                        <p style="font-size: 25px;">{{$order->order_number}}</p>
                    </div>
                </div>
                <hr/>
                <table style="direction: rtl;">
                    <thead>
                    <tr>
                        <th>نام</th>
                        <th>فی</th>
                        <th>تعداد</th>
                        <th>قیمت کل</th>
                    </tr>
                    </thead>
                    <tbody>
                    @for($i=0;$i<count(unserialize($order->order));$i++ )
                        <tr>
                            <td><b>{{unserialize($order->order)[$i]['foodName']}}</b></td>
                            <td><b>{{unserialize($order->order)[$i]['price']}}</b></td>
                            <td><b>{{unserialize($order->order)[$i]['foodNumber']}}</b></td>
                            <td><b>{{unserialize($order->order)[$i]['foodNumber'] * unserialize($order->order)[$i]['price']}}</b></td>
                        </tr>
                    @endfor

                    <tr>
                        <td></td>
                        <td></td>
                        <td><b>جمع کل</b></td>
                        <td><b>{{$order->price}}</b></td>
                    </tr>
                    </tbody>
                </table>
                <br/>
                <div class="text-center">
                    <p><b>{{$restaurant->tax}}% :مالیات بر ارزش افزوده</b></p>
                    <h1>قابل پرداخت: {{$order->price * (1+$restaurant->tax/100)}} تومان</h1>
                </div>
                <br/>
                <hr/>
                <div class="text-center">
                    <p>به امید دیدار دوباره</p>
                </div>
                <hr/>
                <div class="text-center">
                    <h2> {{$restaurant->name}} </h2>
                    <h2> {{$restaurant->tel}} </h2>
                </div>
            <!-- </div> -->
    <br/><br/>
</div>
<style type="text/css">
table {
    border-collapse: collapse;
    width: 100%;
}

th, td {
    padding: 8px;
    text-align: right;
    border-bottom: 1px solid #ddd;
}
th {font-size: 30px;}
td {
    font-size: 35px;
}
p {
    font-size: 35px;
}
.displayBlock {
 display: block !important;
}
.flex-row {
 display: flex;
 flex-direction: row;
 width: 100%;
}
.flex-column {
 display: flex;
 flex-direction: column;
 width: 100%;
}
.space-between {
 justify-content: space-between;
}
.space-around {
 justify-content: space-around;
}
.flex-start {
 justify-content: flex-start;
}
.flex-end {
 justify-content: flex-flex-end;
}
.justify-content-center {
 justify-content: center;
}
.flex-wrap {
 flex-wrap: wrap;
}
.flex-start-align {
 align-content: flex-start ;
}
.flex-center-align {
 align-content: center ;
}
    /*.text-center {
        text-align: center;
    }*/

</style>
<!-- <script type="text/javascript">
function PlaySound(soundObj) {
  var sound = document.getElementById(soundObj);
  sound.Play();
}

PlaySound("sound1");
</script> -->
</body>
</html>