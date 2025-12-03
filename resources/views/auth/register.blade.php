<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Login View CSS -->
    <link rel="stylesheet" href="{{ asset('css/login-view.css') }}">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

    <style>
        /* Login View Styles */
body {
  margin: 0;
  padding: 0;
  font-family: 'Roboto', Arial, sans-serif;
  background: linear-gradient(135deg, #f5f5f5 0%, #e8f4f8 100%);
  overflow: hidden;
  height: 100vh;
  display: flex;
  justify-content: center;
  align-items: center;
  padding: 40px 20px;
}

.login-container {
  display: flex;
  width: 100%;
  max-width: 900px;
  min-height: 500px;
  margin: 0 auto;
  background: #ffffff;
  border-radius: 16px;
  box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
  overflow: hidden;
}

.login-left {
  flex: 1;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  padding: 40px 35px;
  background: #ffffff;
}

.login-divider {
  width: 1px;
  flex-shrink: 0;
  background: transparent;
  box-shadow: 
    -2px 0 4px rgba(242, 140, 40, 0.2),
    2px 0 4px rgba(3, 18, 115, 0.2),
    -1px 0 2px rgba(242, 140, 40, 0.1),
    1px 0 2px rgba(3, 18, 115, 0.1);
}

.login-right {
  flex: 1;
  display: flex;
  justify-content: center;
  align-items: center;
  padding: 40px 35px;
  background: linear-gradient(135deg, #031273 0%, #1e40af 100%);
}

.login-form-wrapper {
  width: 100%;
  max-width: 400px;
}

.login-header {
  margin-bottom: 24px;
  text-align: center;
}

.register-container .login-header {
  margin-bottom: 18px;
}

.login-header h2 {
  font-size: 28px;
  font-weight: 800;
  color: #f28c28;
  margin: 0;
  letter-spacing: 1px;
}

.login-form {
  width: 100%;
}

.form-group {
  margin-bottom: 18px;
}

.form-group label {
  display: flex;
  align-items: center;
  gap: 8px;
  font-size: 14px;
  font-weight: 600;
  color: #374151;
  margin-bottom: 8px;
}

.form-group label i {
  color: #f28c28;
  font-size: 16px;
}

.form-group input[type="text"],
.form-group input[type="email"],
.form-group input[type="password"] {
  width: 100%;
  padding: 14px 16px;
  font-size: 16px;
  border: 2px solid #e5e7eb;
  border-radius: 8px;
  transition: all 0.3s ease;
  background: #ffffff;
  box-sizing: border-box;
}

.form-group input[type="text"]:focus,
.form-group input[type="email"]:focus,
.form-group input[type="password"]:focus {
  outline: none;
  border-color: #f28c28;
  box-shadow: 0 0 0 3px rgba(242, 140, 40, 0.1);
  background: #ffffff;
}

.password-options {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-top: 8px;
}

.forgot-password-link {
  display: flex;
  justify-content: flex-end;
}

.forgot-password-link a {
  display: flex;
  align-items: center;
  gap: 6px;
  font-size: 13px;
  color: #f28c28;
  text-decoration: none;
  transition: color 0.2s ease;
}

.forgot-password-link a:hover {
  color: #ff8c42;
  text-decoration: underline;
}

.forgot-password-link a i {
  font-size: 12px;
}

.remember-me {
  display: flex;
  align-items: center;
  gap: 8px;
}

.remember-me input[type="checkbox"] {
  width: 18px;
  height: 18px;
  cursor: pointer;
  accent-color: #f28c28;
}

.remember-me label {
  font-size: 14px;
  color: #6b7280;
  cursor: pointer;
  margin: 0;
}

.signup-link {
  display: flex;
  justify-content: center;
  margin-bottom: 18px;
}

.signup-link a {
  display: flex;
  align-items: center;
  gap: 8px;
  font-size: 14px;
  color: #f28c28;
  text-decoration: none;
  transition: color 0.2s ease;
}

.signup-link a:hover {
  color: #ff8c42;
  text-decoration: underline;
}

.signup-link a i {
  font-size: 14px;
}

.login-button {
  width: 100%;
  padding: 14px 24px;
  font-size: 16px;
  font-weight: 700;
  color: #ffffff;
  background: linear-gradient(135deg, #031273 0%, #1e40af 100%);
  border: none;
  border-radius: 8px;
  cursor: pointer;
  transition: all 0.3s ease;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  box-shadow: 0 4px 12px rgba(3, 18, 115, 0.3);
}

.login-button:hover {
  background: linear-gradient(135deg, #1e40af 0%, #031273 100%);
  transform: translateY(-2px);
  box-shadow: 0 6px 16px rgba(3, 18, 115, 0.4);
}

.login-button:active {
  transform: translateY(0);
}

.logo-container {
  display: flex;
  justify-content: center;
  align-items: center;
  width: 100%;
  height: 100%;
}

.logo-container img {
  width: 280px;
  height: 280px;
  object-fit: cover;
  border-radius: 50%;
  box-shadow: 0 8px 24px rgba(0, 0, 0, 0.3);
  border: 4px solid #ffffff;
}

/* Error and Status Messages */
.error-messages {
  margin-bottom: 18px;
  padding: 12px;
  background: #fef2f2;
  border: 1px solid #fecaca;
  border-radius: 8px;
}

.error-messages .error-title {
  font-weight: 700;
  color: #dc2626;
  margin-bottom: 8px;
  font-size: 13px;
}

.error-messages ul {
  margin: 0;
  padding-left: 20px;
  color: #dc2626;
  font-size: 13px;
}

.success-message {
  margin-bottom: 18px;
  padding: 12px;
  background: #f0fdf4;
  border: 1px solid #86efac;
  border-radius: 8px;
  color: #16a34a;
  font-size: 13px;
  font-weight: 600;
}

/* Register Page - Single Container */
.register-container {
  display: flex;
  justify-content: center;
  align-items: center;
  width: 100%;
  max-width: 480px;
  min-height: auto;
  margin: 0 auto;
  background: #ffffff;
  border-radius: 16px;
  box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
  padding: 20px 28px;
}

.register-form-wrapper {
  width: 100%;
  max-width: 100%;
}

.register-logo {
  display: flex;
  justify-content: center;
  align-items: center;
  margin-bottom: 8px;
}

.register-logo img {
  width: 75px;
  height: 75px;
  object-fit: cover;
  border-radius: 50%;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
  border: 2px solid #ffffff;
}

.register-container .login-header {
  margin-bottom: 12px;
}

.register-container .login-header h2 {
  font-size: 22px;
  margin: 0;
}

.register-container .form-group {
  margin-bottom: 12px;
}

.register-container .form-group label {
  margin-bottom: 6px;
  font-size: 13px;
}

.register-container .form-group input[type="text"],
.register-container .form-group input[type="email"],
.register-container .form-group input[type="password"] {
  padding: 12px 14px;
  font-size: 15px;
}

.register-container .signup-link {
  margin-bottom: 12px;
}

.register-container .signup-link a {
  font-size: 13px;
}

.register-container .login-button {
  padding: 12px 24px;
  font-size: 15px;
}

.register-container .error-messages {
  margin-bottom: 12px;
  padding: 8px;
}

.register-container .error-messages .error-title {
  font-size: 11px;
  margin-bottom: 5px;
}

.register-container .error-messages ul {
  font-size: 11px;
}

.forgot-password-info {
  margin-bottom: 16px;
  padding: 12px;
  background: #f0f9ff;
  border: 1px solid #bae6fd;
  border-radius: 8px;
  color: #0369a1;
  font-size: 13px;
  line-height: 1.5;
  text-align: center;
}

.forgot-password-info p {
  margin: 0;
}

/* Responsive Design */
@media (max-width: 1024px) {
  body {
    padding: 20px;
    overflow: hidden;
    height: 100vh;
  }

  .login-container {
    flex-direction: column;
    max-width: 600px;
    max-height: 95vh;
    overflow-y: auto;
  }

  .login-left {
    flex: 1;
    padding: 32px 28px;
  }

  .login-divider {
    width: 100%;
    height: 1px;
    box-shadow: 
      0 -2px 4px rgba(242, 140, 40, 0.2),
      0 2px 4px rgba(3, 18, 115, 0.2),
      0 -1px 2px rgba(242, 140, 40, 0.1),
      0 1px 2px rgba(3, 18, 115, 0.1);
  }

  .login-right {
    flex: 0 0 auto;
    padding: 32px 28px;
    min-height: 250px;
  }

  .logo-container img {
    width: 180px;
    height: 180px;
  }

  .login-header h2 {
    font-size: 28px;
  }

  .register-container {
    max-width: 100%;
    padding: 18px 22px;
  }

  .register-logo {
    margin-bottom: 6px;
  }

  .register-logo img {
    width: 65px;
    height: 65px;
  }

  .register-container .login-header {
    margin-bottom: 10px;
  }

  .register-container .login-header h2 {
    font-size: 20px;
  }

  .register-container .form-group {
    margin-bottom: 10px;
  }

  .register-container .form-group label {
    margin-bottom: 5px;
    font-size: 12px;
  }

  .register-container .form-group input[type="text"],
  .register-container .form-group input[type="email"],
  .register-container .form-group input[type="password"] {
    padding: 11px 13px;
    font-size: 14px;
  }

  .register-container .signup-link {
    margin-bottom: 10px;
  }

  .register-container .signup-link a {
    font-size: 12px;
  }

  .register-container .login-button {
    padding: 11px 22px;
    font-size: 14px;
  }

  .forgot-password-info {
    margin-bottom: 12px;
    padding: 10px;
    font-size: 12px;
  }
}

@media (max-width: 640px) {
  body {
    padding: 15px;
    overflow: hidden;
    height: 100vh;
  }

  .login-container {
    max-width: 100%;
    max-height: 95vh;
    border-radius: 12px;
    overflow-y: auto;
  }

  .login-left {
    padding: 28px 20px;
  }

  .login-divider {
    height: 1px;
  }

  .login-right {
    padding: 28px 20px;
    min-height: 200px;
  }

  .logo-container img {
    width: 150px;
    height: 150px;
  }

  .login-header h2 {
    font-size: 24px;
  }

  .login-form-wrapper {
    max-width: 100%;
  }

  .register-container {
    max-width: 100%;
    padding: 16px 16px;
    border-radius: 12px;
    max-height: 95vh;
    overflow-y: auto;
  }

  .register-form-wrapper {
    max-width: 100%;
  }

  .register-logo {
    margin-bottom: 5px;
  }

  .register-logo img {
    width: 60px;
    height: 60px;
  }

  .register-container .login-header {
    margin-bottom: 8px;
  }

  .register-container .login-header h2 {
    font-size: 18px;
  }

  .register-container .form-group {
    margin-bottom: 10px;
  }

  .register-container .form-group label {
    margin-bottom: 5px;
    font-size: 12px;
  }

  .register-container .form-group input[type="text"],
  .register-container .form-group input[type="email"],
  .register-container .form-group input[type="password"] {
    padding: 10px 12px;
    font-size: 14px;
  }

  .register-container .signup-link {
    margin-bottom: 10px;
  }

  .register-container .signup-link a {
    font-size: 12px;
  }

  .register-container .login-button {
    padding: 11px 20px;
    font-size: 14px;
  }

  .register-container .error-messages {
    margin-bottom: 10px;
    padding: 6px;
  }

  .register-container .error-messages .error-title {
    font-size: 10px;
    margin-bottom: 4px;
  }

  .register-container .error-messages ul {
    font-size: 10px;
  }

  .forgot-password-info {
    margin-bottom: 10px;
    padding: 8px;
    font-size: 11px;
  }
}


    </style>
</head>
<body>
    <div class="register-container">
        <div class="register-form-wrapper">
            <!-- MDRRMO Logo -->
            <div class="register-logo">
                <img src="{{ asset('image/mdrrmologo.jpg') }}" alt="MDRRMO Logo">
            </div>
            
            <div class="login-header">
                <h2>Register</h2>
            </div>

            <!-- Validation Errors -->
            @if ($errors->any())
                <div class="error-messages">
                    <div class="error-title">Whoops! Something went wrong.</div>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}" class="login-form">
                @csrf

                <!-- Name -->
                <div class="form-group">
                    <label for="name">
                        <i class="fas fa-user"></i>Name
                    </label>
                    <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus 
                           placeholder="Enter your name">
                </div>

                <!-- Email Address -->
                <div class="form-group">
                    <label for="email">
                        <i class="fas fa-envelope"></i>Email
                    </label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required 
                           placeholder="Enter your email address">
                </div>

                <!-- Password -->
                <div class="form-group">
                    <label for="password">
                        <i class="fas fa-lock"></i>Password
                    </label>
                    <input id="password" type="password" name="password" required autocomplete="new-password" 
                           placeholder="Enter your password">
                </div>

                <!-- Confirm Password -->
                <div class="form-group">
                    <label for="password_confirmation">
                        <i class="fas fa-lock"></i>Confirm Password
                    </label>
                    <input id="password_confirmation" type="password" name="password_confirmation" required 
                           placeholder="Confirm your password">
                </div>

                <!-- Login Link -->
                <div class="signup-link">
                    <a href="{{ route('login') }}">
                        <i class="fas fa-sign-in-alt"></i>Already registered? Log in
                    </a>
                </div>

                <!-- Register Button -->
                <button type="submit" class="login-button">
                    <i class="fas fa-user-plus"></i> Register
                </button>
            </form>
        </div>
    </div>
</body>
</html>
