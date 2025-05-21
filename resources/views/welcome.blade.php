@include('layouts.head')
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Home</title>

    <style>
        body {
            height: 100vh;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #E2ECF2;
        }
        .main-container {
            background-color: #e3edf7;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }
        .login-container {
            background: white;
            border-radius: 10px;
            display: flex;
            flex-wrap: wrap;
            overflow: hidden;
            max-width: 900px;
            width: 100%;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        }
        .left-panel {
            background: linear-gradient(to bottom, #033649, #005f73);
            color: white;
            padding: 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            flex: 1;
        }
        .left-panel img {
            max-width: 180px;
            margin-top: 20px;
            opacity: 0.8;
            max-width: 60%;
            border-radius: 10px;
            mix-blend-mode: color-burn;
        }
        .right-panel {
            padding: 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            flex: 1;
            width: 50%;
        }
        .form-check {
            margin-bottom: 15px;
        }
        .form-check-input {
            margin-right: 10px;
        }
        @media (max-width: 768px) {
            .login-container {
                flex-direction: column;
            }
            .left-panel {
                padding: 5px;
            }
            .right-panel {
                padding: 2px;
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="main-container">
        <div class="login-container">
            <div class="col-md-6 left-panel">
                <h2 class="title">Foundation University<br>School of Science and Technology</h2>
                <img src="{{ asset('img/FUSSTLogo.jpg') }}" alt="University Logo">
            </div>
            <div class="col-md-6 right-panel">
                <h2 class="mb-4 text-center">Login</h2>
                <form>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="user_type" id="admin" onclick="redirectTo('/admin/login')">
                        <label class="form-check-label" for="admin">Admin</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="user_type" id="faculty" onclick="redirectTo('/faculty/login')">
                        <label class="form-check-label" for="faculty">Faculty</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="user_type" id="student" onclick="redirectTo('/login')">
                        <label class="form-check-label" for="student">Student</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="user_type" id="qec" onclick="redirectTo('/qec/login')">
                        <label class="form-check-label" for="qec">QEC</label>
                    </div>
                </form>
                <script>
                    function redirectTo(url) {
                        window.location.href = url;
                    }
                </script>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>