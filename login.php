<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" type="text/css" href="./style/login.css">
    <link href="https://fonts.googleapis.com/css2?family=Jost:wght@500&display=swap" rel="stylesheet">
</head>
<body>
    <div class="main">  	
        <input type="checkbox" id="chk" aria-hidden="true">

        <div class="signup">
            <form action="./app/signup_procces.php" method="POST">
                <label for="chk" aria-hidden="true">Sign up</label>
                <input type="text" name="username" placeholder="User name" required>
                <input type="email" name="email" placeholder="Email" required pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$">
                <input type="password" name="password" placeholder="Password" required minlength="6">
                <button type="submit">Sign up</button>
            </form>
        </div>

        <div class="login">
            <form action="./app/login_procces.php" method="POST">
                <label for="chk" aria-hidden="true">Login</label>
                <input type="email" name="email" placeholder="Email" required pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$">
                <input type="password" name="password" placeholder="Password" required="">
                <button type="submit">Login</button>
            </form>
        </div>
    </div>
</body>
</html>