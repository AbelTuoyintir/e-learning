<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Reset Password</title>
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
                <h2 class="text-4xl font-bold mb-4">Reset Your Password</h2>
                <p class="text-blue-100 text-lg">
                    Enter your new password below.
                </p>
            </div>
        </div>

        <!-- RESET PASSWORD FORM -->
        <div class="md:w-1/2 flex items-center justify-center p-8">
            <div class="w-full max-w-md bg-white/20 backdrop-blur-lg p-10 rounded-3xl shadow-xl">
                <div class="text-center mb-8">
                    <div class="w-20 h-20 bg-white/30 rounded-full mx-auto flex items-center justify-center">
                        <i class="fas fa-lock text-3xl text-white"></i>
                    </div>
                    <h1 class="text-3xl font-bold text-white mt-4">New Password</h1>
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

                <form method="POST" action="{{ route('student.reset.password.submit') }}">
                    @csrf

                    <input type="hidden" name="token" value="{{ $token }}">
                    <input type="hidden" name="email" value="{{ $email }}">

                    <label class="text-white font-medium">Email</label>
                    <input
                        type="email"
                        name="email"
                        value="{{ $email }}"
                        class="w-full px-4 py-3 mt-1 rounded-xl bg-white/90 text-gray-700 focus:ring-2 focus:ring-blue-300 outline-none"
                        readonly
                    >

                    <label class="text-white font-medium mt-4 block">New Password</label>
                    <div class="relative">
                        <input
                            type="password"
                            name="password"
                            class="w-full px-4 py-3 pr-12 mt-1 rounded-xl bg-white/90 text-gray-700 focus:ring-2 focus:ring-blue-300 outline-none"
                            placeholder="Enter new password"
                            required
                        >
                        <button type="button" onclick="togglePassword()" class="absolute right-4 top-4 text-gray-600">
                            <i id="toggleIcon" class="fas fa-eye"></i>
                        </button>
                    </div>

                    <label class="text-white font-medium mt-4 block">Confirm Password</label>
                    <input
                        type="password"
                        name="password_confirmation"
                        class="w-full px-4 py-3 mt-1 rounded-xl bg-white/90 text-gray-700 focus:ring-2 focus:ring-blue-300 outline-none"
                        placeholder="Confirm new password"
                        required
                    >

                    <button
                        type="submit"
                        class="w-full bg-white text-blue-700 mt-6 py-3 rounded-xl font-bold hover:bg-blue-50"
                    >
                        <i class="fas fa-save mr-2"></i>Reset Password
                    </button>
                </form>

                <p class="text-center text-white mt-6">
                    <a href="{{ route('login') }}" class="font-semibold hover:text-blue-200">Back to Login</a>
                </p>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        function togglePassword() {
            const field = document.getElementById("password");
            const icon = document.getElementById("toggleIcon");
            if (field.type === "password") {
                field.type = "text";
                icon.classList.remove("fa-eye");
                icon.classList.add("fa-eye-slash");
            } else {
                field.type = "password";
                icon.classList.remove("fa-eye-slash");
                icon.classList.add("fa-eye");
            }
        }
    </script>
</body>
</html>
