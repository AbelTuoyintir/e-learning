<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Sign Up</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
<main class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 flex items-center justify-center px-4 py-10">
    <section class="w-full max-w-2xl bg-white rounded-2xl shadow-xl border border-slate-200 p-6 sm:p-9">
        <div class="text-center mb-8">
            <div class="w-14 h-14 rounded-full bg-indigo-100 text-indigo-700 flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-user-graduate text-2xl"></i>
            </div>
            <h1 class="text-3xl font-bold text-slate-900">Create your student account</h1>
            <p class="text-slate-600 mt-2">Sign up to access your learning dashboard.</p>
        </div>

        @if($errors->any())
            <div class="mb-6 rounded-lg border border-red-200 bg-red-50 p-4 text-sm text-red-700" role="alert">
                @foreach($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('student.register.submit') }}" class="space-y-5">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label for="firstname" class="block text-sm font-semibold text-slate-700 mb-1">First name</label>
                    <input id="firstname" name="firstname" value="{{ old('firstname') }}" required autocomplete="given-name" class="w-full rounded-lg border border-slate-300 px-3 py-2.5 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200">
                </div>
                <div>
                    <label for="middlename" class="block text-sm font-semibold text-slate-700 mb-1">Middle name</label>
                    <input id="middlename" name="middlename" value="{{ old('middlename') }}" autocomplete="additional-name" class="w-full rounded-lg border border-slate-300 px-3 py-2.5 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200">
                </div>
                <div>
                    <label for="lastname" class="block text-sm font-semibold text-slate-700 mb-1">Last name</label>
                    <input id="lastname" name="lastname" value="{{ old('lastname') }}" required autocomplete="family-name" class="w-full rounded-lg border border-slate-300 px-3 py-2.5 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="email" class="block text-sm font-semibold text-slate-700 mb-1">Email address</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email" class="w-full rounded-lg border border-slate-300 px-3 py-2.5 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200">
                </div>
                <div>
                    <label for="phone" class="block text-sm font-semibold text-slate-700 mb-1">Phone number</label>
                    <input id="phone" type="tel" name="phone" value="{{ old('phone') }}" autocomplete="tel" class="w-full rounded-lg border border-slate-300 px-3 py-2.5 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200">
                </div>
            </div>

            <div>
                <label for="program" class="block text-sm font-semibold text-slate-700 mb-1">Program</label>
                <input id="program" name="program" value="{{ old('program') }}" class="w-full rounded-lg border border-slate-300 px-3 py-2.5 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="password" class="block text-sm font-semibold text-slate-700 mb-1">Password</label>
                    <input id="password" type="password" name="password" required minlength="8" autocomplete="new-password" class="w-full rounded-lg border border-slate-300 px-3 py-2.5 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200">
                </div>
                <div>
                    <label for="password_confirmation" class="block text-sm font-semibold text-slate-700 mb-1">Confirm password</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" required minlength="8" autocomplete="new-password" class="w-full rounded-lg border border-slate-300 px-3 py-2.5 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200">
                </div>
            </div>

            <button type="submit" class="w-full rounded-lg bg-indigo-700 px-4 py-3 font-semibold text-white hover:bg-indigo-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">Create account</button>
        </form>

        <p class="text-center text-sm text-slate-600 mt-6">
            Already registered? <a href="{{ route('login') }}" class="font-semibold text-indigo-700 hover:underline">Sign in</a>
        </p>
    </section>
</main>
</body>
</html>