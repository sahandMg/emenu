@extends('master.layout')
@section('content')

    <div id="load_screen"><div id="loading">در حال لود</div></div>
    <div class="container" style="margin-top: 2%;" id="order">
        <div class="flex-row space-around">
            <div class="flex-row flex-start">
                <a href="{{route('OrderCancel')}}" class="btn btn-danger" style="margin-left: 1%;margin-right: 1%;">سفارشات لغو شده</a>
                <a href="{{route('ytd')}}" class="btn btn-warning" style="margin-left: 1%;margin-right: 1%;border: 2px solid black">سفارشات دیروز</a>
                <a href="{{route('orders')}}" class="btn btn-success" style="margin-left: 1%;margin-right: 1%"> سفارشات امروز</a>

            </div>
            {{--<button @click="reset" :disabled="disbtn" href="{{route('reset')}}" class="btn btn-danger">@{{ orderCounter }}</button>--}}
        </div>




        <div  class="row" style="margin-top: 2%;margin-bottom: 2%;">
            <div v-for="order in orders" class="col-sm-12 col-md-4 col-lg-4" style="margin-bottom: 2%">

                <div class="card" style="padding: 1%;">
                    <div class="flex-row space-around">

                        <span>سفارش @{{order.order_number}}</span>
                        <span>میز @{{order.table_id}}</span>
                    </div>
                    <table class="table table-striped">
                        <tbody>

                        <tr v-for="type in order.order">
                            <td> @{{type.foodName}}</td>
                            <td> @{{type.foodNumber}} </td>
                            <td v-if="type.price%1000 != 0">@{{(parseInt(type.price/1000))+","+(type.price%1000)}}</td>
                            <td v-if="type.price%1000 == 0">@{{(parseInt(type.price/1000))+","+'000'}}</td>
                        </tr>

                        </tbody>
                    </table>
                    <p v-if="order.hour > 0">زمان: @{{ order.hour }} ساعت قبل</p>

                    <p v-if="order.minute >= 0">زمان: @{{ order.minute }} دقیقه قبل</p>

                    <span v-if="order.price*(1+tax/100)%1000 != 0">مجموع کل + مالیات (تومان) : @{{(parseInt(order.price*(1+tax/100)/1000))+","+parseInt((order.price*(1+tax/100))%1000)}}</span>
                    <span v-if="order.price*(1+tax/100)%1000 == 0">مجموع کل + مالیات (تومان) : @{{(parseInt(order.price*(1+tax/100)/1000))+","+'000'}}</span>
                    <p>توضیحات: @{{order.info}}</p>
                    @if($restaurant->orderCode == 1)
                    <p>شناسه: @{{order.orderCode}}</p>

                    @endif

                    <div class="flex-row space-between" style="align-items: stretch;margin-bottom: 2%;">
                      <div style="flex-grow: 5;">
                        {{--<a class="btnprn" v-show="order.delivered == 1 & order.pending == 0"><button id="print@{{ order.id }}" @mouseover='txt(order.id)'  @mouseout='delTxt(order.id)' class="btn btn-primary">آماده تحویل </button></a>--}}

                        <a class="btnprn" v-if="order.delivered == 1"> <button class="btn btn-primary">آماده تحویل </button></a>

                        <button v-if="order.delivered == 0 && order.pending == 1" id="proc@{{order.id}}" @click="cooking(order.id)"   class="btn btn-info">درحال پخت</button>

                        <button v-if="order.delivered == 0 && order.pending == 0" id="proc2@{{order.id}}" @click="sendForCook(order.id)"  class="btn btn-danger">ارسال جهت پخت</button>
                      </div>
                      <div  class="flex-row" style="align-content: flex-end;flex-grow: 1;direction: ltr;">
                        <button style="margin-right: 4%;"  id="print@{{ order.id }}"   style="cursor: pointer;" @click="printBill(order.id)"><i class="fa fa-print" aria-hidden="true"></i></button>
                        <button style="margin-right: 4%;"  id="cancel@{{ order.id }}"   style="cursor: pointer;" @click="cancel(order.id)"><i class="fa fa-ban" aria-hidden="true"></i></button>
                      </div>
                    </div>
                    <!-- <br/> -->
                    <button v-if="order.paid == 1" class="btn btn-success">پرداخت شد </button>
                    <button class="btn btn-warning"  v-else id="payment@{{order.id}}" @click="paid(order.id)">در انتظار پرداخت کاربر</button>
                </div>
            </div>
        </div>
        {{--<div style="margin-right: 600px;" v-show="loader" class="loader"></div>--}}
        <div id="myModal" class="modal">

            <!-- Modal content -->
            <div class="modal-content">
                <div class="text-center">
                    <span class="close">&times;</span>
                    <h2>خرید نسخه اصلی</h2>
                    <h3 style="padding-top: 50px">@{{ message }}</h3>
                </div>
                <div class="container flex-column space-between" style="padding: 0px;height: 100%;" id="orderList">
                </div>
            </div>

        </div>





        <style>

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
                color:#FFF;
                width:120px;
                height:24px;
                margin: 300px auto;
            }

            /* Safari */
            @-webkit-keyframes spin {
                0% { -webkit-transform: rotate(0deg); }
                100% { -webkit-transform: rotate(360deg); }
            }

            @keyframes spin {
                0% { transform: rotate(0deg); }
                100% { transform: rotate(360deg); }
            }
            .modal {
                display: @{{ modal }}; /* Hidden by default */
                position: fixed; /* Stay in place */
                z-index: 1000; /* Sit on top */
                left: 0;
                top: 0;
                width: 100%; /* Full width */
                height: 100%; /* Full height */
                overflow: auto; /* Enable scroll if needed */
                background-color: rgb(0,0,0); /* Fallback color */
                background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
            }
            /* Modal Content/Box */
            .modal-content {
                background-color: #fefefe;
                /*margin: 15% auto; */
                padding: 20px;
                border: 1px solid #888;
                margin: 5% auto;
                /*width: 100%;
                height: 100%;*/
                width: 60%; /* Full width */
                height: 70%; /* Full height */
            }

            /* The Close Button */
            .close {
                color: #aaa;
                float: right;
                font-size: 28px;
                font-weight: bold;
            }

            .close:hover,
            .close:focus {
                color: black;
                text-decoration: none;
                cursor: pointer;
            }
            @media only screen and (max-width: 1024px) {
                .modal-content {padding: 10px;width: 65%; height: 65%;margin: 10% auto;}
                /*.orderListContainer {height: 530px;}*/
            }
            @media only screen and (max-width: 768px) {
                .modal-content {padding: 10px;width: 80%; height: 80%;margin: 10% auto;}
                /*.orderListContainer {height: 520px;}*/
            }

            @media only screen and (max-width: 450px) {
                .modal-content {padding: 5px;width: 100%; height: 100%;margin: 0% auto;}
                /*.orderListContainer {height: 450px;}*/
            }

            @media only screen and (max-width: 400px) {
                .modal-content {padding: 5px;width: 100%; height: 100%;margin: 0% auto;}
                /*.orderListContainer {height: 390px;}*/
            }
            @media only screen and (max-width: 360px) {
                .modal-content {padding: 5px;width: 100%; height: 100%;margin: 0% auto;}
                /*.orderListContainer {height: 365px;}*/
            }
            @media only screen and (max-width: 340px) {
                .modal-content {padding: 5px;width: 100%; height: 100%;margin: 0% auto;}
                /*.orderListContainer {height: 290px;}*/
            }
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
        </style>
    </div>

    <script type="text/javascript" src="{{URL::asset('js/printPage.js')}}"></script>
    <script>

        window.addEventListener("load", function(){
            var load_screen = document.getElementById("load_screen");
            document.body.removeChild(load_screen);
        });
        var modal = document.getElementById('myModal');
        // Get the <span> element that closes the modal
        var span = document.getElementsByClassName("close")[0];
        // When the user clicks on <span> (x), close the modal
        span.onclick = function() {
            modal.style.display = "none";
        };
        function myFunction() {
            var x = document.getElementById("myTopnav");
            if (x.className === "topnav") {
                x.className += " responsive";
            } else {
                x.className = "topnav";
            }
        }

        new Vue({
            el:'#order',
            data:{
                orders:[],
                orderCounter:'بازشماری سفارش',
                disbtn:false,
                message:'',
                modal:'none',
                loader:true,
                hideCart:true,
                tax:0
            },
            created:function () {

                vm = this;
                this.loader = false;
                axios.post('{{route('getOldOrders')}}').then(function (response) {
                    vm.orders = response.data;
//                console.log(response.data)

                });

                axios.get('{{route('getTax')}}').then(function (response) {
                    vm.tax = response.data;
//                console.log(response.data)

                });
                setTimeout(function () {
                    window.location.href = {!! json_encode(route('orders')) !!}
                },120000);

//            this.getAllOrders();

                this.getMsg();

            },
            ready:function(){
                this.hideCart = false

            },
            methods:{
                cancel:function(id){
                   vm = this;
                    axios.post('{{route('OrderCancel')}}',{'id':id,'old':'old'}).then(function (response) {
//                         console.log(response.data)
                        vm.orders = response.data;
                    })
                },
                printBill:function(id){
                    axios.get('{{route('bill')}}'+'?id='+id).then(function (response) {

                    })
                },
                getMsg:function () {
                    vm = this;
                    axios.get('{{route('getOrderId')}}').then(function (response) {
                        var id = response.data;
                        if({{\App\Manager::first()->original}} != 1)
                        {

                            if (id == 50 && {!! json_encode(Cache::get('warn1')) !!} == 0) {
                                vm.modal = 'block';
                                vm.message = 'مشترک گرامی، تنها ۱۰۰ سفارش دیگر قابل ثبت می باشد. برای خرید نسخه اصلی این برنامه، با شماره ۰۹۱۰۴۹۶۳۷۳۴ و یا ۰۹۳۷۱۸۶۹۵۶۸ تماس حاصل فرمایید'
                                {!! json_encode(Cache::put('warn1',1)) !!}
                            } else if ( id == 100 && {!! json_encode(Cache::get('warn2')) !!} == 0) {
                                vm.modal = 'block';
                                vm.message = 'مشترک گرامی، تنها ۵۰ سفارش دیگر قابل ثبت می باشد. برای خرید نسخه اصلی این برنامه، با شماره ۰۹۱۰۴۹۶۳۷۳۴ و یا ۰۹۳۷۱۸۶۹۵۶۸ تماس حاصل فرمایید'
                                {!! json_encode(Cache::put('warn1',2)) !!}
                            } else if (id == 130 && {!! json_encode(Cache::get('warn3')) !!} == 0) {
                                vm.modal = 'block';
                                vm.message = 'مشترک گرامی، تنها ۲۰ سفارش دیگر قابل ثبت می باشد. برای خرید نسخه اصلی این برنامه، با شماره ۰۹۱۰۴۹۶۳۷۳۴ و یا ۰۹۳۷۱۸۶۹۵۶۸ تماس حاصل فرمایید'
                                {!! json_encode(Cache::put('warn1',3)) !!}
                            } else if (id == 150 && {!! json_encode(Cache::get('warn4')) !!} == 0) {
                                vm.modal = 'block';
                                vm.message = 'مشترک گرامی، برای خرید نسخه اصلی این برنامه، با شماره ۰۹۱۰۴۹۶۳۷۳۴ و یا ۰۹۳۷۱۸۶۹۵۶۸ تماس حاصل فرمایید'
                                {!! json_encode(Cache::put('warn1',4)) !!}
                            }
                        }
                    });
                    setTimeout(vm.getMsg,15000);
                },
                reset:function () {
                    axios.get('{{route('reset')}}').then(function (response) {
                    });
                    this.orderCounter = 'باز شماری شد';
                    this.disbtn = true
                },
                getAllOrders:function () {
                    vm = this;
                    axios.post('{{route('getOldOrders')}}').then(function (response) {
                        vm.orders = response.data;
                    });
                    setTimeout(vm.getAllOrders,10000);
                },
                cooking:function (id) {
                    vm = this;
                    axios.post('{{route('delivered',['time'=>'old'])}}',{'id':id}).then(function (response) {

//                        document.getElementById('proc2'+id).hidden=true;
//                        document.getElementById('print'+id).hidden=false;
//                        document.getElementById('proc'+id).className='btn btn-primary';
                        vm.orders = response.data;
                        console.log(vm.orders);

                    })



                },
                sendForCook:function (id) {
                    vm = this;
                    axios.post('{{route('pending',['time'=>'old'])}}',{'id':id}).then(function (response) {
                        console.log(response.data);
//                    document.getElementById('proc'+id).hidden=true;
//                    document.getElementById('proc2'+id).hidden=false;
                        vm.orders = response.data;
                    });
                },
                paid:function(id) {
                    vm = this;
                    axios.post('{{route('paid',['time'=>'old'])}}',{'id':id}).then(function (response) {
//            document.getElementById('payment'+id).innerHTML='پرداخت شد';
//            document.getElementById('payment'+id).className='btn btn-success';
//            console.log(response.data)
                        vm.orders = response.data;

                    })
                },
                txt:function (id) {
                    document.getElementById('print'+id).innerHTML='چاپ رسید';
                    // document.getElementById('print'+id).printPage();

                },
                delTxt:function (id) {
                    document.getElementById('print'+id).innerHTML='آماده تحویل ';
                }
            },

        })
    </script>
@endsection
