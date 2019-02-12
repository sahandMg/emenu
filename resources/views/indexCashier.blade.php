{{--  @extends('master.layout')
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
                <td>
                  <div class="row" id="{{$item->id}}">
                   <button type="button" class="btn btn-danger btnNumber btnMines" style="background-color: red;">-</button>
                    <input style="margin-left: 3%;margin-right: 3%;" min="0" placeholder="0"  class="form-control col-md-3 col-sm-3" name="{{$item->id}}" value="0">
                   <button type="button" class="btn btn-success btnNumber btnPlus" style="border-radius: 20px;background-color: #00c500">+</button>
                  </div>
                </td>
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
   .btnMines {
      padding-right: 14px;padding-left: 14px;
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
</style>
<script type="text/javascript">
(function($) {
    $.fn.invisible = function() {
        return this.each(function() {
            $(this).css("visibility", "hidden");
        });
    };
    $.fn.visible = function() {
        return this.each(function() {
            $(this).css("visibility", "visible");
        });
    };
}(jQuery));
// $('.btnMines').hide();
$('.btnMines').invisible();
  $(document).on('click', '.btnMines', function () {
    console.log("btnMines");
    var parentDivId = $(this).parent().attr('id');
    console.log(parentDivId);
    var num = $('#'+parentDivId+' input').val();
    if (parseInt(num) >0 ) {
      num = parseInt(num) - 1 ;
      $('#'+parentDivId+' input').val(num);
    }

    if (parseInt(num) == 0 ) {
      $('#'+parentDivId+' .btnMines').invisible();
    }
    console.log(num);
  });
  $(document).on('click', '.btnPlus', function () {
    console.log("btnPlus");
    var parentDivId = $(this).parent().attr('id');
    console.log(parentDivId);
    var num = $('#'+parentDivId+' input').val();
      num = parseInt(num) + 1 ;
      $('#'+parentDivId+' input').val(num);
      $('#'+parentDivId+' .btnMines').visible();
      console.log(num);
  });
</script>
@endsection  --}}


@extends('master.layout')
@section('content')

<div class="container" style="background-color: #50555a;width: 100%;max-width: 2000px;height: 100%;">
<br/>
  <h1 class="text-center" style="color: white;">ثبت سفارش</h1>
  <br/>
  <form method="post" action="{{route('indexCashier')}}">
      <input type="hidden" name="_token" value="{{csrf_token()}}">


<section class="wrapper2">
    <ul class="tabs2">
        @foreach ($types as $key => $type)
            @if($key == 0)
            <li class="active2">{{$type}}</li>
            @else
            <li>{{$type}}</li>
            @endif
        @endforeach
    </ul>

  <ul class="tab__content2">
        @for($i=0;$i<count($foods);$i++)
        @if($i == 0)
        <li class="active2">
        @else
        <li>
        @endif

      <div class="content__wrapper2">
        <table class="table table-striped">
        <thead>
         <tr>
          <th>نام غذا</th>
          <th>قیمت</th>
          <th>تعداد</th>
         </tr>
       </thead>
       <tbody>
         @foreach($foods[$i] as $item)
            <tr>
                <td>{{$item->name}}</td>
                <td>{{$item->price}} تومان</td>
                <td>
                  <div class="row" id="{{$item->id}}">
                   <button type="button" class="btn btn-danger btnNumber btnMines" style="background-color: red;">-</button>
                    <input style="margin-left: 3%;margin-right: 3%;" min="0" placeholder="0"  class="form-control col-md-3 col-sm-3" name="{{$item->id}}" value="0">
                   <button type="button" class="btn btn-success btnNumber btnPlus" style="border-radius: 20px;background-color: #00c500">+</button>
                  </div>
                </td>
            </tr>

         @endforeach

      </tbody>
     </table>
      </div>
    </li>
    @endfor
  </ul>
</section>
@if(sizeof($foods) > 0)
   <br/>
      <button class="btn btn-success" type="submit" style="margin: auto;display: block;">ثبت سفارش</button>
   <br/>
   @endif
  </form>
</div>

<style>
   .btnMines {
      padding-right: 14px;padding-left: 14px;
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


.bg-color2 {
  background-color: #46a1de;
  transition-duration: .3s;
}

.text-color2 {
  color: #46a1de;
  transition-duration: .3s;
}

.wrapper2 {
  min-width: 600px;
  max-width: 1100px;
  margin: 0 auto;
}

.tabs2 {
  /*display: table;*/
  display: flex;
  flex-wrap: wrap;
  /*table-layout: fixed;*/
  width: 100%;
  -webkit-transform: translateY(5px);
  transform: translateY(5px);
}
.tabs2 > li {
  width: 20%;
  transition-duration: .25s;
  display: table-cell;
  list-style: none;
  text-align: center;
  padding: 20px 20px 25px 20px;
  position: relative;
  overflow: hidden;
  cursor: pointer;
  color: white;
}
.tabs2 > li:before {
  z-index: -1;
  position: absolute;
  content: "";
  width: 100%;
  height: 120%;
  top: 0;
  left: 0;
  background-color: rgba(255, 255, 255, 0.3);
  -webkit-transform: translateY(100%);
  transform: translateY(100%);
  transition-duration: .25s;
  border-radius: 5px 5px 0 0;
}
.tabs2 > li:hover:before {
  -webkit-transform: translateY(70%);
  transform: translateY(70%);
}
.tabs2 > li.active2 {
  color: #50555a;
}
.tabs2 > li.active2:before {
  transition-duration: .3s;
  background-color: white;
  -webkit-transform: translateY(0);
  transform: translateY(0);
}

.tab__content2 {
  background-color: white;
  position: relative;
  width: 100%;
  border-radius: 5px;
}
.tab__content2 > li {
  width: 100%;
  position: absolute;
  top: 0;
  left: 0;
  display: none;
  list-style: none;
}
.tab__content2 > li .content__wrapper2 {
  text-align: center;
  border-radius: 5px;
  width: 100%;
  padding: 45px 40px 40px 40px;
  background-color: white;
}

.content__wrapper2 h2 {
  width: 100%;
  text-align: center;
  padding-bottom: 20px;
  font-weight: 300;
}
.content__wrapper2 img {
  width: 100%;
  height: auto;
  border-radius: 5px;
}

.colors2 {
  text-align: center;
  padding-top: 20px;
}
.colors2 > li {
  list-style: none;
  width: 50px;
  height: 50px;
  border-radius: 50%;
  border-bottom: 5px solid rgba(0, 0, 0, 0.1);
  display: inline-block;
  margin: 0 10px;
  cursor: pointer;
  transition-duration: .2s;
  box-shadow: 0 2px 1px rgba(0, 0, 0, 0.2);
}
.colors2 > li:hover {
  -webkit-transform: scale(1.2);
  transform: scale(1.2);
  border-bottom: 10px solid rgba(0, 0, 0, 0.15);
  box-shadow: 0 10px 10px rgba(0, 0, 0, 0.2);
}
.colors2 > li.active-color2 {
  -webkit-transform: scale(1.2) translateY(-10px);
  transform: scale(1.2) translateY(-10px);
  box-shadow: 0 10px 10px rgba(0, 0, 0, 0.2);
  border-bottom: 20px solid rgba(0, 0, 0, 0.15);
}
.colors2 > li:nth-child(1) {
  background-color: #2ecc71;
}
.colors2 > li:nth-child(2) {
  background-color: #D64A4B;
}
.colors2 > li:nth-child(3) {
  background-color: #8e44ad;
}
.colors2 > li:nth-child(4) {
  background-color: #46a1de;
}
.colors2 > li:nth-child(5) {
  background-color: #bdc3c7;
}

 body {
  background-color: #50555a;
 }
</style>

<script type="text/javascript">

$(document).ready(function(){

  // Variables
  var clickedTab = $(".tabs2 > .active2");
  var tabWrapper = $(".tab__content2");
  var activeTab = tabWrapper.find(".active2");
  var activeTabHeight = activeTab.outerHeight();

  // Show tab on page load
  activeTab.show();

  // Set height of wrapper on page load
  tabWrapper.height(activeTabHeight);

  $(".tabs2 > li").on("click", function() {

    // Remove class from active tab
    $(".tabs2 > li").removeClass("active2");

    // Add class active to clicked tab
    $(this).addClass("active2");

    // Update clickedTab variable
    clickedTab = $(".tabs2 .active2");

    // fade out active tab
    activeTab.fadeOut(250, function() {

      // Remove active class all tabs
      $(".tab__content2 > li").removeClass("active2");

      // Get index of clicked tab
      var clickedTabIndex = clickedTab.index();

      // Add class active to corresponding tab
      $(".tab__content2 > li").eq(clickedTabIndex).addClass("active2");

      // update new active tab
      activeTab = $(".tab__content2 > .active2");

      // Update variable
      activeTabHeight = activeTab.outerHeight();

      // Animate height of wrapper to new tab height
      tabWrapper.stop().delay(50).animate({
        height: activeTabHeight
      }, 500, function() {

        // Fade in active tab
        activeTab.delay(50).fadeIn(250);

      });
    });
  });

  // Variables
  var colorButton = $(".colors2 li");

  colorButton.on("click", function(){

    // Remove class from currently active button
    $(".colors2 > li").removeClass("active-color2");

    // Add class active to clicked button
    $(this).addClass("active-color2");

    // Get background color of clicked
    var newColor = $(this).attr("data-color2");

    // Change background of everything with class .bg-color
    $(".bg-color2").css("background-color", newColor);

    // Change color of everything with class .text-color
    $(".text-color2").css("color", newColor);
  });
});



(function($) {
    $.fn.invisible = function() {
        return this.each(function() {
            $(this).css("visibility", "hidden");
        });
    };
    $.fn.visible = function() {
        return this.each(function() {
            $(this).css("visibility", "visible");
        });
    };
}(jQuery));
// $('.btnMines').hide();
$('.btnMines').invisible();
  $(document).on('click', '.btnMines', function () {
    console.log("btnMines");
    var parentDivId = $(this).parent().attr('id');
    console.log(parentDivId);
    var num = $('#'+parentDivId+' input').val();
    if (parseInt(num) >0 ) {
      num = parseInt(num) - 1 ;
      $('#'+parentDivId+' input').val(num);
    }

    if (parseInt(num) == 0 ) {
      $('#'+parentDivId+' .btnMines').invisible();
    }
    console.log(num);
  });
  $(document).on('click', '.btnPlus', function () {
    console.log("btnPlus");
    var parentDivId = $(this).parent().attr('id');
    console.log(parentDivId);
    var num = $('#'+parentDivId+' input').val();
      num = parseInt(num) + 1 ;
      $('#'+parentDivId+' input').val(num);
      $('#'+parentDivId+' .btnMines').visible();
      console.log(num);
  });
</script>
@endsection
