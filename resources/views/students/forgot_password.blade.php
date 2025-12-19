<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Forgot Password</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
</head>

<body class="min-h-screen bg-gradient-to-br from-blue-600 via-indigo-700 to-purple-800">

    <div class="min-h-screen flex flex-col md:flex-row">

        <!-- IMAGE / INFO SIDE -->
        <div class="hidden md:flex md:w-1/2 items-center justify-center p-8">
            <div class="text-center text-white max-w-md">
                <div class="mb-3">
                    <img
                        src="https://images.unsplash.com/photo-1523050854058-8df90110c9f1?auto=format&fit=crop&w=800&q=80"
                        class="w-full h-64 object-cover rounded-2xl shadow-2xl mx-auto"
                    >
                </div>
                <h2 class="text-4xl font-bold mb-4">Forgot Your Password?</h2>
                <p class="text-blue-100 text-lg">
                    No worries! Enter your email and we'll send you a reset link.
                </p>
            </div>
        </div>

        <!-- FORGOT PASSWORD FORM -->
        <div class="md:w-1/2 flex items-center justify-center p-8">
            <div class="w-full max-w-md bg-white/20 backdrop-blur-lg p-10 rounded-3xl shadow-xl">
                <div class="text-center mb-8">
                    <div class="w-20 h-20 bg-white/30 rounded-full mx-auto flex items-center justify-center">
                        <i class="fas fa-key text-3xl text-white"></i>
                    </div>
                    <h1 class="text-3xl font-bold text-white mt-4">Reset Password</h1>
                </div>

                @if(session('status'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                        {{ session('status') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                        @foreach($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                <form method="POST" action="{{ route('student.forgot.password.submit') }}">
                    @csrf

                    <label class="text-white font-medium">Student Email</label>
                    <input
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        class="w-full px-4 py-3 mt-1 rounded-xl bg-white/90 text-gray-700 focus:ring-2 focus:ring-blue-300 outline-none"
                        placeholder="Enter your email"
                        required
                    >

                    <button
                        type="submit"
                        class="w-full bg-white text-blue-700 mt-6 py-3 rounded-xl font-bold hover:bg-blue-50"
                    >
                        <i class="fas fa-paper-plane mr-2"></i>Send Reset Link
                    </button>
                </form>

                <p class="text-center text-white mt-6">
                    Remember your password?
                    <a href="{{ route('student.login') }}" class="font-semibold hover:text-blue-200">Login here</a>
                </p>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>
</html>
