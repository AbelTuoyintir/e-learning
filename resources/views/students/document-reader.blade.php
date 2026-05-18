@extends('layouts.studentNavBar')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="mb-4 flex flex-col gap-3 rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <h1 class="text-xl font-bold text-gray-800">{{ $documentTitle }}</h1>
            <a href="{{ route('students.course.materials', $course->id) }}"
               class="inline-flex items-center rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-700 hover:bg-gray-100">
                Back to Materials
            </a>
        </div>

        <div class="flex flex-wrap items-center gap-3">
            <button id="zoomOutBtn" class="rounded-lg bg-gray-100 px-3 py-2 text-sm font-medium text-gray-800 hover:bg-gray-200">-</button>
            <input id="zoomRange" type="range" min="50" max="200" step="5" value="100" class="w-44">
            <button id="zoomInBtn" class="rounded-lg bg-gray-100 px-3 py-2 text-sm font-medium text-gray-800 hover:bg-gray-200">+</button>
            <span id="zoomLabel" class="min-w-16 text-sm font-semibold text-gray-700">100%</span>
            <button id="resetZoomBtn" class="rounded-lg bg-gray-100 px-3 py-2 text-sm font-medium text-gray-800 hover:bg-gray-200">Reset</button>
        </div>

        <div class="flex flex-wrap items-center gap-3">
            <button id="toggleHighlighterBtn" class="rounded-lg bg-yellow-100 px-3 py-2 text-sm font-medium text-yellow-900 hover:bg-yellow-200">
                Highlighter: Off
            </button>
            <button id="clearHighlightsBtn" class="rounded-lg bg-gray-100 px-3 py-2 text-sm font-medium text-gray-800 hover:bg-gray-200">
                Clear Highlights
            </button>
            <button id="readAloudBtn" class="rounded-lg bg-emerald-100 px-3 py-2 text-sm font-medium text-emerald-900 hover:bg-emerald-200">
                Read Aloud
            </button>
            <button id="stopReadAloudBtn" class="rounded-lg bg-rose-100 px-3 py-2 text-sm font-medium text-rose-900 hover:bg-rose-200">
                Stop
            </button>
        </div>

        <textarea id="ttsTextInput" rows="2"
                  class="w-full rounded-lg border border-gray-300 p-2 text-sm"
                  placeholder="Optional: paste text here for read aloud if your browser cannot read selected text from the document."></textarea>
        <p class="text-xs text-gray-500">
            Tip: Select any text (if selectable) and click "Read Aloud". For PDFs, extracted text is used as fallback.
        </p>
    </div>

    <div id="readerViewport" class="relative h-[78vh] overflow-auto rounded-xl border border-gray-200 bg-gray-50 shadow-inner">
        <div id="documentShell" class="relative origin-top-left">
            <iframe id="documentFrame"
                    src="{{ $sourceUrl }}"
                    class="h-[78vh] w-full border-0 bg-white"
                    title="Document Reader"></iframe>
            <div id="highlightLayer" class="pointer-events-none absolute inset-0"></div>
        </div>
    </div>
</div>

<script type="module">
const zoomRange = document.getElementById('zoomRange');
const zoomLabel = document.getElementById('zoomLabel');
const zoomOutBtn = document.getElementById('zoomOutBtn');
const zoomInBtn = document.getElementById('zoomInBtn');
const resetZoomBtn = document.getElementById('resetZoomBtn');
const shell = document.getElementById('documentShell');
const viewport = document.getElementById('readerViewport');
const highlighterBtn = document.getElementById('toggleHighlighterBtn');
const clearHighlightsBtn = document.getElementById('clearHighlightsBtn');
const highlightLayer = document.getElementById('highlightLayer');
const readAloudBtn = document.getElementById('readAloudBtn');
const stopReadAloudBtn = document.getElementById('stopReadAloudBtn');
const ttsTextInput = document.getElementById('ttsTextInput');
const highlightLoadUrl = @json($highlightLoadUrl);
const highlightSaveUrl = @json($highlightSaveUrl);
const csrfToken = @json(csrf_token());

let zoom = 100;
let highlightMode = false;
let drawing = false;
let draftHighlight = null;
let startPoint = null;
const highlights = [];
let pdfExtractedText = '';
let saveTimer = null;

function applyZoom(value) {
    zoom = Math.min(200, Math.max(50, value));
    const scale = zoom / 100;

    shell.style.transform = `scale(${scale})`;
    shell.style.width = `${100 / scale}%`;
    zoomLabel.textContent = `${zoom}%`;
    zoomRange.value = String(zoom);
}

function setHighlighterMode(enabled) {
    highlightMode = enabled;
    highlightLayer.style.pointerEvents = enabled ? 'auto' : 'none';
    highlightLayer.style.cursor = enabled ? 'crosshair' : 'default';
    highlighterBtn.textContent = `Highlighter: ${enabled ? 'On' : 'Off'}`;
}

function renderHighlightBox(box) {
    const el = document.createElement('div');
    el.className = 'absolute rounded-sm';
    el.style.left = `${box.left}%`;
    el.style.top = `${box.top}%`;
    el.style.width = `${box.width}%`;
    el.style.height = `${box.height}%`;
    el.style.background = 'rgba(253, 224, 71, 0.45)';
    el.style.border = '1px solid rgba(202, 138, 4, 0.45)';
    highlightLayer.appendChild(el);
}

function redrawHighlights() {
    highlightLayer.innerHTML = '';
    highlights.forEach(renderHighlightBox);
}

function scheduleHighlightsSave() {
    if (!highlightSaveUrl) return;

    if (saveTimer) {
        clearTimeout(saveTimer);
    }

    saveTimer = setTimeout(() => {
        saveHighlights().catch((error) => {
            console.warn('Unable to persist highlights:', error);
        });
    }, 350);
}

async function loadHighlights() {
    if (!highlightLoadUrl) return;

    const response = await fetch(highlightLoadUrl, {
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
        },
        credentials: 'same-origin',
    });

    if (!response.ok) return;

    const data = await response.json();
    const remote = Array.isArray(data.highlights) ? data.highlights : [];

    highlights.length = 0;
    for (const box of remote) {
        if (!box) continue;
        highlights.push({
            left: Number(box.left) || 0,
            top: Number(box.top) || 0,
            width: Number(box.width) || 0,
            height: Number(box.height) || 0,
        });
    }

    redrawHighlights();
}

async function saveHighlights() {
    if (!highlightSaveUrl) return;

    await fetch(highlightSaveUrl, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': csrfToken,
        },
        credentials: 'same-origin',
        body: JSON.stringify({ highlights }),
    });
}

function flushHighlightsSaveOnExit() {
    if (!highlightSaveUrl) return;
    const payload = JSON.stringify({ highlights });

    fetch(highlightSaveUrl, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': csrfToken,
        },
        credentials: 'same-origin',
        keepalive: true,
        body: payload,
    }).catch(() => {});
}

function getPointInLayer(event) {
    const rect = highlightLayer.getBoundingClientRect();
    const x = ((event.clientX - rect.left) / rect.width) * 100;
    const y = ((event.clientY - rect.top) / rect.height) * 100;
    return { x, y };
}

highlightLayer.addEventListener('mousedown', (event) => {
    if (!highlightMode) return;
    drawing = true;
    startPoint = getPointInLayer(event);

    draftHighlight = document.createElement('div');
    draftHighlight.className = 'absolute rounded-sm';
    draftHighlight.style.background = 'rgba(253, 224, 71, 0.45)';
    draftHighlight.style.border = '1px dashed rgba(202, 138, 4, 0.7)';
    highlightLayer.appendChild(draftHighlight);
});

highlightLayer.addEventListener('mousemove', (event) => {
    if (!drawing || !startPoint || !draftHighlight) return;

    const point = getPointInLayer(event);
    const left = Math.min(startPoint.x, point.x);
    const top = Math.min(startPoint.y, point.y);
    const width = Math.abs(point.x - startPoint.x);
    const height = Math.abs(point.y - startPoint.y);

    draftHighlight.style.left = `${left}%`;
    draftHighlight.style.top = `${top}%`;
    draftHighlight.style.width = `${width}%`;
    draftHighlight.style.height = `${height}%`;
});

window.addEventListener('mouseup', () => {
    if (!drawing || !startPoint || !draftHighlight) return;

    const left = parseFloat(draftHighlight.style.left || '0');
    const top = parseFloat(draftHighlight.style.top || '0');
    const width = parseFloat(draftHighlight.style.width || '0');
    const height = parseFloat(draftHighlight.style.height || '0');

    draftHighlight.remove();
    draftHighlight = null;

    if (width > 0.5 && height > 0.5) {
        highlights.push({ left, top, width, height });
        redrawHighlights();
        scheduleHighlightsSave();
    }

    drawing = false;
    startPoint = null;
});

zoomRange.addEventListener('input', () => applyZoom(parseInt(zoomRange.value, 10)));
zoomOutBtn.addEventListener('click', () => applyZoom(zoom - 10));
zoomInBtn.addEventListener('click', () => applyZoom(zoom + 10));
resetZoomBtn.addEventListener('click', () => applyZoom(100));
highlighterBtn.addEventListener('click', () => setHighlighterMode(!highlightMode));
clearHighlightsBtn.addEventListener('click', () => {
    highlights.length = 0;
    redrawHighlights();
    scheduleHighlightsSave();
});

function speakText(text) {
    window.speechSynthesis.cancel();
    const utterance = new SpeechSynthesisUtterance(text);
    utterance.rate = 1;
    utterance.pitch = 1;
    utterance.volume = 1;
    window.speechSynthesis.speak(utterance);
}

readAloudBtn.addEventListener('click', () => {
    const selectedText = (window.getSelection()?.toString() || '').trim();
    const manualText = (ttsTextInput.value || '').trim();
    const fallbackText = (pdfExtractedText || '').trim();
    const textToRead = selectedText || manualText || fallbackText;

    if (!textToRead) {
        alert('Select text first, or paste text into the read aloud box.');
        return;
    }

    speakText(textToRead);
});

stopReadAloudBtn.addEventListener('click', () => {
    window.speechSynthesis.cancel();
});

async function extractPdfText() {
    const isPdf = {{ $isPdf ? 'true' : 'false' }};
    if (!isPdf) return;

    try {
        const pdfjs = await import('https://cdnjs.cloudflare.com/ajax/libs/pdf.js/5.4.149/pdf.min.mjs');
        pdfjs.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/5.4.149/pdf.worker.min.mjs';

        const loadingTask = pdfjs.getDocument(@json($sourceUrl));
        const pdf = await loadingTask.promise;

        const textChunks = [];
        const pageLimit = Math.min(pdf.numPages, 25);

        for (let pageNum = 1; pageNum <= pageLimit; pageNum++) {
            const page = await pdf.getPage(pageNum);
            const textContent = await page.getTextContent();
            const pageText = textContent.items.map(item => item.str).join(' ');
            textChunks.push(pageText);
        }

        pdfExtractedText = textChunks.join(' ').replace(/\s+/g, ' ').trim();
    } catch (error) {
        console.warn('PDF text extraction unavailable:', error);
    }
}

applyZoom(100);
setHighlighterMode(false);
loadHighlights().catch((error) => console.warn('Unable to load highlights:', error));
extractPdfText();
window.addEventListener('beforeunload', flushHighlightsSaveOnExit);
</script>
@endsection
