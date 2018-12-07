<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>جدول فروش </title>
    <script type="text/javascript" src="{{URL::asset('js/jquery.js')}}"></script>
    {{--<link type="text/css" rel="stylesheet" href="{{URL::asset('css/bootstrap.min.css')}}">--}}
    <script src="{{URL::asset('js/ajax_vue.js')}}"></script>
    <script src="{{URL::asset('js/vue_resource.js')}}"></script>
    <script src="{{URL::asset('js/lodash.js')}}"></script>
    <script src="{{URL::asset('js/axios.js')}}"></script>



    <link rel="stylesheet" href="{{URL::asset('css/chart.css')}}">
</head>
<body>
<div style="width: 300px;background: #2990ff"></div>
<div class="app">
    <line-chart></line-chart>
</div>
{{--<script src='https://unpkg.com/vue'></script>--}}
{{--<script src='https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.min.js'></script>--}}
{{--<script src='https://unpkg.com/vue-chartjs@3.0.1-rc2/dist/vue-chartjs.js'></script>--}}

<script src="{{URL::asset('js/vue2.5.js')}}"></script>
<script src="{{URL::asset('js/chart.js')}}"></script>
<script src="{{URL::asset('js/vue-chartjs.js')}}"></script>


<script>

    Vue.component('line-chart', {
        extends: VueChartJs.Bar,
        mounted:function () {
            this.renderChart({
                labels: {!! json_encode(Cache::get('chartTime')) !!},
                datasets: [
                    {
                        label: {!! json_encode(Cache::get('chartName')) !!},
                        backgroundColor: '#2990ff',
                        data: {!! json_encode(Cache::get('chartValue')) !!}
                    }
                ]
            }, {responsive: true, maintainAspectRatio: false})

        }
    });

    var vm = new Vue({
        el: '.app',
        data: {
            message: 'Hello World'
        }
    })

</script>

</body>
</html>