@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-[#0f172a] to-[#1e293b] text-slate-100">

  <!-- Header -->
  <header class="max-w-4xl mx-auto px-6 pt-10 pb-6">
    <div class="flex items-center justify-between">
      <div>
        <h1 class="text-3xl font-extrabold tracking-tight bg-clip-text text-transparent bg-gradient-to-r from-indigo-400 to-purple-400">
          Add Topic under Module
        </h1>
        <p class="text-slate-400 text-sm mt-1" id="moduleName">{{ $module->title }}</p>
      </div>
      <button onclick="closeSlide()" class="p-2 text-slate-400 hover:text-white transition">
        <i class="fas fa-times text-xl"></i>
      </button>
    </div>
  </header>

  <!-- Form Card -->
  <div class="max-w-4xl mx-auto px-6 pb-10">
    <div class="bg-white/5 backdrop-blur rounded-3xl shadow-2xl border border-white/10 p-8">

      <form id="topicForm" x-data="topicForm()" @submit.prevent="submitTopic" class="space-y-6">

        <!-- Title -->
        <div>
          <label for="title" class="block text-sm font-semibold text-slate-300 mb-2">Topic Title *</label>
          <input type="text" name="title" id="title" x-model="form.title" required placeholder="e.g. Introduction to HTML"
                 class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl placeholder-slate-400 text-slate-100 focus:ring-2 focus:ring-indigo-400 focus:border-transparent transition">
        </div>

        <!-- Content (Rich editor placeholder) -->
        <div>
          <label for="content" class="block text-sm font-semibold text-slate-300 mb-2">Content</label>
          <textarea name="content" id="content" x-model="form.content" rows="6" placeholder="Write your lesson, paste HTML, or leave empty..."
                    class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl placeholder-slate-400 text-slate-100 focus:ring-2 focus:ring-indigo-400 focus:border-transparent transition"></textarea>
        </div>

        <!-- Video URL -->
        <div>
          <label for="video_url" class="block text-sm font-semibold text-slate-300 mb-2">Video URL (YouTube/Vimeo)</label>
          <input type="url" name="video_url" id="video_url" x-model="form.video_url" placeholder="https://youtu.be/xxxxxx"
                 class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl placeholder-slate-400 text-slate-100 focus:ring-2 focus:ring-indigo-400 focus:border-transparent transition">
        </div>

        <!-- File Upload -->
        <div>
          <label for="file" class="block text-sm font-semibold text-slate-300 mb-2">Upload File (pdf, video, image)</label>
          <input type="file" name="file" id="file" @change="form.file = $event.target.files[0]" accept="video/*,image/*,.pdf"
                 class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-slate-300 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-indigo-600 file:text-white file:cursor-pointer focus:ring-2 focus:ring-indigo-400 transition">
        </div>

        <!-- Two Column Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <!-- Order -->
          <div>
            <label for="order" class="block text-sm font-semibold text-slate-300 mb-2">Order</label>
            <input type="number" name="order" id="order" x-model="form.order" min="0" value="0"
                   class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-slate-100 focus:ring-2 focus:ring-indigo-400 focus:border-transparent transition">
          </div>

          <!-- Status -->
          <div>
            <label for="is_active" class="block text-sm font-semibold text-slate-300 mb-2">Status</label>
            <select name="is_active" id="is_active" x-model="form.is_active" class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-slate-100 focus:ring-2 focus:ring-indigo-400 focus:border-transparent transition">
              <option value="1">Active</option>
              <option value="0">Draft</option>
            </select>
          </div>
        </div>

        <!-- Progress Bar -->
        <template x-if="uploadProgress > 0">
          <div class="w-full bg-white/10 rounded-full h-2.5">
            <div class="bg-indigo-500 h-2.5 rounded-full" :style="`width: ${uploadProgress}%`"></div>
          </div>
        </template>

        <!-- Actions -->
        <div class="flex items-center justify-end gap-3 pt-6 border-t border-white/10">
          <button type="button" onclick="closeSlide()" class="px-5 py-2.5 border border-white/20 text-slate-300 rounded-xl hover:bg-white/10 transition duration-200 font-medium">
            Cancel
          </button>
          <button type="submit" :disabled="submitting" class="group px-5 py-2.5 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 focus:ring-2 focus:ring-indigo-400 focus:ring-offset-2 focus:ring-offset-[#1e293b] transition duration-200 font-medium disabled:opacity-60">
            <span x-text="submitting ? 'Creating...' : 'Create Topic'"></span>
            <i class="fas fa-save ml-2 group-hover:scale-110 transition"></i>
          </button>
        </div>
      </form>
    </div>
  </div>

</div>


<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function closeSlide(){
  // if inside iframe / slide-over
  parent.postMessage({action:'closeTopicSlide'},'*');
}

function topicForm(){
  return {
    form: {
      title: '',
      content: '',
      video_url: '',
      file: null,
      order: 0,
      is_active: 1
    },
    submitting: false,
    uploadProgress: 0,

    async submitTopic(){
      this.submitting = true;
      const fd = new FormData();
      Object.keys(this.form).forEach(k => fd.append(k, this.form[k]));
      fd.append('module_id', {{ $module->id }});

      try{
        const res = await axios.post('/admin/topics', fd, {
          onUploadProgress: e => this.uploadProgress = Math.round((e.loaded * 100) / e.total)
        });
        this.uploadProgress = 0;
        Swal.fire({icon:'success',title:'Created!',text:'Topic added to module.',background:'#1e293b',color:'#e2e8f0',timer:1200});
        parent.postMessage({action:'topicCreated',topic:res.data.topic},'*');
        setTimeout(()=>closeSlide(),500);
      }catch(err){
        this.uploadProgress = 0;
        Swal.fire({icon:'error',title:'Oops',text:err.response.data.message ?? 'Validation failed',background:'#1e293b',color:'#e2e8f0'});
      }finally{
        this.submitting = false;
      }
    }
  };
}
</script>


@endsection
