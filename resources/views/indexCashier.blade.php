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
@endsection
