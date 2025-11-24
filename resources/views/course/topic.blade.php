@extends('layouts.app')
@section('title','Topic Management')
@section('content')
<div class="min-h-screen bg-gray-50 text-gray-800"
     x-data="topicManager()"
     x-init="loadTopic({{ $topic->id ?? 0 }})">

  {{-- HEADER --}}
  <header class="max-w-5xl mx-auto px-6 pt-10 pb-6">
    <div class="flex items-center justify-between">
      <div>
        <h1 class="text-3xl font-extrabold tracking-tight bg-clip-text text-transparent bg-gradient-to-r from-indigo-600 to-purple-600"
            x-text="editId ? 'Edit Topic' : 'Create Topic'"></h1>
        <p class="text-gray-600 text-sm mt-1">Module: <span class="font-semibold text-gray-800">{{ $module->title }}</span></p>
      </div>
      <a href="{{ route('courses.modules',$module->id) }}"
         class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-100 transition text-sm">
        <i class="fas fa-arrow-left mr-2"></i>Back to module
      </a>
    </div>
  </header>

      {{-- TOPICS TABLE --}}
    <div class="max-w-7xl mx-auto px-6 pb-10">
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
            {{-- Table --}}
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Topic
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Module
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Course
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Order
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Status
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Created
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($topics as $topic)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $topic->title }}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            {{ Str::limit($topic->content, 50) }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $topic->module->title ?? 'N/A' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $topic->module->course->title ?? 'N/A' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $topic->order }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                    {{ $topic->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                    {{ $topic->is_active ? 'Active' : 'Draft' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $topic->created_at->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('admin.topics.edit', $topic->id) }}"
                                       class="text-indigo-600 hover:text-indigo-900 transition"
                                       title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    <form action="{{ route('admin.topics.destroy', $topic->id) }}" method="POST"
                                          class="inline delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="text-red-600 hover:text-red-900 transition"
                                                title="Delete"
                                                onclick="return confirm('Are you sure you want to delete this topic?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                                <i class="fas fa-inbox text-4xl mb-3 text-gray-300"></i>
                                <p class="text-lg">No topics found</p>
                                <p class="text-sm mt-1">Get started by creating your first topic</p>
                                <button class="mt-3 inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition"
                                    id="createTopicBtn">
                                    <i class="fas fa-plus mr-2"></i>Create Topic
                                </button>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($topics->hasPages())
            <div class="bg-white px-6 py-4 border-t border-gray-200">
                {{ $topics->links() }}
            </div>
            @endif
        </div>
    </div>
</div>

{{-- MAIN FORM --}}
<div id="topicModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">


    <!-- POPUP BOX -->
    <div class="bg-white w-full max-w-4xl max-h-[90vh] overflow-y-auto rounded-xl shadow-xl p-6 relative">
        <h1 class ="text-3xl text-center font-extrabold mb-3 tracking-tight bg-clip-text text-transparent bg-gradient-to-r from-indigo-600 to-purple-600"> Create Topic </h1>
        <!-- CLOSE BUTTON -->
        <button id="closeTopicModal"
                class="absolute top-3 right-3 text-gray-500 hover:text-gray-700 text-xl">
            &times;
        </button>
    <form method="POST"
      action="{{route('admin.topics.store') }}"
      enctype="multipart/form-data"
      id="myForm"
      class="max-w-5xl mx-auto px-6 pb-10">
    @csrf

    <input type="hidden" name="module_id" value="{{ $module->id }}">

    <div class="grid md:grid-cols-2 gap-4">

      {{-- LEFT: Topic core --}}
      <div class="md:col-span-2 space-y-6">
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 space-y-5">

          {{-- Title --}}
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Topic Title *</label>
            <input type="text" name="title" value="{{ old('title', $topic->title ?? '') }}" required
                   class="w-full px-4 py-2 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
            @error('title')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
          </div>

         <div>
            <label class="text-sm text-gray-600">Order</label>
            <input type="number" name="order" value="{{ old('order', $topic->order ?? 0) }}" min="0"
                   class="w-full mt-1 px-3 py-2 bg-white border border-gray-300 rounded-xl">
            @error('order')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
          </div>

          <div>
            <label class="text-sm text-gray-600">Status</label>
            <select name="is_active"
                    class="w-full mt-1 px-3 py-2 bg-white border border-gray-300 rounded-xl">
              <option value="1" {{ old('is_active', $topic->is_active ?? 1) ? 'selected' : '' }}>Active</option>
              <option value="0" {{ !old('is_active', $topic->is_active ?? 1) ? 'selected' : '' }}>Draft</option>
            </select>
          </div>

        </div>
        <button type="submit"
                class="w-full px-4 py-3 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 flex items-center justify-center gap-2 font-semibold">
          <span>Save Topic</span>
          <i class="fas fa-save"></i>
        </button>
    </div>

    </div>
  </form>
</div>
</div>

</div>




<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    let contentCounter = {{ count(old('contents', [])) }};
    let questionCounter = {{ count(old('questions', [])) }};

    // Add content block
    document.getElementById('addContent').addEventListener('click', function() {
        const contentsList = document.getElementById('contentsList');
        const index = contentCounter++;

        const contentHTML = `
        <div class="content-block bg-gray-50 border border-gray-200 rounded-xl p-4 relative">
            <div class="flex items-start justify-between">
                <span class="text-xs text-gray-500">Block ${contentCounter}</span>
                <button type="button" class="remove-content text-red-600 hover:text-red-800">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <select name="contents[${index}][type]" class="content-type mt-2 w-full px-3 py-1.5 bg-white border border-gray-300 rounded-lg text-sm">
                <option value="text">Text / HTML</option>
                <option value="image">Image</option>
                <option value="video">Video</option>
                <option value="pdf">PDF</option>
            </select>
            <textarea name="contents[${index}][body]" class="text-content mt-2 w-full px-3 py-2 bg-white border border-gray-300 rounded-xl" rows="4" placeholder="Write or paste HTML..."></textarea>
            <input type="file" name="contents[${index}][file]" class="file-content mt-2 w-full text-sm text-gray-700 file:mr-3 file:px-3 file:py-1.5 file:rounded-lg file:border-0 file:bg-indigo-600 file:text-white">
        </div>
        `;

        contentsList.insertAdjacentHTML('beforeend', contentHTML);
    });

    // Add question block
    document.getElementById('addQuestion').addEventListener('click', function() {
        const questionsList = document.getElementById('questionsList');
        const index = questionCounter++;

        const questionHTML = `
        <div class="question-block bg-gray-50 border border-gray-200 rounded-xl p-4 mb-3">
            <div class="flex items-start justify-between">
                <span class="text-xs text-gray-500">Q${questionCounter}</span>
                <button type="button" class="remove-question text-red-600 hover:text-red-800">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <input type="text" name="questions[${index}][text]" placeholder="Question text" class="w-full mt-2 px-3 py-2 bg-white border border-gray-300 rounded-xl">
            <div class="mt-3 space-y-2">
                <div class="flex items-center gap-2 option-row">
                    <input type="text" name="questions[${index}][options][0][text]" placeholder="Option" class="flex-1 px-3 py-1.5 bg-white border border-gray-300 rounded-lg text-sm">
                    <label class="text-xs text-gray-600 flex items-center gap-1">
                        <input type="radio" name="questions[${index}][correct]" value="0" class="text-indigo-600 bg-white border-gray-300" checked>
                        Correct
                    </label>
                    <button type="button" class="remove-option text-red-600 hover:text-red-800">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </div>
                <div class="flex items-center gap-2 option-row">
                    <input type="text" name="questions[${index}][options][1][text]" placeholder="Option" class="flex-1 px-3 py-1.5 bg-white border border-gray-300 rounded-lg text-sm">
                    <label class="text-xs text-gray-600 flex items-center gap-1">
                        <input type="radio" name="questions[${index}][correct]" value="1" class="text-indigo-600 bg-white border-gray-300">
                        Correct
                    </label>
                    <button type="button" class="remove-option text-red-600 hover:text-red-800">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </div>
                <button type="button" class="add-option text-xs text-indigo-600 hover:text-indigo-800">
                    + Add option
                </button>
            </div>
        </div>
        `;

        questionsList.insertAdjacentHTML('beforeend', questionHTML);
    });

    // Remove handlers (delegated)
    document.addEventListener('click', function(e) {
        if (e.target.closest('.remove-content')) {
            e.target.closest('.content-block').remove();
        }
        if (e.target.closest('.remove-question')) {
            e.target.closest('.question-block').remove();
        }
        if (e.target.closest('.remove-option')) {
            if (e.target.closest('.question-block').querySelectorAll('.option-row').length > 1) {
                e.target.closest('.option-row').remove();
            }
        }
        if (e.target.closest('.add-option')) {
            const questionBlock = e.target.closest('.question-block');
            const optionIndex = questionBlock.querySelectorAll('.option-row').length;
            const questionIndex = Array.from(questionBlock.parentNode.children).indexOf(questionBlock);

            const optionHTML = `
            <div class="flex items-center gap-2 option-row">
                <input type="text" name="questions[${questionIndex}][options][${optionIndex}][text]" placeholder="Option" class="flex-1 px-3 py-1.5 bg-white border border-gray-300 rounded-lg text-sm">
                <label class="text-xs text-gray-600 flex items-center gap-1">
                    <input type="radio" name="questions[${questionIndex}][correct]" value="${optionIndex}" class="text-indigo-600 bg-white border-gray-300">
                    Correct
                </label>
                <button type="button" class="remove-option text-red-600 hover:text-red-800">
                    <i class="fas fa-trash-alt"></i>
                </button>
            </div>
            `;
            e.target.insertAdjacentHTML('beforebegin', optionHTML);
        }
    });
});

// Function to show the loading alert
function showLoading() {
    Swal.fire({
        title: 'Now loading',
        allowEscapeKey: false,
        allowOutsideClick: false,
        onOpen: () => {
            Swal.showLoading(); // This shows the loading spinner
        }
    });
}

// Example: Show loading when a form is submitted
document.getElementById('myForm').addEventListener('submit', function(event) {
    showLoading();
});


const openBtn = document.getElementById("createTopicBtn");
const modal = document.getElementById("topicModal");
const closeBtn = document.getElementById("closeTopicModal");

openBtn.addEventListener("click", () => {
    modal.classList.remove("hidden");
    modal.classList.add("flex");
});

// Close when clicking X
closeBtn.addEventListener("click", () => {
    modal.classList.add("hidden");
});

// Close when clicking outside the popup box
modal.addEventListener("click", (e) => {
    if (e.target === modal) {
        modal.classList.add("hidden");
    }
});
</script>
@endsection
