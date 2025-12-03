<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="theme-color" content="#0b2a5a" />
  <link rel="manifest" href="{{ asset('manifest.webmanifest') }}">
  <title>Ambulance Tracker - Login</title>

  <!-- Google Font: Poppins -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet" />

  <style>
    :root {
      --primary-color: #0ef;
      --bg-gradient: linear-gradient(to bottom right, #132743, #1e3c72);
      --glass-bg: rgba(255, 255, 255, 0.08);
      --input-bg: rgba(255, 255, 255, 0.12);
    }

    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    body {
      font-family: 'Poppins', sans-serif;
      background: var(--bg-gradient);
      color: white;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      align-items: center;
      padding: 20px 15px;
    }

    /* Top Section */
    .header {
      text-align: center;
      margin-top: 30px;
      margin-bottom: 20px;
    }

    .header img {
      width: 80px;
      margin-bottom: 10px;
    }

    .header h1 {
      font-size: 1.4rem;
      font-weight: 600;
      color: var(--primary-color);
    }

    .header p {
      font-size: 14px;
      color: #dbefff;
    }

    /* Glassy Login Container */
    .login-card {
      background: var(--glass-bg);
      border: 1px solid rgba(255, 255, 255, 0.15);
      border-radius: 25px 25px 0 0;
      padding: 25px 20px;
      width: 100%;
      max-width: 400px;
      backdrop-filter: blur(12px);
      box-shadow: 0 0 20px rgba(0, 255, 255, 0.1);
    }

    .login-card h2 {
      font-size: 22px;
      margin-bottom: 20px;
      font-weight: 600;
      text-align: center;
      color: white;
    }

    .form-group {
      margin-bottom: 15px;
      text-align: left;
    }

    .form-group label {
      font-size: 14px;
      margin-bottom: 5px;
      display: block;
      color: #c4e1f5;
    }

    .form-group input {
      width: 100%;
      padding: 12px;
      border: none;
      border-radius: 10px;
      background: var(--input-bg);
      color: white;
      font-size: 14px;
      outline: none;
    }

    .form-group input::placeholder {
      color: #a5dfff;
    }

    .btn-submit {
      margin-top: 10px;
      width: 100%;
      padding: 12px;
      background: linear-gradient(135deg, #0ef, #00f2ff);
      border: none;
      border-radius: 10px;
      color: #000;
      font-weight: 600;
      cursor: pointer;
      transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .btn-submit:hover {
      transform: scale(1.02);
      box-shadow: 0 0 10px #00f2ff;
    }

    .error-message {
      margin-top: 12px;
      font-size: 14px;
      color: #ff6b6b;
      text-align: center;
    }

    .signin-link {
      margin-top: 12px;
      text-align: center;
      font-size: 13px;
      color: #c8eaff;
    }

    .signin-link a {
      color: var(--primary-color);
      text-decoration: none;
      font-weight: 500;
    }
  </style>
</head>
<body>

  <!-- Header Section -->
  <div class="header">
    <img src="{{ asset('image/mdrrmologo.jpg') }}" alt="MDRRMO Logo" style="width: 80px; height: 80px; object-fit: cover; border-radius: 50%;" />
    <h1>Ambulance Tracker</h1>
    <p>The Silang DRRMO Ambulance Tracking System</p>
  </div>

  <!-- Login Glassy Card -->
  <div class="login-card">
    <h2>Login</h2>
    <form method="POST" action="{{ route('driver.login') }}">
      @csrf
      <div class="form-group">
        <label for="email">Email</label>
        <input id="email" type="email" name="email" placeholder="you@example.com" required />
      </div>
      <div class="form-group">
        <label for="password">Password</label>
        <input id="password" type="password" name="password" placeholder="••••••••" required />
      </div>
      <button type="submit" class="btn-submit">Login</button>
    </form>

    @if ($errors->any())
      <div class="error-message">{{ $errors->first() }}</div>
    @endif
  </div>

</body>
</html>