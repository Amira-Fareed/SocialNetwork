<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account</title>
    <link rel="shortcut icon" href="assets/img/icon.png" />
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/fonts/ionicons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.min.css">
    <link rel="stylesheet" href="assets/css/Login-Form-Clean.css">
    <link rel="stylesheet" href="assets/css/styles.css">
    <style type="text/css">
        span {color: red;display: none;}
    </style>
</head>

<body>
    <div class="login-clean">
        <form method="POST" id="registerForm">
            <div class="illustration"><i class="icon ion-ios-navigate"></i></div>
            <div class="form-group">
                <input class="form-control" id="name" type="text" name="name" 
                placeholder="Full Name" required>
                <span id="nameError"></span>
            </div>
            <div class="form-group">
                <input class="form-control" id="email" type="email" name="email" placeholder="Email" required>
                <span id="emailError"></span>
            </div>
            <div class="form-group">
                <input class="form-control" id="password" type="password" name="password" placeholder="Password" required>
                <span id="passwordError"></span>
            </div>
            <div class="form-group">
                <button class="btn btn-primary btn-block" id="ca" type="submit" data-bs-hover-animate="shake">Create Account</button>
            </div><br>
            <p class="forgot"> Already have an account ? <a href="login.php" 
                style="color: #f4476b">Login</p></a>
        </form>
    </div>
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <!-- Data Validation -->
    <script>
        $('#registerForm').on("submit", function(e) {
        e.preventDefault();
        var errors = false;

        /* Name Validation */
        var nameReg = /^[a-zA-Z ]/;
        if(!nameReg.test($("#name").val()))
        {
          $("#nameError").text("Invalid Name!");
          $("#nameError").show();
          errors = true;
        }

        else if($("#name").val().length < 5) 
        {
          $("#nameError").text("Name should be at least 5 Charachters");
          $("#nameError").show();
          errors = true;
        }

        else
          $("#nameError").hide();

        /*Email Validation*/
        emailReg = /^\w+@[a-zA-Z_]+?\.[a-zA-Z]{2,3}$/; 
        if(!emailReg.test($("#email").val()))
        {
            $("#emailError").text("Invalid Email!");
            $("#emailExistsError").show();
        }

        else
            $("#emailExistsError").hide();

        /* Password Validation */
        if($("#password").val().length < 5)
        {
            $("#passwordError").text("Password should be at least 5 Charachters");
            $("#passwordError").show();
            errors = true;
        }

        else
          $("#passwordError").hide();

        if(errors == false) 
        {
            $.post("functions/checkemail.php", { email: $("#email").val()}).done(function(data) 
            {
              var result = $.trim(data);
              if(result == "Error") 
              {
                $("#emailError").text("This email is already registered with us. Choose Different Email.");
                $("#emailError").show();
              } 
              else 
              {
                $("#emailError").hide();
                adduser();
              }
            } 
            )}    
      });
</script>
<script>
    function adduser() 
    {
      $.post("functions/adduser.php", $("#registerForm").serialize()).done(function(data) {
        var result = $.trim(data);
        if(result == "ok") 
        {
          confirm("Account Created Successfully!");
          window.location.href = "login.php";
        }
      });
  }
</script>
</body>
</html>
