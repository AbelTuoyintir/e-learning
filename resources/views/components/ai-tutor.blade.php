<!-- Global AI Tutor Floating Widget -->
<div class="fixed bottom-6 right-6 z-50">
    <button onclick="openAIChat()" class="bg-blue-600 hover:bg-blue-700 text-white w-14 h-14 rounded-full shadow-2xl flex items-center justify-center transition-transform hover:scale-110 active:scale-95 group relative">
        <i class="fas fa-robot text-2xl"></i>
        <span class="absolute right-full mr-3 bg-gray-900 text-white text-xs py-1 px-2 rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap pointer-events-none">Chat with AI Tutor</span>
    </button>
</div>

<!-- AI Chat Modal -->
<div id="aiChatModal" class="fixed inset-0 bg-black/50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white dark:bg-gray-800 rounded-2xl w-full max-w-2xl h-[600px] flex flex-col shadow-2xl overflow-hidden">
        <div class="p-4 border-b dark:border-gray-700 flex justify-between items-center bg-blue-600 text-white">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center mr-3">
                    <i class="fas fa-robot text-xl"></i>
                </div>
                <div>
                    <h3 class="font-bold">AI Academic Tutor</h3>
                    <p class="text-[10px] text-blue-100 uppercase tracking-widest font-bold">Always here to help</p>
                </div>
            </div>
            <button onclick="closeAIChat()" class="text-white hover:text-gray-200 transition-colors bg-white/10 w-8 h-8 rounded-full">&times;</button>
        </div>
        <div id="chatHistory" class="flex-1 overflow-y-auto p-6 space-y-4 bg-gray-50 dark:bg-gray-900/50">
            <div class="bg-blue-100 dark:bg-blue-900/30 dark:text-blue-100 p-4 rounded-2xl rounded-tl-none max-w-[85%] shadow-sm">
                <p class="text-sm">Hello! I am your AI academic tutor. How can I help you with your studies today?</p>
            </div>
        </div>
        <div class="p-4 border-t dark:border-gray-700 bg-white dark:bg-gray-800">
            <form id="aiChatForm" class="flex gap-2">
                <input type="text" id="aiQuestion"
                       class="flex-1 border dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all shadow-sm"
                       placeholder="Ask about a concept, assignment, or topic...">
                <button type="submit" class="bg-blue-600 text-white px-6 py-3 rounded-xl hover:bg-blue-700 transition-all shadow-lg active:scale-95">
                    <i class="fas fa-paper-plane"></i>
                </button>
            </form>
            <p class="text-[10px] text-gray-400 mt-2 text-center">AI can make mistakes. Verify important information.</p>
        </div>
    </div>
</div>

<script>
    async function openAIChat() {
        document.getElementById('aiChatModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden'; // Prevent scrolling
        await loadChatHistory();
    }

    function closeAIChat() {
        document.getElementById('aiChatModal').classList.add('hidden');
        document.body.style.overflow = ''; // Restore scrolling
    }

    async function loadChatHistory() {
        const chatHistory = document.getElementById('chatHistory');
        try {
            const response = await fetch('{{ route("student.ai.history") }}');
            const history = await response.json();

            if (history.length > 0) {
                chatHistory.innerHTML = '';
                history.reverse().forEach(session => {
                    // User message
                    const userMsg = document.createElement('div');
                    userMsg.className = 'bg-white dark:bg-gray-700 p-4 rounded-2xl rounded-tr-none max-w-[85%] ml-auto border dark:border-gray-600 shadow-sm mb-4';
                    userMsg.innerHTML = `<p class="text-sm dark:text-white">${escapeHtml(session.question)}</p>`;
                    chatHistory.appendChild(userMsg);

                    // AI message
                    const aiMsg = document.createElement('div');
                    aiMsg.className = 'bg-blue-100 dark:bg-blue-900/30 dark:text-blue-100 p-4 rounded-2xl rounded-tl-none max-w-[85%] shadow-sm mb-4';
                    aiMsg.innerHTML = `<div class="text-sm">${renderMarkdownToHtml(session.response)}</div>`;

                    if (session.provider) {
                        const providerBadge = document.createElement('small');
                        providerBadge.className = 'block text-[9px] text-blue-500 dark:text-blue-400 mt-2 font-bold uppercase tracking-tighter';
                        providerBadge.textContent = 'Powered by ' + session.provider;
                        aiMsg.appendChild(providerBadge);
                    }
                    chatHistory.appendChild(aiMsg);
                });
                chatHistory.scrollTop = chatHistory.scrollHeight;
            }
        } catch (error) {
            console.error('Error loading history:', error);
        }
    }

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    function renderMarkdownToHtml(markdown) {
        if (!markdown) return '';
        let text = String(markdown).replace(/\r\n/g, '\n');
        const codeBlocks = [];
        text = text.replace(/```([\s\S]*?)```/g, (match, code) => {
            const idx = codeBlocks.length;
            codeBlocks.push(String(code).replace(/^\n|\n$/g, ''));
            return `[[CODE_BLOCK_${idx}]]`;
        });

        function renderInline(md) {
            let s = escapeHtml(md);
            s = s.replace(/\*\*([^*]+)\*\*/g, '<strong>$1</strong>');
            s = s.replace(/\*([^*]+)\*/g, '<em>$1</em>');
            s = s.replace(/`([^`]+)`/g, '<code class="px-1 py-0.5 rounded bg-gray-100 border border-gray-200 text-xs">$1</code>');
            return s;
        }

        const lines = text.split('\n');
        const out = [];
        let i = 0;
        while (i < lines.length) {
            const line = lines[i];
            if (!line || !line.trim()) { i++; continue; }
            const codeMatch = line.match(/\[\[CODE_BLOCK_(\d+)\]\]/);
            if (codeMatch) {
                const idx = parseInt(codeMatch[1], 10);
                const code = codeBlocks[idx] ?? '';
                out.push(`<div class="my-3"><pre class="bg-gray-900 text-gray-100 rounded-xl p-4 overflow-x-auto text-sm"><code>${escapeHtml(code)}</code></pre></div>`);
                i++; continue;
            }
            if (/^(-{3,}|\*{3,}|_{3,})$/.test(line.trim())) { out.push(`<hr class="my-3 border-gray-200" />`); i++; continue; }
            if (/^#{1,6}\s+/.test(line)) {
                const level = line.match(/^#{1,6}/)[0].length;
                const content = line.replace(/^#{1,6}\s+/, '');
                const sizeMap = { 1: 'text-2xl', 2: 'text-xl', 3: 'text-lg', 4: 'text-md', 5: 'text-sm', 6: 'text-xs' };
                out.push(`<h${level} class="font-bold mt-4 mb-1 ${sizeMap[level] || 'text-sm'}">${renderInline(content)}</h${level}>`);
                i++; continue;
            }
            let paraLines = [line];
            i++;
            while (i < lines.length && lines[i] && lines[i].trim() && !/^#{1,6}\s+/.test(lines[i]) && !/^(-{3,}|\*{3,}|_{3,})$/.test(lines[i].trim())) {
                paraLines.push(lines[i]); i++;
            }
            out.push(`<p class="whitespace-pre-wrap my-2 text-sm">${renderInline(paraLines.join('\n')).replace(/\n/g, '<br>')}</p>`);
        }
        return out.join('');
    }

    document.getElementById('aiChatForm').addEventListener('submit', async (e) => {
        e.preventDefault();
        const questionInput = document.getElementById('aiQuestion');
        const question = questionInput.value.trim();
        if (!question) return;
        const chatHistory = document.getElementById('chatHistory');
        const userMsg = document.createElement('div');
        userMsg.className = 'bg-white dark:bg-gray-700 p-4 rounded-2xl rounded-tr-none max-w-[85%] ml-auto border dark:border-gray-600 shadow-sm';
        userMsg.innerHTML = `<p class="text-sm dark:text-white">${escapeHtml(question)}</p>`;
        chatHistory.appendChild(userMsg);
        questionInput.value = '';
        const loadingMsg = document.createElement('div');
        loadingMsg.className = 'bg-blue-50 dark:bg-blue-900/10 dark:text-blue-200 p-4 rounded-2xl rounded-tl-none max-w-[85%] italic text-xs text-gray-500';
        loadingMsg.innerHTML = '<i class="fas fa-circle-notch fa-spin mr-2"></i>AI is thinking...';
        chatHistory.appendChild(loadingMsg);
        chatHistory.scrollTop = chatHistory.scrollHeight;

        try {
            let body = { question };
            const response = await fetch('{{ route("student.ai.chat") }}', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') },
                body: JSON.stringify(body)
            });
            const data = await response.json();
            chatHistory.removeChild(loadingMsg);
            const aiMsg = document.createElement('div');
            aiMsg.className = 'bg-blue-100 dark:bg-blue-900/30 dark:text-blue-100 p-4 rounded-2xl rounded-tl-none max-w-[85%] shadow-sm';
            aiMsg.innerHTML = `<div class="text-sm">${renderMarkdownToHtml(data.response)}</div>`;
            if (data.provider) {
                const providerBadge = document.createElement('small');
                providerBadge.className = 'block text-[9px] text-blue-500 dark:text-blue-400 mt-2 font-bold uppercase tracking-tighter';
                providerBadge.textContent = 'Powered by ' + data.provider;
                aiMsg.appendChild(providerBadge);
            }
            chatHistory.appendChild(aiMsg);
        } catch (error) {
            loadingMsg.className = 'bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 p-3 rounded-lg text-xs';
            loadingMsg.innerHTML = '<i class="fas fa-exclamation-circle mr-2"></i>Error connecting to AI tutor.';
        }
        chatHistory.scrollTop = chatHistory.scrollHeight;
    });
</script>
