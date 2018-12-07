<!DOCTYPE html>
<html>
<head>
	<title>Hot</title>
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
	<script type="text/javascript" src="{{URL::asset('js/jquery.js')}}"></script>
	<link type="text/css" rel="stylesheet" href="{{URL::asset('css/bootstrap.min.css')}}">
	<link type="text/css" rel="stylesheet" href="{{URL::asset('css/main.css')}}">
	<link rel="stylesheet" href="{{URL::asset('css/font-awesome.min.css')}}">
	<script src="{{URL::asset('js/ajax_vue.js')}}"></script>
	<script src="{{URL::asset('js/vue_resource.js')}}"></script>
	<script src="{{URL::asset('js/lodash.js')}}"></script>
	<script src="{{URL::asset('js/axios.js')}}"></script>

</head>
<body>
<header>
	@if(!is_null($info->image))
		<img src="{{asset('storage/images/'.$info->image)}}" />
	@else
		<img src="{{asset('storage/images/burger1.jpg')}}" />
	@endif
      <div class="header">
		  @if(!is_null($info->logo))
       <img src="{{asset('storage/images/'.$info->logo)}}" />
		  @else
			  <img src="{{asset('storage/images/HOT.jpeg')}}" />
		  @endif
       <h1>{{$info->name}}</h1>
      </div>
</header>

<nav id="nav">
  <ul class="menu">
	  @for($t=0;$t<count($foods);$t++)
		<li><a href="#section{{$t}}">{{$types[$t]}}</a></li>
	  @endfor
	  @if(!is_null(DB::table('orders')->where('token',Session::token())->orderBy('id','dsc')->first()))
	  <li><a href="{{route('userBill',['id'=>Session::token()])}}">رسید سفارش</a></li>
		@endif
  </ul>
</nav>
<br/>
@for($i=0;$i<count($foods);$i++)
	<section id="section{{$i}}" class="container">

	<!-- <h3>{{$types[$i]}}</h3> -->
	<div class="row" style="margin: auto;" id="app">

			@foreach($foods[$i] as $item)
				<div class="col-sm-12 col-md-6 col-lg-4 food-card">
					<div class="card">
						<img class="card-img-top" width="300" height="200" src="{{asset('storage/images/'.$item->image)}}" alt="Card image cap">
						<div class="card-body" id="cardId{{$item->id}}">
							<div class="row flex-row space-between">
								<h5 class="card-title"> {{$item->name}}</h5><span class="text-left">{{$item->price}} تومان</span>
							</div>
							<p class="card-text">{{$item->description}}</p>
							<div class="flex-row flex-start"  data-num="{{$item->id}}" id="{{$item->id}}" style="direction: ltr;align-items: center;">
								<button class="btnNumber btnMines">-</button><span class="foodNumber">0</span><button class="btnNumber btnPlus">+</button>
								<input hidden value="{{$item->price}}" />
							</div>
						</div>
					</div>
				</div>
			@endforeach


 </div>
</section>
@endfor
<a href="#" class="float" id="myBtn"><i class="fa fa-shopping-basket fa-6" aria-hidden="true"></i> <span id="cart">0</span></a>
<a href="#" class="float-left-btn" style=""></i>رسید خرید</a>

<!-- The Modal -->
<div id="myModal" class="modal">

  <!-- Modal content -->
  <div class="modal-content">
   <div class="text-center">
   	  <span class="close">&times;</span>
   	  <h2>سفارش شما</h2>
   </div>
    <div class="container flex-column space-between" style="padding: 0px;height: 100%;" id="orderList">
   </div>
  </div>

</div>

<script type="text/javascript">
	// Page Scroll
jQuery(document).ready(function ($) {
	$('a[href*=#]:not([href=#])').click(function() {
		if (location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'')
			|| location.hostname == this.hostname) {

			var target = $(this.hash);
			target = target.length ? target : $('[name=' + this.hash.slice(1) +']');
			if (target.length) {
				$('html,body').animate({
					scrollTop: target.offset().top - 32
				}, 1000);
				return false;
			}
		}
	});
});

// Fixed Nav
jQuery(document).ready(function ($) {
	$(window).scroll(function(){
		var scrollTop = 142;
		if($(window).scrollTop() >= scrollTop){
			$('nav').css({
				position : 'fixed',
				top : '0'
			});
		}
		if($(window).scrollTop() < scrollTop){
			$('nav').removeAttr('style');
		}
	})

  // Active Nav Link
  $('nav ul li a').click(function(){
         $('nav ul li a').removeClass('active');
         $(this).addClass('active');
    });

		  $(document).on("scroll", onScroll);
})

function onScroll(event){
		var scrollPos = $(document).scrollTop();
		$('#nav a').each(function () {
				var currLink = $(this);
			 var refElement = $(currLink.attr("href"));
				if (refElement.position().top <= scrollPos && refElement.position().top + refElement.height() > scrollPos) {
						$('nav ul li a').removeClass("active");
						currLink.addClass("active");
				}
				else{
						currLink.removeClass("active");
				}
		});
}

var order = [];
$('.btnMines').hide();
	var allPrice = 0;
$(document).on('click', '.btnMines', function () {
// $('.btnMines').click(function(){
  // console.log('btnMines');//console.log($(this).parent().attr('id'));
     var temp = $(this).parent().attr('data-num');console.log('data-num : '+temp);
	 var foodId = $(this).parent().attr('data-num') ;
	 var foodNumber = $('#'+foodId+' span').text();//console.log('foodNumber : '+foodNumber);
	 var cardId = $(this).parent().parent().attr('id') ;
	 var foodName = $('#'+cardId+' h5').text();//console.log('foodName : '+foodName);
	 var price = $('#'+foodId+' input').val();
	 foodNumber = foodNumber - 1 ;
	 if(!(foodNumber < 0)) {
		 $('#'+foodId+' span').text(foodNumber);//console.log('foodNumber : '+foodNumber);
		 setOrder(foodId, foodNumber, price, foodName);
	 }
	 if(foodNumber == 0) {
	 	$('#'+foodId+' .btnMines').hide();
	 }
	 renderModal();
});
$(document).on('click', '.btnPlus', function () {
// $('.btnPlus').click(function(){
			 //console.log('btnPlus');console.log($(this).parent().attr('id'));
			 var temp = $(this).parent().attr('data-num');console.log('data-num : '+temp);
			 var foodId = $(this).parent().attr('data-num') ;
			 var foodNumber = $('#'+foodId+' span').text();//console.log('foodNumber : '+foodNumber);
			 var cardId = $('#'+foodId).parent().attr('id') ;//console.log('cardId : '+cardId);
	         var foodName = $('#'+cardId+' h5').text();//console.log('foodName : '+foodName);
			 var price = $('#'+foodId+' input').val();
			 foodNumber = parseInt(foodNumber) + 1 ;
			 $('#'+foodId+' span').text(foodNumber);//console.log('foodNumber : '+foodNumber);
			 setOrder(foodId, foodNumber, price, foodName);
			 $('#'+foodId+' .btnMines').show();
			 renderModal();
});

function setOrder(foodId, foodNumber, price, foodName) {
 if(order.length > 0) {
   for (var i = 0; i<order.length; i++) {
     if(order[i].foodId == foodId) {
     	if(foodNumber == 0) {
     	  removeFromOrder(i);console.log("remove Food");break;

     	} else {
     		order[i].foodNumber = foodNumber;console.log("old food added");break;
     	}
	 } else if( (i+1) == order.length) {
      order.push({foodId: foodId, foodNumber: foodNumber, price: price, foodName: foodName});
      console.log("new food added");
	 }
	}
 } else {
   order.push({foodId: foodId, foodNumber: foodNumber, price: price, foodName: foodName});
   console.log("new food added");
 }
 $('#cart').text(order.length);
	console.log('order : ');console.log(order);
}
// food1
// Get the modal
var modal = document.getElementById('myModal');

// Get the button that opens the modal
var btn = document.getElementById("myBtn");

// Get the <span> element that closes the modal
var span = document.getElementsByClassName("close")[0];

// When the user clicks on the button, open the modal
btn.onclick = function() {
	modal.style.display = "block";
	renderModal();
}

function removeFromOrder(index) {
  if (index > -1) {
    order.splice(index, 1);
  }
}

function renderModal() {
	$("#orderList").empty();
    if(order.length > 0) {
       var htmlString = '<div class="orderListContainer"><ul class="orderList">'; //<h3 class="text-center">سفارش شما</h3>
      var allPrice = 0 ;
       for(var i =0 ; i< order.length; i++) {
       	 allPrice = allPrice + (order[i].price*order[i].foodNumber) ;
       	 htmlString = htmlString+
       	                 `<li class="flex-row space-betweena">
       	                    <div class="flex-column space-between">
								 <h3 class="card-title" style="margin-bottom: 0.2rem">`+order[i].foodName+`</h3><small>`+order[i].price*order[i].foodNumber+` تومان</small>
							</div>
							<div class="flex-row flex-start" data-num="`+order[i].foodId+`" style="direction: ltr;align-items: center;">
								<button class="btnNumber btnMines">-</button><span class="foodNumber">`+order[i].foodNumber+`</span><button class="btnNumber btnPlus">+</button>
								<input hidden value="`+order[i].price+`" />
							 </div>
							</li><hr/>`
       }
       htmlString = htmlString +'</ul></div>';
       htmlString = htmlString +`
         <div>
             <div class="flex-row space-between flex-center-align">
                <span class="roomNumber">شماره میز : </span>
                <input onkeyup="tableId()" type="number" id="table_id" class="form-control" name="roomNumber">
             </div>
             <br/>
             <div class="flex-row space-between flex-center-align">
                <span class="roomNumber">توضیحات : </span>
                <textarea rows="3" type="text" id="info" class="form-control" name="description"> </textarea>

             </div>
             <div class="flex-row space-between" style="margin-top: 1%;margin-bottom: 1%;">
               <span>مجموع سفارش شما :</span><span><b>`+allPrice+` تومان</b></span>
             </div>
             <button onclick="sendOrder()" class="btn btn-success" id="btn" disabled="true" style="display: block;margin: auto;">ثبت سفارش</button>
           </div>
       `;
       $('#orderList').append(htmlString);
    } else {
       $('#orderList').append('<h2>لیست سفارش شما خالی است.</h2>');
    }
}

// When the user clicks on <span> (x), close the modal
span.onclick = function() {
    modal.style.display = "none";
};

function tableId() {


	 if(document.getElementById('table_id').value== 0){

		 document.getElementById('btn').disabled=true;
	 }else{
		 document.getElementById('btn').disabled=false;
	 }



}

function sendOrder() {
	var info = document.getElementById('info').value;
	var table_id = document.getElementById('table_id').value;
	axios.post('{{route('sendOrder')}}',{'order':order,'table_id':table_id,'total_price':allPrice,'info':info}).then(function (response) {

		console.log(response.data)
		window.location = {!! json_encode(route('userBill',['id'=>Session::token()])) !!};
	})


}

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
};
new Vue({
	el:'#app',
	data:{

		delivered:0,
		stat:0,
		start:0
	},
	created:function () {

		if({!! json_encode(!is_null($order)) !!}){
				this.status();

		}

	},
	methods:{
		status:function () {

				vm = this;
				axios.get('{{route('orderStatus')}}' + '?token=' +{!! json_encode(Session::token()) !!}).then(function (response) {

					vm.stat = response.data;
					if(vm.stat == 404){

					}else{

						if (vm.stat == 1) {
//							alert('غذای شما حاضر است');
							vm.start = new Date().getTime();
							axios.post('{{route('rmSession')}}',{'token':{!! json_encode(Session::token()) !!}}).then(function (response) {

								console.log(response.data)
							})
						}else{

							setTimeout(vm.status, 10000);
						}
					}

				});

		},

	}
})
</script>
<style type="text/css">
.allPrice {   }
.roomNumber {
	width: 40%;
}
.orderListContainer {overflow:hidden; overflow-y:scroll;}
.orderList { padding: 0px;  }
.orderList li { list-style: none; width: 100%; height: 80%;}
.float{
	position:fixed;
	width:80px;
	height:40px;
	bottom:10px;
	right:10px;
	background-color:#0C9;
	color:#FFF;
	border-radius:10px;
	text-align:center;
	box-shadow: 2px 2px 3px #999;font-size: 25px;
}
.float-left-btn{
	position:fixed;
	width:100px;
	height:40px;
	bottom:10px;
	left: 10px;
	background-color:#0C9;
	color:#FFF;
	border-radius:10px;
	text-align:center;
	box-shadow: 2px 2px 3px #999;font-size: 20px;
}
header {
	position: relative;
}
.header {
	/*background: linear-gradient(-90deg, #171717, transparent);*/
	background-color: rgba(0,0,0,0.5);
	/*background-color: #171717;*/
  position: absolute;top: 0px;left: 0px;right: 0px;
  /*padding: 20px 0;*/
  /*background: #fff;*/
  padding-top: 6px;
  font: normal 2em / normal 'Nixie One', serif;
  color: #ff6e6f;
  text-align: center;margin: auto;
  width: 100%;height: 100%;
}
.header img {
	margin: 0px;margin-top: 80px;
	display: inline-block;
	/*margin-right: 10px;margin-top: 10px;*/
	width: 100px;height: 100px;
}
/* The Modal (background) */
.modal {
    display: none; /* Hidden by default */
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
    width: 80%; /* Full width */
    height: 90%; /* Full height */
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
   .header img {
	width: 80px;height: 80px;margin-top: 25px;
    }
    .modal-content {padding: 10px;width: 80%; height: 80%;margin: 10% auto;}
    /*.orderListContainer {height: 520px;}*/
 }

@media only screen and (max-width: 450px) {
   .header img {
	width: 60px;height: 60px;margin-top: 25px;
    }
   .modal-content {padding: 5px;width: 100%; height: 100%;margin: 0% auto;}
   /*.orderListContainer {height: 450px;}*/
  }

 @media only screen and (max-width: 400px) {
   .header img {
	width: 60px;height: 60px;margin-top: 25px;
    }
   .modal-content {padding: 5px;width: 100%; height: 100%;margin: 0% auto;}
   /*.orderListContainer {height: 390px;}*/
  }
  @media only screen and (max-width: 360px) {
   .header img {
	width: 60px;height: 60px;margin-top: 25px;
    }
   .modal-content {padding: 5px;width: 100%; height: 100%;margin: 0% auto;}
   /*.orderListContainer {height: 365px;}*/
  }
  @media only screen and (max-width: 340px) {
   .header img {
	width: 60px;height: 60px;margin-top: 25px;
    }
   .modal-content {padding: 5px;width: 100%; height: 100%;margin: 0% auto;}
   /*.orderListContainer {height: 290px;}*/
  }
h1 {
  font-size: 1.60em;
}
h3 {
	font-size: 1rem;font-weight: 700;text-align: right;
}
nav {
  margin: 0;
  padding: 0;
  background: #ff6e6f;
  overflow-x: hidden;
  overflow-y: hidden;	z-index: 100;
}
nav ul {
  list-style: none;text-align: center;margin: 0;
  white-space: nowrap;
  overflow-x: auto;
  overflow-y: hidden;
  -webkit-overflow-scrolling: touch;
}
nav ul li {
  display: inline-block; */
  /* margin: 0 -4px; */
  padding: 5px 0;
}
nav ul li a {
  padding: 10px 10px;
  font: normal 1em / normal 'Open Sans Condensed', sans-serif;
  color: #fff;
	text-decoration: none; display: block;
  /* text-transform: uppercase;width: inherit;height: inherit; */
}
nav ul li a:hover,
nav ul li a.active {
  background: #343434;list-style: none;text-decoration: none;
}
section {
  /* padding: 20px; */
  /* height: 500px; */
  /* font: normal 2em / normal 'Open Sans Condensed', sans-serif; */
}
section#section1,
section#section3 {
  /* background: #343434; */
  /* color: #eee; */
}
section#section2,
section#section4 {
  /* background: #666; */
  /* color: #eee; */
}
footer {
  padding: 20px;
  background: #ff6e6f;
  font: normal 1em 'Open Sans Condensed', sans-serif;
  color: #fff;
  text-align: center;
}
.food-card {
   margin-bottom: 10px;
}


</style>
</body>
</html>
