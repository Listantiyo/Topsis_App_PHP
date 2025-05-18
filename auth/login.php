<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Simple Login Form</title>
  <!-- Bootstrap CSS CDN -->
  <link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
    rel="stylesheet"
    integrity="sha384-ENjdO4Dr2bkBIFxQpeoCpVLyZ9M+NQZ+6NCeGlmCJoAiRbDpQPLzFro9+W4CcmhH"
    crossorigin="anonymous"
  />
  <style>
    body {
      background: #f8f9fa;
      display: flex;
      height: 100vh;
      align-items: center;
      justify-content: center;
    }
    .login-container {
      max-width: 360px;
      padding: 2rem;
      background: white;
      border-radius: 0.5rem;
      box-shadow: 0 0.25rem 0.75rem rgba(0,0,0,0.1);
    }
    .login-title {
      margin-bottom: 1.5rem;
      text-align: center;
      font-weight: 600;
      color: #343a40;
    }
  </style>
</head>
<body>
  <div class="login-container">
    <h2 class="login-title">Login</h2>
    <form action="auth.php" method="post">
      <div class="mb-3">
        <label for="username" class="form-label">Username</label>
        <input
          type="text"
          class="form-control"
          name="username"
          id="username"
          placeholder="Enter username"
          required
        />
      </div>
      <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <input
          type="password"
          class="form-control"
          name="password"
          id="password"
          placeholder="Enter password"
          required
        />
      </div>
      <button type="submit" class="btn btn-primary w-100">Login</button>
    </form>
  </div>

  <!-- Bootstrap JS Bundle with Popper -->
  <script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-qJYtPku0QonRtZYxLZhDry7V9VJwO+G74jeBDfQWFN1jDzXGACHXukzxN1dNQO7/"
    crossorigin="anonymous"
  ></script>
</body>
</html>

