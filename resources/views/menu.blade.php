@extends('master.layout')
@section('content')
<div class="container" style="margin-top: 2%;" id="app" xmlns="http://www.w3.org/1999/html">
<!-- Tab links -->
<div class="tab" style="direction: rtl;">
    @if($errors->all() || session('message'))
        <button class="tablinks"   onclick="openCity(event, 'menu')">منو</button>
        <button class="tablinks" onclick="openCity(event, 'menuCategory')">اضافه کردن دسته غذا</button>
        <button class="tablinks" id="defaultOpen" onclick="openCity(event, 'menuFood')">اضافه کردن غذا</button>
    @elseif(session('categoryError'))
        <button class="tablinks"  onclick="openCity(event, 'menu')">منو</button>
        <button class="tablinks" id="defaultOpen"  onclick="openCity(event, 'menuCategory')">اضافه کردن دسته غذا</button>
        <button class="tablinks"  onclick="openCity(event, 'menuFood')">اضافه کردن غذا</button>
    @elseif(session('nav'))
        <button class="tablinks"   onclick="openCity(event, 'menu')">منو</button>
        <button class="tablinks" id="defaultOpen" onclick="openCity(event, 'menuCategory')">اضافه کردن دسته غذا</button>
        <button class="tablinks"  onclick="openCity(event, 'menuFood')">اضافه کردن غذا</button>
    @else
        <button class="tablinks" id="defaultOpen"   onclick="openCity(event, 'menu')">منو</button>
        <button class="tablinks" onclick="openCity(event, 'menuCategory')">اضافه کردن دسته غذا</button>
        <button class="tablinks"  onclick="openCity(event, 'menuFood')">اضافه کردن غذا</button>

    @endif

</div>
<!--   <div class="flex-row space-around">
    <a href="menuCategory.html" class="btn btn-primary">اضافه کردن دسته</a>
    <a href="" class="btn btn-primary">اضافه کردن غذا</a>
  </div>
  <hr/> -->
  <!-- Menu -->
 <div id="menu" class="tabcontent">

     @for($i=0;$i<count($foods);$i++)
         <h3 class="text-center" style="margin: 30px">{{$types[$i]}}</h3>
         <div class="row">
             @foreach($foods[$i] as $item)
                 <div class="col-sm-12 col-md-4 col-lg-4 food-card" style="margin-top: 35px">
                     <div class="card">
                         <img width="300" height="200" class="card-img-top" src="{{('images/'.$item->image)}}" alt="Card image cap">
                         <div class="card-body" id="cardId{{$item->id}}">
                             <div class="row flex-row space-between">

                                 @if($item->price%1000 == 0)
                                     <h5 class="card-title"> {{$item->name}}</h5><span class="text-left">{{floor($item->price/1000)}},{{'000'}} تومان</span>
                                 @else
                                     <h5 class="card-title"> {{$item->name}}</h5><span class="text-left">{{floor($item->price/1000)}},{{$item->price%1000}} تومان</span>
                                 @endif
                             </div>
                             <p class="card-text">{{$item->description}}</p>
                             <div class="flex-row space-around" id="{{$item->id}}">
                                 <a href="{{route('editFood',$item->id)}}" class="btn btn-primary">تغییر</a>
                                 @if($item->valid == 1)
                                 <button id="btn{{$item->id}}" @click="valid({{$item->id}})" class="btn btn-warning" >موجود</button>
                                 @else
                                     <button id="btn{{$item->id}}" @click="valid({{$item->id}})" class="btn btn-warning" >ناموجود</button>
                                 @endif
                                 <form action="{{route('remove',$item->id)}}" method="post">
                                     <input type="hidden" name="_token" value="{{csrf_token()}}">
                                     <button id="{{$item->id}}"  type="submit" class="btn btn-danger">حذف</button>
                                 </form>

                             </div>
                         </div>
                     </div>
                 </div>
             @endforeach
         </div>
     @endfor

 </div>


 <!-- Menu category -->
 <div id="menuCategory" class="tabcontent"> 
    <h4>اضافه کردن دسته جدید</h4>

         {{--<div id="message"  class="alert alert-success" role="alert">--}}
             {{--<span>دسته جدید ایجاد شد</span>--}}
         {{--</div>--}}
     @if(session()->has('categoryError'))
         <div>
             <p class="alert alert-danger">{{session('categoryError')}}</p>
         </div>
     @endif

    <form action="{{route('addCategory')}}" method="post" class="flex-row flex-start"  style="margin-bottom: 2%;">
        <input type="hidden" name="_token" value="{{csrf_token()}}">
      <input v-model="categoryTag" id="category" class="form-control col-md-3 col-sm-8" type="text" name="category" placeholder="نام دسته">
      <input v-model="priority" id="priority" class="form-control col-md-3 col-sm-2" type="number" min="0" step="1" name="priority" placeholder="ترتیب نمایش">
      <button :disabled="!categoryTag || !priority" type="submit" class="btn btn-primary col-md-1 col-sm-1">ثبت</button>
    </form>
    <hr/>
    <h4>دسته های اضافه شده</h4>
     <form action="{{route('catPriority')}}" method="post">
         <input type="hidden" name="_token" value="{{csrf_token()}}">
        <ul class="row">
            @foreach($cats as $cat)
          <li class="col-lg-3 col-md-4 col-sm-6">
            <div class="flex-row space-around flex-center-align" style=" align-items: center;">
              {{--<input id="{{$cat->id}}" @click="removeCkBox({{$cat->id}})" :checked="checked"  type="checkbox" >--}}
                <my-checkbox :list="removeList" id="{{$cat->id}}" type="{{$cat->type}}" priority="{{$cat->priority}}"></my-checkbox>
                {{--<h5 v-show="!del"> {{$cat->type}} </h5>--}}
                <input id="{{$cat->type}}" class="form-control" type="text" name="{{$cat->type}}" value="{{$cat->priority}}" style="width: 100px;" />
                <input name="removeList" type="hidden" value="@{{ removeList }}">

            </div>
          </li>
            @endforeach
        </ul>
        <button  type="submit" class="btn btn-success" style="display: block;margin: auto;">ذخیره</button>
     </form>
 </div>
 <!-- Menu category -->
 <div id="menuFood" class="tabcontent">
    <h4>اضافه کردن غذا</h4>

     @if($errors->all())
         <div>
             @foreach($errors->all() as $error)
                <p class="alert alert-danger">{{$error}}</p>
             @endforeach

     </div>
     @endif
    <form action="{{route('addFood')}}" method="post" enctype="multipart/form-data" style="margin-bottom: 2%;">
        @if(session()->has('message'))
            <div>
                <p class="alert alert-danger">{{session('message')}}</p>
            </div>
        @endif

        <input type="hidden" name="_token" value="{{csrf_token()}}">
      <div class="form-group">
       <label>نام غذا</label>
       <input type="text" name="foodName" class="form-control" value="{{Request::old('foodName')}}">
      </div>
      <div class="form-group">
       <label>توضیح غذا</label>
       <input type="text" name="foodDes" class="form-control" value="{{Request::old('foodDes')}}">
      </div>
      <div class="form-group">
       <label>قیمت غذا (تومان)</label>
       <input type="number"  min="0" name="foodPrice" class="form-control" placeholder="10000" value="{{Request::old('foodPrice')}}}}">
      </div>
      <div class="form-group">
        <label for="sel1">انتخاب دسته غذا</label>
        <select  class="form-control" id="sel1" name="foodCategory">
         <option selected  disabled >دسته بندی</option>
         <option v-for="type in list" value="@{{ type }}">@{{ type }}</option>
        </select>
      </div>
      <div class="form-group">
       <label>عکس غذا</label>
       <input type="file" name="foodImage" v-model="image" class="form-control">
      </div>
      <button type="submit" :disabled="submitBtn" class="btn btn-primary col-md-1 col-sm-1">ثبت</button>
    </form>
 </div>
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

    /* Style the tab */
    .tab {
        overflow: hidden;
        border: 1px solid #ccc;
        background-color: #f1f1f1;
    }

    /* Style the buttons that are used to open the tab content */
    .tab button {
        background-color: inherit;
        float: right;
        border: none;
        outline: none;
        cursor: pointer;
        padding: 14px 16px;
        transition: 0.3s;
    }

    /* Change background color of buttons on hover */
    .tab button:hover {
        background-color: #ddd;
    }

    /* Create an active/current tablink class */
    .tab button.active {
        background-color: #ccc;
    }

    /* Style the tab content */
    .tabcontent {
        display: none;
        padding: 6px 12px;
        border: 1px solid #ccc;
        border-top: none;
    }
    .tabcontent {
        animation: fadeEffect 1s; /* Fading effect takes 1 second */
    }

    /* Go from zero to full opacity */
    @keyframes fadeEffect {
        from {opacity: 0;}
        to {opacity: 1;}
    }

    #menuCategory li {
        list-style: none;
        margin: 1%;
    }
    #menuCategory li h5{
        margin-right: 2%;margin-left: 2%;
        display: inline-block;
        vertical-align: middle;
    }
</style>

<template id="my-checkbox">
    <input type="checkbox" @click="removeCkBox(id,list)">
    <h5 v-show="!deleteTag" >@{{ type }}</h5>
    <h5 style="text-decoration: line-through; color:red" v-show="deleteTag"> @{{type}}</h5>

</template>


<script>
    function myFunction() {
        var x = document.getElementById("myTopnav");
        if (x.className === "topnav") {
            x.className += " responsive";
        } else {
            x.className = "topnav";
        }
    }


    function openCity(evt, cityName) {
        // Declare all variables
        var i, tabcontent, tablinks;

        // Get all elements with class="tabcontent" and hide them
        tabcontent = document.getElementsByClassName("tabcontent");
        for (i = 0; i < tabcontent.length; i++) {
            tabcontent[i].style.display = "none";
        }

        // Get all elements with class="tablinks" and remove the class "active"
        tablinks = document.getElementsByClassName("tablinks");
        for (i = 0; i < tablinks.length; i++) {
            tablinks[i].className = tablinks[i].className.replace(" active", "");
        }

        // Show the current tab, and add an "active" class to the button that opened the tab
        document.getElementById(cityName).style.display = "block";
        evt.currentTarget.className += " active";
    }

    // Get the element with id="defaultOpen" and click on it
    document.getElementById("defaultOpen").click();

    function sendData() {
        var category = document.getElementById('category').value;
        var priority = document.getElementById('priority').value;

        axios.post('{{route('addCategory')}}',{'category':category,'priority':priority}).then(function (response) {

            if(response.data == 200){
                document.getElementById('message').hidden=false
            }
        })

    }
    function removeCat(id) {
        axios.post('{{route('removeCategory')}}',{'id':id}).then(function (response) {

        })
    }
    Vue.component('my-checkbox',{

        template:'#my-checkbox',
        props:['id','list','type','priority'],
        methods:{
            removeCkBox:function (id,list) {

                this.stat = !this.stat;
                if(this.stat == true){
                    this.deleteTag = !this.deleteTag;
                    list.unshift(id);

                }else{
                    list.shift(id);
                    this.deleteTag = !this.deleteTag;
                }
//                console.log(list)
            }
        },
        data:function () {
            return {
                stat:false,
                deleteTag:false

            }
        }
    });
    new Vue({
        el:'#app',
        data:{
            list:[''],
            hidden:true,
            removeList:[],
            categoryTag:'',
            priority:'',
            disableButton:true,
            image:'',
            submitBtn:true

        },
        watch:{

            image:function () {
               if(this.image.length>0){
                   this.submitBtn = false
               }
            }
        },
        methods:{
            valid:function (id) {
                 vm = this;
                axios.get('{{route('valid')}}'+'?id='+id).then(function (response) {

                    var stat = document.getElementById('btn'+id).innerHTML;
                   if(stat === 'موجود'){
                       document.getElementById('btn'+id).innerHTML = 'ناموجود'

                   }else if(stat === 'ناموجود'){
                       document.getElementById('btn'+id).innerHTML = 'موجود'
                   }
                })
            },

        },
        created:function () {

            vm = this;
            axios.get('{{route('getCats')}}').then(function (response) {
                vm.list = response.data

            })
        }
    });

</script>

@endsection

