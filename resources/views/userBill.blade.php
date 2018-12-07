<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script type="text/javascript" src="{{URL::asset('js/jquery.js')}}"></script>
    {{--<script type="text/javascript" src="{{URL::asset('js/jquery.vibrate.min.js')}}"></script>--}}
	<link type="text/css" rel="stylesheet" href="{{URL::asset('css/bootstrap.min.css')}}">
	<link type="text/css" rel="stylesheet" href="{{URL::asset('css/main.css')}}">
    <script src="{{URL::asset('js/ajax_vue.js')}}"></script>
    <script src="{{URL::asset('js/vue_resource.js')}}"></script>
    <script src="{{URL::asset('js/lodash.js')}}"></script>
    <script src="{{URL::asset('js/axios.js')}}"></script>
    <title>رسید</title>
</head>
<body style="background-color: #efefef;width: 100%;height: 100%;margin: 0;padding: 0;">

<div class="container" id="bill">
    <div id="load_screen"><div id="loading">در حال بارگذاری</div></div>
	<div class="card-wrapper text-center" style="padding: 5%;">

          <div v-if="method == 1 && paid == 0">
            <p>برای تکمیل سفارش به صندوق مراجعه کنید</p>
            <p>شماره سفارش شما : {{$order->order_number}}</p>
              @if(!is_null($restaurant->tax))
                  <p> مالیات بر ارزش افزوده {{ $restaurant->tax }}%</p>
                  <h5> قابل پرداخت : {{$order->price * (1+$restaurant->tax/100) }} تومان</h5>
              @else
                  <p>هزینه پرداختی : {{$order->price}}</p>
              @endif
          </div>

        {{--@if($order->paid == 1 || $restaurant->payMethod == 0)--}}
            <div v-if="paid == 1 || method == 0">
                <h2 class="text-right">سفارش شما با موفقیت ثبت شد.غذای شما به زودی آماده می شود.</h2>
                <br/>
                <h4 class="text-center">وضعیت سفارش</h4>
                <div class="progress">
                  <div class="progress-bar @{{ pgClass }}" role="progressbar" style="width: @{{ progressBar }}%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                <h4 style="color: #0000F0" class="text-center" v-if="pending == 0 && delivered == 0">در حال آماده سازی </h4>
                <h5 style="color:red" class="text-center" v-if="pending == 1">ارسال جهت پخت</h5>
                <h5 style="color: #00CC99" class="text-center" v-if="delivered == 1">آماده تحویل</h5>
                <h5 v-show="estimateShow">زمان تقریبی: @{{remaining}} دقیقه</h5>
                <br/><br/>
                <div class="flex-row space-around">
                    <div class="text-center" style="border: 1px solid;">
                        <p  style="direction: rtl"> {{\Morilog\Jalali\CalendarUtils::strftime('H:i:s', strtotime($order->created_at))}}</p>
                        <p  style="direction: rtl"> {{\Morilog\Jalali\CalendarUtils::strftime('Y/m/d', strtotime($order->created_at))}}</p>
                    </div>
                    <div>
                        @if(!is_null($restaurant->logo))
                            <img height="90px" width="100px" src="{{(('images/'.$restaurant->logo))}}" />
                        @else
                            <img height="90px" width="100px" src="{{(('images/preset/logo.jpg'))}}" />
                        @endif
                        {{--<img  width="80px" height="80px"  src="{{URL::asset('images/').'/'.$restaurant->logo}}" />--}}
                    </div>
                    <div class="text-center" style="border: 1px solid;">
                        <p>شماره فاکتور: </p>
                        <p>{{$order->order_number}}</p>
                    </div>
                </div>
                <hr/>
                <table class="table">
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
                            <td> {{unserialize($order->order)[$i]['foodName']}}</td>
                            <td> {{unserialize($order->order)[$i]['price']}}</td>
                            <td> {{unserialize($order->order)[$i]['foodNumber']}}</td>
                            <td> {{unserialize($order->order)[$i]['foodNumber'] * unserialize($order->order)[$i]['price']}} </td>
                        </tr>
                    @endfor
                    <tr>
                        <td></td>
                        <td></td>
                        <td> مالیات بر ارزش افزوده {{$restaurant->tax}}%  </td>
                        <td>{{$order->price*$restaurant->tax/100 }} تومان</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                        <td>جمع کل  </td>
                        <td>{{$order->price*(1+$restaurant->tax/100) }} تومان</td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <br/>

        <br>

      {{--@endif--}}
	</div>
	<br/><br/>
	<a href="{{route('main')}}">بازگشت به منو</a>
    <br>
    <br>
</div>
<!-- <embed src="{{URL::asset('wav/3.wav')}}" autostart="false" width="0" height="0" id="sound1" enablejavascript="true"> -->
<style type="text/css">
    div#load_screen{
        background: whitesmoke;
        opacity: 1;
        position: fixed;
        z-index:10;
        top: 0px;
        width: 100%;
        height: 100%;
    }
    div#load_screen > div#loading{
        color: #242424;
        width:120px;
        height:24px;
        margin: 300px auto;
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
   h4 { font-size: 1.25rem;}
 }
@media only screen and (max-width: 768px) {
   h2 { font-size: 1rem;}
   h4 { font-size: 1rem;}
 }
 body {
     width: 100%;
     min-height: 100%;
 }
</style>
<!-- <script type="text/javascript">
function PlaySound(soundObj) {
  var sound = document.getElementById(soundObj);
  sound.Play();
}

PlaySound("sound1");
</script> -->

<script>

    window.addEventListener("load", function(){

        setTimeout(function () {
            var load_screen = document.getElementById("load_screen");
            $("#load_screen").remove();
            load_screen.parentNode.removeChild(load_screen);
//        load_screen.removeChild(load_screen.childNodes[0]);
            console.log("refresh")
//        document.body.removeChild(load_screen);
        },500)

    });
    new Vue({
        el: '#bill',
        data: {
            paid: 0,
            method: 0,
            pending: 0,
            delivered: 0,
            progressBar: 0,
            pgClass: 'bg-info',
            remaining : 0,
            estimateShow:true
        },
        created: function () {

            vm = this;
            axios.get('{{route('paidStat',['id'=>$order->id])}}').then(function (response) {

                vm.paid = response.data[0];
                vm.method = response.data[1];

            });

            this.cash();
            this.timeUpdate()
        },
        ready:function () {
//            var pageLoading = window.setTimeout(this.loading(),1000);
//                clearTimeout(pageLoading)
        },
        methods: {


            cash: function () {

                vm = this;
                axios.get('{{route('paidStat',['id'=>$order->id])}}').then(function (response) {

                    vm.paid = response.data[0];
                    vm.method = response.data[1];
                    vm.delivered = response.data[2];
                    vm.pending = response.data[3];
                    if (vm.pending == 0 && vm.delivered == 0 ) {
                        vm.progressBar = 50
                    }
                    if (vm.pending == 1 && vm.delivered == 0 ) {
                        vm.progressBar = 75;
                        vm.pgClass = "bg-warning"
                    }

                    if (vm.pending == 0 && vm.delivered == 1 ) {
                        vm.progressBar = 100;
                        vm.pgClass = "bg-success";
                        vm.estimateShow = false
                    }


                });

                setTimeout(vm.cash, 5000)
            },
            timeUpdate: function () {
                vm = this
                axios.post('{{route('timeUpdate')}}',{'order':{!! json_encode($order) !!},'totalTime':{!! json_encode($totalTime) !!}}).then(function (response) {
                        vm.remaining = response.data

                })

                setTimeout(vm.timeUpdate, 60000)
            }
        }
    })
</script>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<footer style="background-color: rgba(94, 91, 82, 0.74);padding: 2%;direction: rtl;color: white;">
    <p class="text-center">نرم افزار EMenu</p>
    <p class="text-center">رستوران هوشمند را با ما تجربه کنید</p>
    <p style="text-align: center;font-size: 10px">09104963734, 09387728916</p>
</footer>
</body>

</html>
