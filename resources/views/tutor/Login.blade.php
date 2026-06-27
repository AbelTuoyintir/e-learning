<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Tutor Login · LearnSpace</title>
  <!-- Bootstrap 5 & Vite (placeholder) -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <!-- Font Awesome (optional, for clean icons) -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
      background: #f0f2f5;
    }

    /* main container — split layout */
    .tutor-login-wrapper {
      display: flex;
      min-height: 100vh;
      width: 100%;
    }

    /* left panel — image / brand */
    .tutor-image-panel {
      flex: 1 1 50%;
      background: linear-gradient(145deg, #1e2b6d 0%, #2a3f8a 50%, #1a2a5c 100%);
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 2.5rem;
      position: relative;
      overflow: hidden;
    }

    /* subtle animated circles (decoration) */
    .tutor-image-panel::before {
      content: '';
      position: absolute;
      top: -20%;
      right: -10%;
      width: 320px;
      height: 320px;
      background: rgba(255, 255, 255, 0.03);
      border-radius: 50%;
      pointer-events: none;
    }

    .tutor-image-panel::after {
      content: '';
      position: absolute;
      bottom: -15%;
      left: -10%;
      width: 280px;
      height: 280px;
      background: rgba(255, 255, 255, 0.02);
      border-radius: 50%;
      pointer-events: none;
    }

    .tutor-brand-content {
      position: relative;
      z-index: 2;
      max-width: 480px;
      text-align: center;
      color: white;
    }

    .tutor-brand-content img {
      width: 100%;
      max-height: 220px;
      object-fit: cover;
      border-radius: 20px;
      box-shadow: 0 20px 40px rgba(0, 0, 0, 0.35);
      margin-bottom: 1.8rem;
      transition: transform 0.25s ease;
      background: #2a3f8a; /* fallback color */
    }

    .tutor-brand-content img:hover {
      transform: scale(1.01);
    }

    .tutor-brand-content h2 {
      font-weight: 700;
      font-size: 2.2rem;
      letter-spacing: -0.02em;
      margin-bottom: 0.6rem;
      text-shadow: 0 4px 12px rgba(0,0,0,0.2);
    }

    .tutor-brand-content p {
      font-size: 1.05rem;
      opacity: 0.85;
      line-height: 1.6;
      max-width: 360px;
      margin: 0 auto;
      font-weight: 300;
    }

    .tutor-brand-content .tutor-feature-badge {
      display: inline-block;
      background: rgba(255, 255, 255, 0.12);
      backdrop-filter: blur(4px);
      padding: 0.4rem 1.2rem;
      border-radius: 40px;
      font-size: 0.85rem;
      font-weight: 500;
      letter-spacing: 0.3px;
      border: 1px solid rgba(255, 255, 255, 0.08);
      margin-top: 1.2rem;
    }

    /* right panel — login form */
    .tutor-form-panel {
      flex: 1 1 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 2rem 2.5rem;
      background: #ffffff;
      box-shadow: -4px 0 30px rgba(0, 0, 0, 0.03);
    }

    .tutor-login-card {
      width: 100%;
      max-width: 400px;
      margin: 0 auto;
    }

    .tutor-login-card .login-header {
      margin-bottom: 2.2rem;
    }

    .tutor-login-card .login-header h3 {
      font-weight: 700;
      font-size: 1.9rem;
      color: #1e2b6d;
      letter-spacing: -0.02em;
    }

    .tutor-login-card .login-header p {
      color: #6b7280;
      font-size: 0.95rem;
    }

    .form-label {
      font-weight: 500;
      font-size: 0.9rem;
      color: #1f2937;
      margin-bottom: 0.3rem;
    }

    .form-control {
      border-radius: 12px;
      border: 1px solid #e5e7eb;
      padding: 0.7rem 1rem;
      font-size: 0.95rem;
      background: #fafbfc;
      transition: all 0.2s;
    }

    .form-control:focus {
      border-color: #2a3f8a;
      box-shadow: 0 0 0 4px rgba(42, 63, 138, 0.12);
      background: white;
    }

    .form-check-input:checked {
      background-color: #1e2b6d;
      border-color: #1e2b6d;
    }

    .btn-tutor-primary {
      background: #1e2b6d;
      border: none;
      padding: 0.75rem 1.2rem;
      font-weight: 600;
      font-size: 1rem;
      border-radius: 40px;
      color: white;
      transition: background 0.2s, transform 0.1s;
      box-shadow: 0 6px 16px rgba(30, 43, 109, 0.25);
      letter-spacing: 0.3px;
    }

    .btn-tutor-primary:hover {
      background: #162258;
      color: white;
      transform: translateY(-1px);
      box-shadow: 0 10px 24px rgba(30, 43, 109, 0.3);
    }

    .btn-tutor-primary:active {
      transform: translateY(0px);
    }

    .tutor-extra-links {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-top: 0.2rem;
      flex-wrap: wrap;
    }

    .tutor-extra-links a {
      color: #4b5563;
      font-size: 0.9rem;
      text-decoration: none;
      transition: color 0.15s;
      font-weight: 500;
    }

    .tutor-extra-links a:hover {
      color: #1e2b6d;
      text-decoration: underline;
    }

    .tutor-divider {
      display: flex;
      align-items: center;
      margin: 1.6rem 0 1.2rem;
      color: #9ca3af;
      font-size: 0.8rem;
      gap: 0.75rem;
    }

    .tutor-divider hr {
      flex: 1;
      border: none;
      border-top: 1px solid #e5e7eb;
      margin: 0;
    }

    .tutor-student-link {
      text-align: center;
      background: #f8fafc;
      padding: 0.7rem;
      border-radius: 40px;
      border: 1px solid #edf2f7;
      transition: background 0.2s;
    }

    .tutor-student-link a {
      color: #1e2b6d;
      font-weight: 600;
      text-decoration: none;
      font-size: 0.95rem;
    }

    .tutor-student-link a i {
      margin-right: 6px;
    }

    .tutor-student-link:hover {
      background: #f1f4f9;
    }

    /* SweetAlert2 custom override (just in case) */
    .swal2-popup {
      border-radius: 20px !important;
    }

    /* ----- responsive ----- */
    @media (max-width: 820px) {
      .tutor-login-wrapper {
        flex-direction: column;
      }
      .tutor-image-panel {
        flex: none;
        min-height: 38vh;
        padding: 1.8rem 1.5rem;
      }
      .tutor-brand-content img {
        max-height: 150px;
      }
      .tutor-brand-content h2 {
        font-size: 1.8rem;
      }
      .tutor-form-panel {
        flex: none;
        padding: 2rem 1.5rem;
        min-height: 62vh;
        box-shadow: 0 -6px 30px rgba(0, 0, 0, 0.02);
      }
      .tutor-login-card {
        max-width: 360px;
      }
    }

    @media (max-width: 480px) {
      .tutor-brand-content h2 {
        font-size: 1.5rem;
      }
      .tutor-brand-content p {
        font-size: 0.9rem;
      }
      .tutor-login-card .login-header h3 {
        font-size: 1.6rem;
      }
      .tutor-extra-links {
        flex-direction: column;
        gap: 0.3rem;
        align-items: flex-start;
      }
    }
  </style>
</head>
<body>

<div class="tutor-login-wrapper">

  <!-- LEFT SIDE: image + branding -->
  <div class="tutor-image-panel">
    <div class="tutor-brand-content">
      <!-- online tutoring / education image (with fallback) -->
      <img
        src="https://images.unsplash.com/photo-1509062522246-3755977927d7?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80"
        alt="Tutor session"
        onerror="this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNDAwIiBoZWlnaHQ9IjI0MCIgdmlld0JveD0iMCAwIDQwMCAyNDAiIGZpbGw9Im5vbmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+CjxyZWN0IHdpZHRoPSI0MDAiIGhlaWdodD0iMjQwIiBmaWxsPSIjMjA0NTg3Ii8+CjxjaXJjbGUgY3g9IjIwMCIgY3k9IjEwMCIgcj0iNDAiIGZpbGw9IndoaXRlIiBvcGFjaXR5PSIwLjE1Ii8+CjxwYXRoIGQ9Ik0xNDAgMTUwTDIwMCAxOTBMMjYwIDE1MCIgc3Ryb2tlPSJ3aGl0ZSIgc3Ryb2tlLXdpZHRoPSIzIiBzdHJva2UtbGluZWNhcD0icm91bmQiLz4KPHRleHQgeD0iMjAwIiB5PSIyMTUiIHRleHQtYW5jaG9yPSJtaWRkbGUiIGZpbGw9IndoaXRlIiBmb250LXNpemU9IjI0IiBmb250LXdlaWdodD0iNjAwIiBmb250LWZhbWlseT0iQXJpYWwiPlR1dG9yIEh1YjwvdGV4dD4KPC9zdmc+'"
      />
      <h2><i class="fas fa-chalkboard-teacher me-2" style="opacity:0.9;"></i>Tutor Hub</h2>
      <p>Manage your sessions, track student progress, and create engaging quizzes — all in one place.</p>
      <span class="tutor-feature-badge"><i class="fas fa-star me-1"></i> trusted by 2,500+ tutors</span>
    </div>
  </div>

  <!-- RIGHT SIDE: login form -->
  <div class="tutor-form-panel">
    <div class="tutor-login-card">

      <div class="login-header">
        <h3><i class="fas fa-user-graduate me-2" style="color:#2a3f8a;"></i> Tutor Login</h3>
        <p>Access your personal dashboard</p>
      </div>

      <!-- error alert (if any) — using SweetAlert2 via inline script -->
      @if($errors->any())
      <script>
        document.addEventListener('DOMContentLoaded', function() {
          Swal.fire({
            icon: 'error',
            title: 'Login Failed',
            html: `{!! implode('<br>', $errors->all()) !!}`,
            confirmButtonColor: '#1e2b6d',
            confirmButtonText: 'Try again'
          });
        });
      </script>
      @endif

      <!-- login form -->
      <form method="POST" action="{{ route('tutor.login.submit') }}" id="tutorLoginForm">
        @csrf

        <div class="mb-3">
          <label for="tutorEmail" class="form-label"><i class="far fa-envelope me-1"></i> Email Address</label>
          <input
            type="email"
            class="form-control @error('email') is-invalid @enderror"
            id="tutorEmail"
            name="email"
            value="{{ old('email') }}"
            placeholder="tutor@example.com"
            required
            autofocus
          />
          @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <div class="mb-3">
          <label for="tutorPassword" class="form-label"><i class="fas fa-lock me-1"></i> Password</label>
          <input
            type="password"
            class="form-control @error('password') is-invalid @enderror"
            id="tutorPassword"
            name="password"
            placeholder="••••••••"
            required
          />
          @error('password')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <div class="d-flex justify-content-between align-items-center mb-3">
          <div class="form-check">
            <input class="form-check-input" type="checkbox" name="remember" id="rememberTutor" {{ old('remember') ? 'checked' : '' }}>
            <label class="form-check-label" for="rememberTutor" style="font-size:0.9rem;">
              Keep me signed in
            </label>
          </div>
          <a href="#" style="font-size:0.85rem; color:#4b5563;">Forgot password?</a>
        </div>

        <button type="submit" class="btn btn-tutor-primary w-100">
          <i class="fas fa-sign-in-alt me-2"></i> Login
        </button>

        <div class="tutor-divider">
          <hr />
          <span>or</span>
          <hr />
        </div>

        <div class="tutor-student-link">
          <a href="{{ route('login') }}">
            <i class="fas fa-user-graduate"></i> Switch to Student Login
          </a>
        </div>

        <p class="text-center mt-3" style="font-size:0.8rem; color:#9ca3af;">
          <i class="fas fa-shield-alt me-1"></i> Secure · encrypted connection
        </p>
      </form>
    </div>
  </div>
</div>

<!-- Bootstrap JS & SweetAlert2 (for error display) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- optional: automatic alert if there are errors (already handled above) -->
</body>
</html>