<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Quiz System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .login-container {
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .split-layout {
            display: flex;
            min-height: 100vh;
        }
        .image-side {
            flex: 1;
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.9) 0%, rgba(118, 75, 162, 0.9) 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }
        .form-side {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            background: white;
        }
        .login-card {
            width: 100%;
            max-width: 400px;
        }
        .image-content {
            text-align: center;
            color: white;
        }
        .image-content img {
            max-width: 100%;
            height: auto;
            border-radius: 10px;
            margin-bottom: 2rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
        }
        .image-content h3 {
            font-weight: 600;
            margin-bottom: 1rem;
        }
        .image-content p {
            opacity: 0.9;
            line-height: 1.6;
        }

        /* Responsive design */
        @media (max-width: 768px) {
            .split-layout {
                flex-direction: column;
            }
            .image-side {
                padding: 1rem;
                min-height: 40vh;
            }
            .form-side {
                padding: 1rem;
                min-height: 60vh;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="split-layout">
            <!-- Image Side -->
            <div class="image-side">
                <div class="image-content">
                    <!-- Option 1: Using an online education image -->
                    <img src="https://images.unsplash.com/photo-1522202176988-66273c2fd55f?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=800&q=80"
                         alt="Education System"
                         onerror="this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNDAwIiBoZWlnaHQ9IjMwMCIgdmlld0JveD0iMCAwIDQwMCAzMDAiIGZpbGw9Im5vbmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+CjxyZWN0IHdpZHRoPSI0MDAiIGhlaWdodD0iMzAwIiBmaWxsPSIjNjc4RUVBIi8+CjxwYXRoIGQ9Ik0xMDAgMTIwTDIwMCAxODBMMzAwIDEyMCIgc3Ryb2tlPSJ3aGl0ZSIgc3Ryb2tlLXdpZHRoPSIzIi8+CjxjaXJjbGUgY3g9IjIwMCIgY3k9IjEwMCIgcj0iMjAiIGZpbGw9IndoaXRlIi8+Cjx0ZXh0IHg9IjIwMCIgeT0iMjUwIiB0ZXh0LWFuY2hvcj0ibWlkZGxlIiBmaWxsPSJ3aGl0ZSIgZm9udC1zaXplPSIyNCIgZm9udC1mYW1pbHk9IkFyaWFsIj5RdWl6IFN5c3RlbTwvdGV4dD4KPC9zdmc+'">
                    <h3>Quiz Management System</h3>
                    <p>Welcome back, Administrator. Manage your courses, quizzes, and student progress with ease.</p>
                </div>
            </div>

            <!-- Form Side -->
            <div class="form-side">
                <div class="login-card">
                    <div class="text-center mb-4">
                        <h2 class="fw-bold text-primary">Admin Login</h2>
                        <p class="text-muted">Access the admin dashboard</p>
                    </div>

                    @if($errors->any())
                    <script>
                        Swal.fire({
                            icon: 'error',
                            title: 'Login Failed',
                            html: `{!! implode('<br>', $errors->all()) !!}`,
                        })
                    </script>
                    @endif

                    <form method="POST" action="{{ route('admin.login.submit') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror"
                                   id="email" name="email" value="{{ old('email') }}" required autofocus>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror"
                                   id="password" name="password" required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="remember" name="remember">
                            <label class="form-check-label" for="remember">Remember me</label>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 py-2 fw-bold">Login</button>
                    </form>

                    <div class="text-center mt-3">
                        <a href="{{ route('login') }}" class="text-decoration-none">
                            Student Login
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>
</html>
