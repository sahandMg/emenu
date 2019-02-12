<!DOCTYPE html>
<html>
<head>
    <title>Emenu</title>
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
<nav class="topnav flex-row space-around" id="myTopnav">
    @if(request()->route()->getName() == 'orders')
            @if(Auth::guard('cashier')->check())
                <a  href="{{route('menu')}}" >مدیریت منو</a>
                <a class="activeNav" href="{{route('orders')}}">سفارش ها</a>
                <a   href="{{route('indexCashier')}}">منو صندوقدار </a>
                <a href="{{route('users')}}">کاربران</a>
                <a  href="{{route('main')}}">منو غذا </a>
            {{--<a href="{{route('message')}}">پیام ها</a>--}}
            @else
                <a  href="{{route('menu')}}" >مدیریت منو</a>
                <a class="activeNav" href="{{route('orders')}}">سفارش ها</a>
                <a  href="{{route('report')}}">گزارش فروش</a>
{{--                <a  href="{{route('resources')}}">مواد اولیه</a>--}}
            <a   href="{{route('indexCashier')}}">منو صندوقدار </a>
            <a href="{{route('users')}}">کاربران</a>
                <a  href="{{route('main')}}">منو غذا </a>
                <a  href="{{route('settings')}}">تنظیمات</a>
               {{--<a href="{{route('message')}}">پیام ها</a>--}}
            @endif
        @elseif(request()->route()->getName() == 'report')
                @if(Auth::guard('cashier')->check())
        <a  href="{{route('menu')}}" >مدیریت منو</a>
        <a  href="{{route('orders')}}">سفارش ها</a>
            <a   href="{{route('indexCashier')}}">منو صندوقدار </a>
            <a href="{{route('users')}}">کاربران</a>
        <a  href="{{route('main')}}">منو غذا </a>
            {{--<a href="{{route('message')}}">پیام ها</a>--}}
                @else
                <a  href="{{route('menu')}}" >مدیریت منو</a>
                <a  href="{{route('orders')}}">سفارش ها</a>
                <a class="activeNav" href="{{route('report')}}">گزارش فروش</a>
                {{--<a  href="{{route('resources')}}">مواد اولیه</a>--}}
            <a   href="{{route('indexCashier')}}">منو صندوقدار </a>
            <a href="{{route('users')}}">کاربران</a>
                <a  href="{{route('main')}}">منو غذا </a>
                <a  href="{{route('settings')}}">تنظیمات</a>
            {{--<a href="{{route('message')}}">پیام ها</a>--}}
                @endif


        @elseif(request()->route()->getName() == 'settings')
        <a  href="{{route('menu')}}" >مدیریت منو</a>
        <a  href="{{route('orders')}}">سفارش ها</a>
        <a  href="{{route('report')}}">گزارش فروش</a>
        {{--<a  href="{{route('resources')}}">مواد اولیه</a>--}}
        <a   href="{{route('indexCashier')}}">منو صندوقدار </a>
        <a href="{{route('users')}}">کاربران</a>
        <a  href="{{route('main')}}">منو غذا </a>
        <a class="activeNav" href="{{route('settings')}}">تنظیمات</a>
        {{--<a href="{{route('message')}}">پیام ها</a>--}}


    @elseif(request()->route()->getName() == 'menu')
                @if(Auth::guard('cashier')->check())
                <a class="activeNav" href="{{route('menu')}}" >مدیریت منو</a>
                <a  href="{{route('orders')}}">سفارش ها</a>
            <a   href="{{route('indexCashier')}}">منو صندوقدار </a>
            <a href="{{route('users')}}">کاربران</a>
                <a  href="{{route('main')}}">منو غذا </a>
            {{--<a href="{{route('message')}}">پیام ها</a>--}}
        @else
                <a class="activeNav" href="{{route('menu')}}" >مدیریت منو</a>
                <a  href="{{route('orders')}}">سفارش ها</a>
                <a  href="{{route('report')}}">گزارش فروش</a>
                {{--<a  href="{{route('resources')}}">مواد اولیه</a>--}}
            <a   href="{{route('indexCashier')}}">منو صندوقدار </a>
            <a href="{{route('users')}}">کاربران</a>
                <a  href="{{route('main')}}">منو غذا </a>
                <a  href="{{route('settings')}}">تنظیمات</a>
            {{--<a href="{{route('message')}}">پیام ها</a>--}}
        @endif

        @elseif(request()->route()->getName() == 'editFood')
        <a class="activeNav" href="{{route('menu')}}" >مدیریت منو</a>
        <a  href="{{route('orders')}}">سفارش ها</a>
        <a  href="{{route('report')}}">گزارش فروش</a>
        {{--<a  href="{{route('resources')}}">مواد اولیه</a>--}}
        <a   href="{{route('indexCashier')}}">منو صندوقدار </a>
        <a href="{{route('users')}}">کاربران</a>
        <a  href="{{route('main')}}">منو غذا </a>
        <a  href="{{route('settings')}}">تنظیمات</a>
        {{--<a href="{{route('message')}}">پیام ها</a>--}}


    @elseif(request()->route()->getName() == 'ytd')
        @if(Auth::guard('cashier')->check())
        <a  href="{{route('menu')}}" >مدیریت منو</a>
        <a class="activeNav" href="{{route('orders')}}">سفارش ها</a>
            <a   href="{{route('indexCashier')}}">منو صندوقدار </a>
            <a href="{{route('users')}}">کاربران</a>
            <a  href="{{route('main')}}">منو غذا </a>
            <a href="{{route('message')}}">پیام ها</a>
        @else
            <a  href="{{route('menu')}}" >مدیریت منو</a>
            <a class="activeNav" href="{{route('orders')}}">سفارش ها</a>
        <a  href="{{route('report')}}">گزارش فروش</a>
        {{--<a  href="{{route('resources')}}">مواد اولیه</a>--}}
            <a   href="{{route('indexCashier')}}">منو صندوقدار </a>
            <a href="{{route('users')}}">کاربران</a>
        <a  href="{{route('main')}}">منو غذا </a>
        <a  href="{{route('settings')}}">تنظیمات</a>
            {{--<a href="{{route('message')}}">پیام ها</a>--}}
        @endif

    @elseif(request()->route()->getName() == 'resources')
        <a  href="{{route('menu')}}" >مدیریت منو</a>
        <a  href="{{route('orders')}}">سفارش ها</a>
        <a  href="{{route('report')}}">گزارش فروش</a>
        {{--<a class="activeNav" href="{{route('resources')}}">مواد اولیه</a>--}}
        <a   href="{{route('indexCashier')}}">منو صندوقدار </a>
        <a href="{{route('users')}}">کاربران</a>
        <a  href="{{route('main')}}">منو غذا </a>
        <a  href="{{route('settings')}}">تنظیمات</a>
        {{--<a href="{{route('message')}}">پیام ها</a>--}}

    @elseif(request()->route()->getName() == 'main')
        <a  href="{{route('menu')}}" >مدیریت منو</a>
        <a  href="{{route('orders')}}">سفارش ها</a>
        <a  href="{{route('report')}}">گزارش فروش</a>
        {{--<a  href="{{route('resources')}}">مواد اولیه</a>--}}
        <a   href="{{route('indexCashier')}}">منو صندوقدار </a>
        <a href="{{route('users')}}">کاربران</a>
        <a class="activeNav"  href="{{route('main')}}">منو غذا </a>
        <a  href="{{route('settings')}}">تنظیمات</a>
        {{--<a href="{{route('message')}}">پیام ها</a>--}}
    @elseif(request()->route()->getName() == 'chartSetting')
        <a  href="{{route('menu')}}" >مدیریت منو</a>
        <a  href="{{route('orders')}}">سفارش ها</a>
        <a class="activeNav"  href="{{route('report')}}">گزارش فروش</a>
        {{--<a  href="{{route('resources')}}">مواد اولیه</a>--}}
        <a   href="{{route('indexCashier')}}">منو صندوقدار </a>
        <a href="{{route('users')}}">کاربران</a>
        <a   href="{{route('main')}}">منو غذا </a>
        <a  href="{{route('settings')}}">تنظیمات</a>
        {{--<a href="{{route('message')}}">پیام ها</a>--}}

    @elseif(request()->route()->getName() == 'message')
        <a  href="{{route('menu')}}" >مدیریت منو</a>
        <a  href="{{route('orders')}}">سفارش ها</a>
        <a  href="{{route('report')}}">گزارش فروش</a>
        {{--<a  href="{{route('resources')}}">مواد اولیه</a>--}}
        <a   href="{{route('indexCashier')}}">منو صندوقدار </a>
        <a href="{{route('users')}}">کاربران</a>
        <a   href="{{route('main')}}">منو غذا </a>
        <a  href="{{route('settings')}}">تنظیمات</a>
        {{--<a class="activeNav"  href="{{route('message')}}">پیام ها</a>--}}

    @elseif(request()->route()->getName() == 'indexCashier')
        @if(Auth::guard('cashier')->check())
            <a  href="{{route('menu')}}" >مدیریت منو</a>
            <a  href="{{route('orders')}}">سفارش ها</a>
            {{--<a class="activeNav" href="{{route('resources')}}">مواد اولیه</a>--}}
            <a class="activeNav"   href="{{route('indexCashier')}}">منو صندوقدار </a>
            <a href="{{route('users')}}">کاربران</a>
            <a  href="{{route('main')}}">منو غذا </a>
        {{--<a href="{{route('message')}}">پیام ها</a>--}}
        @else
            <a  href="{{route('menu')}}" >مدیریت منو</a>
            <a  href="{{route('orders')}}">سفارش ها</a>
            <a  href="{{route('report')}}">گزارش فروش</a>
            {{--<a class="activeNav" href="{{route('resources')}}">مواد اولیه</a>--}}
            <a class="activeNav"   href="{{route('indexCashier')}}">منو صندوقدار </a>
            <a href="{{route('users')}}">کاربران</a>
            <a  href="{{route('main')}}">منو غذا </a>
            <a  href="{{route('settings')}}">تنظیمات</a>
            @endif
    @elseif(request()->route()->getName() == 'users')
            <a  href="{{route('menu')}}" >مدیریت منو</a>
            <a  href="{{route('orders')}}">سفارش ها</a>
            <a  href="{{route('report')}}">گزارش فروش</a>
            <a href="{{route('indexCashier')}}">منو صندوقدار </a>
            <a class="activeNav" href="{{route('users')}}">کاربران</a>
            <a  href="{{route('main')}}">منو غذا </a>
            <a  href="{{route('settings')}}">تنظیمات</a>
    @endif



    <a href="javascript:void(0);" class="icon" onclick="myFunction()">
        <i class="fa fa-bars"></i>
    </a>
</nav>

@yield('content')

</body>
</html>
