@extends('layouts.app')

@section('content')
{{-- SweetAlert2 --}}
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="container mx-auto p-6">

    {{-- Page header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8 animate-fadeIn">
        <h1 class="text-3xl font-extrabold text-gray-800 mb-4 sm:mb-0">Quiz Management</h1>
        <a href="{{ route('quizzes.create') }}"
           class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-lg shadow hover:shadow-lg transform hover:-translate-y-0.5 transition duration-200">
            + Create New Quiz
        </a>
    </div>

    {{-- Table card --}}
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden animate-fadeIn">
        <table class="w-full text-left">
            <thead>
                <tr class="bg-gradient-to-r from-gray-50 to-gray-100 text-gray-700 text-sm uppercase">
                    <th class="p-4">#</th>
                    <th class="p-4">Title</th>
                    <th class="p-4">Description</th>
                    <th class="p-4">Difficulty</th>
                    <th class="p-4">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($quizzes as $quiz)
                    <tr class="hover:bg-gray-50 transition duration-150 hover:scale-[1.01] transform origin-left">
                        <td class="p-4 font-mono text-gray-500">{{ $quiz->id }}</td>
                        <td class="p-4 font-semibold text-gray-800">{{ $quiz->title }}</td>
                        <td class="p-4 text-gray-600">{{ Str::limit($quiz->description, 50) }}</td>
                        <td class="p-4">
                            @php
                                $colors = [
                                    'easy'  => 'bg-green-100 text-green-700',
                                    'medium'=> 'bg-yellow-100 text-yellow-700',
                                    'hard'  => 'bg-red-100 text-red-700'
                                ];
                            @endphp
                            <span class="px-3 py-1 text-xs rounded-full animate-pulseOnce {{ $colors[$quiz->difficulty] ?? 'bg-gray-100 text-gray-700' }}">
                                {{ ucfirst($quiz->difficulty) }}
                            </span>
                        </td>
                        <td class="p-4">
                            <div class="flex flex-wrap gap-2">
                                {{-- Manage Questions --}}
                                <a href="{{ route('questions.index', $quiz) }}"
                                   class="bg-indigo-500 hover:bg-indigo-600 text-white px-3 py-1.5 rounded-md shadow hover:shadow-md transition transform hover:-translate-y-0.5">
                                    Questions
                                </a>

                                {{-- Edit --}}
                                <a href="{{ route('quizzes.edit', $quiz) }}"
                                   class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1.5 rounded-md shadow hover:shadow-md transition transform hover:-translate-y-0.5">
                                    Edit
                                </a>

                                {{-- Delete --}}
                                <form action="{{ route('quizzes.destroy', $quiz) }}" method="POST" class="inline delete-form">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                            class="bg-red-600 hover:bg-red-700 text-white px-3 py-1.5 rounded-md shadow hover:shadow-md transition transform hover:-translate-y-0.5">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="p-8 text-center text-gray-400">
                            <i class="fas fa-inbox text-4xl mb-3 opacity-40"></i>
                            <p>No quizzes yet – create your first one!</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Tailwind animations --}}
<style>
    @keyframes fadeIn { from { opacity:0; transform:translateY(-10px) } to { opacity:1; transform:translateY(0) } }
    .animate-fadeIn { animation: fadeIn .6s ease-out forwards; }

    @keyframes pulseOnce { 0%,100% { transform:scale(1) } 50% { transform:scale(1.05) } }
    .animate-pulseOnce { animation: pulseOnce .8s ease-in-out; }
</style>

{{-- SweetAlert toast helpers --}}
<script>
    /* Show toast for successful create/update */
    @if(session('success'))
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'success',
            title: "{{ session('success') }}",
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true
        });
    @endif

    /* Confirm before delete + toast after */
    document.querySelectorAll('.delete-form').forEach(form => {
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Yes, delete it!'
            }).then(result => {
                if (result.isConfirmed) {
                    fetch(form.action, {
                        method: 'POST',
                        headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                        body: new FormData(form)
                    }).then(res => {
                        if (res.ok) {
                            Swal.fire({ toast: true, icon: 'success', title: 'Quiz deleted!', timer: 2000, position: 'top-end', showConfirmButton: false });
                            setTimeout(() => location.reload(), 800);
                        } else {
                            Swal.fire({ toast: true, icon: 'error', title: 'Something went wrong', timer: 2000, position: 'top-end', showConfirmButton: false });
                        }
                    });
                }
            });
        });
    });
</script>
@endsection
