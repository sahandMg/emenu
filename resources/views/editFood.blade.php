@extends('master.layout')
@section('content')

<div class="container" style="margin-top: 2%;" id="edit">
  <h4>تغییر اطلاعات غذا</h4>
    @if(session('message'))
        <div class="alert alert-success" role="alert">
            {{session('message')}}
        </div>
    @endif
    <form action="{{route('editFood',$type->id)}}" method="post" enctype="multipart/form-data" style="margin-bottom: 2%;">
        <input type="hidden" name="_token" value="{{csrf_token()}}">
      <div class="form-group">
       <label>نام غذا</label>
       <input type="text" name="foodName" class="form-control" placeholder="{{$type->name}}">
      </div>
      <div class="form-group">
       <label>توضیح غذا</label>
       <input type="text" name="foodDes" class="form-control" placeholder="{{$type->description}}">
      </div>
      <div class="form-group">
       <label>قیمت غذا (تومان)</label>
       <input type="number"  name="foodPrice"  class="form-control" placeholder="{{$type->price}}">
      </div>
      <div class="form-group">
        <label for="sel1">انتخاب دسته غذا</label>
          <select  class="form-control" id="sel1" name="foodCategory">
              <option selected  disabled>دسته بندی</option>
              <option v-for="type in categories" value="@{{ type }}">@{{ type }}</option>
          </select>
      </div>
      <div class="form-group">
       <label>عکس غذا</label>
       <input type="file" name="foodImage" class="form-control">
      </div>
      <button type="submit" class="btn btn-primary col-md-1 col-sm-1">ذخیره</button>
    </form>

</div>

<style>
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
</style>
<script>
new Vue({
    el:'#edit',
    data:{
        categories:['']
    },
    created:function () {

        vm = this;
        axios.get('{{route('getCats')}}').then(function (response) {
            vm.categories = response.data

        })

    }
})
</script>
@endsection