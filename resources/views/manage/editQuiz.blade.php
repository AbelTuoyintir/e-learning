@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-2xl font-bold text-gray-800 mb-4">Edit Quiz</h1>

    <div class="bg-white p-6 rounded-lg shadow">
       <form action="{{ route('quizzes.update', $quiz->id) }}" method="POST" enctype="multipart/form-data">
            @method('PUT')
            @include('manage._form', ['submitText' => 'Update Quiz'])
        </form>
    </div>
</div>
@endsection
