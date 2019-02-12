@extends('master.layout')
@section('content')

    <div id="load_screen"><div id="loading">در حال لود</div></div>
    <div class="container" style="margin-top: 2%;" id="order">
        <div class="flex-row space-around">
            <div class="flex-row flex-start">
               <a href="{{route('orders')}}" class="btn btn-danger" style="margin-left: 1%;margin-right: 1%;border: 2px solid black">سفارشات لغو شده</a>
                <!-- <a href="{{route('ytd')}}" class="btn btn-warning" style="margin-left: 1%;margin-right: 1%"> سفارشات دیروز</a> -->
                <a href="{{route('orders')}}" class="btn btn-success" style="margin-left: 1%;margin-right: 1%;"> سفارشات امروز</a>
            </div>
            {{--<button @click="reset" :disabled="disbtn" href="{{route('reset')}}" class="btn btn-danger">@{{ orderCounter }}</button>--}}
        </div>
<br/>
<table class="table">
    <thead>
      <tr>
        <th>شماره سفارش</th>
        <th>شماره میز</th>
        <th>شرح سفارش</th>
        <th>زمان</th>
        <th>مجموع کل + مالیات (تومان)</th>
        <th colspan="2">توضیحات</th>

        <th>پرینت</th>

      </tr>
    </thead>
    <tbody>
      <tr  v-for="order in orders">
        <td>@{{order.order_number}}</td>
        <td>@{{order.table_id}}</td>
        <td>
           <button class="btn showDetail">مشاهده</button>
           <div style="display: none;">
             <table class="table table-striped">
                <thead>
                <tr>
                  <th>نام</th>
                  <th>تعداد</th>
                  <th>قیمت</th>
                  <th>گیرنده سفارش</th>
                 </tr>
                </thead>
                <tbody>
                   <tr v-for="type in order.order">
                       <td> @{{type.foodName}}</td>
                       <td> @{{type.foodNumber}} </td>
                       <td v-if="type.price%1000 != 0">@{{(parseInt(type.price/1000))+","+(type.price%1000)}}</td>
                       <td v-if="type.price%1000 == 0">@{{(parseInt(type.price/1000))+","+'000'}}</td>
                       <td>
                           @if(Auth::guard('cashier')->check())
                            {{Auth::guard('cashier')->user()->username}}
                            @else
                            {{Auth::guard('manager')->user()->username}}
                            @endif
                       </td>
                    </tr>
                 </tbody>
             </table>
           </div>
        </td>
        <td><span>@{{order.created_at}}</span></td>
        <td>
           <span v-if="order.price*(1+tax/100)%1000 != 0">@{{(parseInt(order.price*(1+tax/100)/1000))+","+parseInt((order.price*(1+tax/100))%1000)}}</span>
           <span v-if="order.price*(1+tax/100)%1000 == 0">@{{(parseInt(order.price*(1+tax/100)/1000))+","+'000'}}</span>
        </td>
        <td colspan="2">@{{order.info}}</td>

        <td>
           <button style="margin-right: 4%;"  id="print@{{ order.id }}"   style="cursor: pointer;" @click="printBill(order.id)"><img height="20" width="20" src="{{(('images/icons/printer.png'))}}"></button>
        </td>

      </tr>
    </tbody>
   </table>
   <br/>
   <div>
    <ul class="pagination">
   </ul>
  </div>

       <!--  <div id="myModal" class="modal">
            <div class="modal-content">
                <div class="text-center">
                    <span class="close">&times;</span>
                    <h2>خرید نسخه اصلی</h2>
                    <h3 style="padding-top: 50px">@{{ message }}</h3>
                </div>
                <div class="container flex-column space-between" style="padding: 0px;height: 100%;" id="orderList">
                </div>
            </div>
        </div> -->

     <div id="myModal" class="modal">
            <!-- Modal content -->
            <div class="modal-content">
              <div class="text-center">
               <span class="close">&times;</span>
               <h2>شرح سفارش</h2>
              </div>
              <br/>
              <div id="showOrderDetail"></div>
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
        var ordersNumber = {!! $cancels !!}
         for(var i=0; i<ordersNumber;i++) {
          if (i==0) {
            $(".pagination").append(`<li class="page-item active"><a class="page-link" href="#">`+(parseInt(i)+1)+`</a></li>`);
          } else {
             $(".pagination").append(`<li class="page-item"><a class="page-link" href="#">`+(parseInt(i)+1)+`</a></li>`);
          }     
        }
        var numberOfPage = 1 ;
         var tableDetail;
        $(document).on('click', '.showDetail', function(){
          console.log("show detail");
          console.log($(this).parent().children().eq(1));

          $("#myModal").css("display","block");
          tableDetail = $(this).parent().children().eq(1);
          // tableDetail.show();
          console.log("tableDetail");console.log(tableDetail);
          $("#showOrderDetail").append(tableDetail.clone().show());
        });

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
            tableDetail.hide();
            $("#showOrderDetail").empty();
        };

        function myFunction() {
            var x = document.getElementById("myTopnav");
            if (x.className === "topnav") {
                x.className += " responsive";
            } else {
                x.className = "topnav";
            }
        }

        var vueGetOriders = new Vue({
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
                axios.get('{{route('getCancel')}}'+'?num=').then(function (response) {
                    vm.orders = response.data;
                console.log(response.data)

                });

                axios.get('{{route('getTax')}}').then(function (response) {
                    vm.tax = response.data;
//                console.log(response.data)

                });


            },

            methods:{
         
                printBill:function(id){
                    axios.get('{{route('bill')}}'+'?id='+id).then(function (response) {

                    })
                },

                getAllOrders: function() {
                   axios.get('{{route('getCancel')}}'+'?num='+numberOfPage).then(function (response) {
                    vm.orders = response.data;
                    console.log(response.data)
                   });
                }

            },


        })

        $(document).on('click', '.page-item', function(){
           $(".active").removeClass("active");
           $(this).addClass("active");
           console.log("page-item");
           console.log($(this).text());
           numberOfPage = parseInt($(this).text());
           console.log(vueGetOriders);
           vueGetOriders.getAllOrders();
        });
    </script>
@endsection
