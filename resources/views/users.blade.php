@extends('master.layout')
@section('content')
<div class="container" style="margin-top: 2%;width: 90%;max-width: 2000px;" id="app" xmlns="http://www.w3.org/1999/html">
  <table class="table table-striped">
    <thead>
      <tr>
        <th>نام</th>
        <th>کد اشتراک</th>
        <th>ادرس</th>
        <th>شماره تلفن</th>
        <th>تغییر</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>امیری</td>
        <td>1234</td>
        <td>باغ فیض، خیابان 22 بهمن کوچه 15 غربی ، پلاک 17 ، واحد 4</td>
        <td>4412345678</td>
        <td><button class="btn btn-edit">تغییر</button></td>
      </tr>
      <tr>
        <td>امیری</td>
        <td>1234</td>
        <td>باغ فیض، خیابان 22 بهمن کوچه 15 غربی ، پلاک 17 ، واحد 4</td>
        <td>4412345678</td>
        <td><button class="btn btn-edit">تغییر</button></td>
      </tr>
      <tr>
        <td>امیری</td>
        <td>1234</td>
        <td>باغ فیض، خیابان 22 بهمن کوچه 15 غربی ، پلاک 17 ، واحد 4</td>
        <td>4412345678</td>
        <td><button class="btn btn-edit">تغییر</button></td>
      </tr>
    </tbody>
   </table>
  </div>
  <!-- The Modal -->
<div id="myModal" class="modal" style="color: black;">
  <!-- Modal content -->
  <div class="modal-content">
    <span class="close">&times;</span>
    <div>
        <h2 class="text-center">تغییر اطلاعات اشتراک کاربر</h2>
        <div class="form">
         <form method="post" action="" class="register-form">
           <!-- <input type="hidden" name="_token" value="{{csrf_token()}}"> -->
           <input type="text"  hidden name="userCode" id="userCode"/>
           <label>نام</label>
           <input type="text" name="name" placeholder="نام" id="formName"/>
           <label>آدرس</label>
           <input type="text" name="address" placeholder="آدرس" id="formAddress"/>
           <label>کد اشتراک</label>
           <input type="text" name="newUserCode" placeholder="کد اشتراک" id="formUserCode"/>
           <label>شماره تلفن</label>
           <input type="text" name='phoneNumber' placeholder="شماره تلفن" id="formPhoneNumber"/>
           <button style="font-size: 150%;font-weight: 400;" type="submit" id="registerButton">تغییر</button>
           <button style="margin-top: 2%;font-size: 150%;font-weight: 400;background-color: red;" type="submit">حذف</button>
        </form>
        </div>
    </div>
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

        /* The container */
        .container-checkBox {
            display: block;
            position: relative;
            padding-left: 35px;
            margin-bottom: 12px;
            cursor: pointer;
            font-size: 22px;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
            width: 300px;
        }

        /* Hide the browser's default checkbox */
        .container-checkBox input {
            position: absolute;
            opacity: 0;
            cursor: pointer;
        }

        /* Create a custom checkbox */
        .checkmark {
            position: absolute;
            top: 7px;
            left: 0;
            height: 25px;
            width: 25px;
            background-color: #eee;
        }

        /* On mouse-over, add a grey background color */
        .container-checkBox:hover input ~ .checkmark {
            background-color: #ccc;
        }

        /* When the checkbox is checked, add a blue background */
        .container-checkBox input:checked ~ .checkmark {
            background-color: #2196F3;
        }

        /* Create the checkmark/indicator (hidden when not checked) */
        .checkmark:after {
            content: "";
            position: absolute;
            display: none;
        }

        /* Show the checkmark when checked */
        .container-checkBox input:checked ~ .checkmark:after {
            display: block;
        }

        /* Style the checkmark/indicator */
        .container-checkBox .checkmark:after {
            left: 9px;
            top: 5px;
            width: 5px;
            height: 10px;
            border: solid white;
            border-width: 0 3px 3px 0;
            -webkit-transform: rotate(45deg);
            -ms-transform: rotate(45deg);
            transform: rotate(45deg);
        }

        /* The Modal (background) */
.modal {
  display: none; /* Hidden by default */
  position: fixed; /* Stay in place */
  z-index: 1; /* Sit on top */
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
  margin: 10% auto; /* 15% from the top and centered */
  padding: 20px;
  border: 1px solid #888;
  width: 80%; /* Could be more or less, depending on screen size */
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
.form {
  /*position: relative;*/
  /*z-index: 1;*/
  /*background: #FFFFFF;*/
  max-width: 560px;
  margin: 0 auto 100px;
  padding: 45px;
  text-align: center;
  /*box-shadow: 0 0 20px 0 rgba(0, 0, 0, 0.2), 0 5px 5px 0 rgba(0, 0, 0, 0.24);*/
}
 .form label {
 	font-size: 150%;
 	font-weight: 400;
  }
.form input {
  font-family: "Roboto", sans-serif;
  outline: 0;
  background: #f2f2f2;
  width: 100%;
  border: 0;
  margin: 0 0 15px;
  padding: 15px;
  box-sizing: border-box;
  font-size: 14px;
}
.form button {
  font-family: "Roboto", sans-serif;
  text-transform: uppercase;
  outline: 0;
  background: #4CAF50;
  width: 100%;
  border: 0;
  padding: 15px;
  color: #FFFFFF;
  font-size: 14px;
  -webkit-transition: all 0.3 ease;
  transition: all 0.3 ease;
  cursor: pointer;
}
.form button:hover,.form button:active,.form button:focus {
  background: #43A047;
}

    </style>

<script type="text/javascript">
    	// Get the modal
var modal = document.getElementById('myModal');

// Get the button that opens the modal
var btn = document.getElementById("myBtn");

// Get the <span> element that closes the modal
var span = document.getElementsByClassName("close")[0];

// When the user clicks on the button, open the modal
$(document).on('click', '.btn-edit', function () {
  modal.style.display = "block";
  $("#userCode").val($(this).parent().parent().children().eq(1).text());
  $("#formName").val($(this).parent().parent().children().eq(0).text());
  $("#formUserCode").val($(this).parent().parent().children().eq(1).text());
  $("#formAddress").val($(this).parent().parent().children().eq(2).text());
  $("#formPhoneNumber").val($(this).parent().parent().children().eq(3).text());
})


// When the user clicks on <span> (x), close the modal
span.onclick = function() {
  modal.style.display = "none";
}

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
  if (event.target == modal) {
    modal.style.display = "none";
  }
}
</script>
@endsection