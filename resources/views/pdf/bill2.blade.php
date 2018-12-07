
        <!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script type="text/javascript" src="{{URL::asset('js/jquery.js')}}"></script>
    <link rel="stylesheet" href="{{URL::asset('/css/bootstrap2.min.css')}}">
    <link type="text/css" rel="stylesheet" href="{{URL::asset('css/main.css')}}">
    <title>سفارش</title>
</head>
<body style="background-color: #efefef;width: 100%;height: 100%;margin: 0;padding: 0;">
<div class="container" style="">
    <div class="card-wrapper text-center" style="padding: 5%;">
            <br/><br/>
            <div class="flex-row space-around">
                <div class="text-center" style="border: 1px solid;">
                    <h3  style="direction: rtl"> {{\Morilog\Jalali\CalendarUtils::strftime('H:i:s', strtotime($order->created_at))}}</h3>
                    <h3  style="direction: rtl"> {{\Morilog\Jalali\CalendarUtils::strftime('Y/m/d', strtotime($order->created_at))}}</h3>
                </div>
                <div>
                    <img width="120px" height="100px" src="{{URL::asset('storage/images/').'/'.$restaurant->logo}}" />
                </div>
                <div class="text-center" style="border: 1px solid;">
                    <h1>شماره فاکتور: </h1>
                    <h2>{{$order->order_number}}</h2>
                </div>
            </div>
            <hr/>
            <table class="table-bordered">
                <thead>
                <tr>
                    <th> <h3>نام</h3></th>
                    <th><h3>فی</h3></th>
                    <th><h3>تعداد</h3></th>
                    <th><h3>قیمت کل</h3></th>
                </tr>
                </thead>
                <tbody>
                @for($i=0;$i<count(unserialize($order->order));$i++ )
                    <tr>
                        <td><h3> {{unserialize($order->order)[$i]['foodName']}} </h3></td>
                        <td><h3> {{unserialize($order->order)[$i]['price']}} </h3></td>
                        <td><h3> {{unserialize($order->order)[$i]['foodNumber']}} </h3></td>
                        <td><h3> {{unserialize($order->order)[$i]['foodNumber'] * unserialize($order->order)[$i]['price']}} </h3> </td>
                    </tr>
                @endfor

                <tr>
                    <td></td>
                    <td></td>
                    <td style="font-size: 40px;font-weight: bold">جمع کل  </td>
                    <td style="font-size: 40px;font-weight: bold">{{$order->price}}</td>
                </tr>
                </tbody>
            </table>
    </div>
    <br/><br/>
</div>
<!-- <embed src="{{URL::asset('wav/3.wav')}}" autostart="false" width="0" height="0" id="sound1" enablejavascript="true"> -->
<style type="text/css">
    h1{
        font-size: 60px;
    }
    h2{
        font-size: 40px;
    }
    h3{
        font-size: 35px;
    }
    .card-wrapper {
        background: #fff;
        border-radius: 2px;
        display: inline-block;
        margin: 0 auto;
        box-shadow: 0 10px 20px rgba(0,0,0,0.19), 0 6px 6px rgba(0,0,0,0.23);
        width: 100%;
        padding: 1% 2%;
        margin-top: 10%;
    }
    @media only screen and (max-width: 1024px) {
        h2 { font-size: 1.5rem;}
    }
    @media only screen and (max-width: 768px) {
        h2 { font-size: 1rem;}
    }
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