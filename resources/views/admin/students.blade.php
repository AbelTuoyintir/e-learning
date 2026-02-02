@extends('layouts.app')

@section('title', 'Manage Students')

@section('content')
<div class="container mx-auto px-6 py-8">

    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-slate-800">Manage Students</h1>
            <p class="text-slate-600 mt-2">View and manage all registered students</p>
        </div>
        <a href="{{ route('admin.dashboard') }}" class="bg-slate-600 hover:bg-slate-700 text-white px-6 py-3 rounded-lg font-semibold transition">
            <i class="fas fa-arrow-left mr-2"></i>Back to Dashboard
        </a>
         <a href="{{ route('admin.students') }}" class="bg-slate-600 hover:bg-slate-700 text-white px-6 py-3 rounded-lg font-semibold transition">
            <i class="fas fa-plus mr-2"></i>Add Student
        </a>
    </div>

    <!-- Students Table -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-200">
            <h2 class="text-xl font-semibold text-slate-800">All Students ({{ $students->count() }})</h2>
        </div>
        <div class="bg-blue-300 shadow-sm shadow-md.rounded-lg.px-6.py-4.mb-6">
            <p class="text-slate-800 text-sm">Note: You can view, edit, or delete student records using the action buttons provided in the table below.</p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-slate-600">Name</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-slate-600">Email</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-slate-600">Phone</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-slate-600">Program</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-slate-600">Registered</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-slate-600">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($students as $student)
                    <tr class="hover:bg-slate-50">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-indigo-100 rounded-full flex items-center justify-center mr-3">
                                    <i class="fas fa-user text-indigo-600"></i>
                                </div>
                                <div>
                                    <p class="font-medium text-slate-800">{{ $student->firstname }} {{ $student->lastname }}</p>
                                    <p class="text-sm text-slate-600">{{ $student->middlename }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-slate-800">{{ $student->email }}</td>
                        <td class="px-6 py-4 text-slate-800">{{ $student->phone ?? 'N/A' }}</td>
                        <td class="px-6 py-4 text-slate-800">{{ $student->Program ?? 'N/A' }}</td>
                        <td class="px-6 py-4 text-slate-600">{{ $student->created_at->format('M d, Y') }}</td>
                        <td class="px-6 py-4">
                            <div class="flex space-x-2">
                                <button class="text-indigo-600 hover:text-indigo-800 p-2 rounded-lg hover:bg-indigo-50">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="text-slate-600 hover:text-slate-800 p-2 rounded-lg hover:bg-slate-50">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="text-red-600 hover:text-red-800 p-2 rounded-lg hover:bg-red-50">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-slate-500">
                            <i class="fas fa-users text-4xl mb-4 text-slate-300"></i>
                            <p>No students registered yet.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
