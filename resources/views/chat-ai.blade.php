<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BRAIN AI Assistant</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #343541; color: #ececf1; animation: fadeIn 0.3s ease-in-out; transition: opacity 0.2s ease-in-out; }
        .bg-user { background-color: #343541; }
        .bg-ai { background-color: #444654; }
        .fade-out { opacity: 0 !important; }
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
        .custom-scrollbar::-webkit-scrollbar { width: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background-color: #565869; border-radius: 10px; }
    </style>
</head>
<body class="flex flex-col h-screen overflow-hidden">

    <header class="bg-[#202123] border-b border-white/10 p-4 flex justify-between items-center shrink-0 shadow-md z-10">
        <div class="flex items-center gap-4">
            <a href="{{ route('dashboard') }}" class="text-gray-400 hover:text-white transition bg-white/5 p-2 rounded-lg">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
            </a>
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-full bg-purple-600 flex items-center justify-center text-white shadow-lg">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                </div>
                <div>
                    <h1 class="font-bold text-white leading-tight">BRAIN Assistant</h1>
                    <p class="text-[10px] text-gray-400 font-medium">Context-Aware AI Model</p>
                </div>
            </div>
        </div>
    </header>

    <main id="chatBox" class="flex-1 overflow-y-auto custom-scrollbar flex flex-col pb-6">
        <div class="bg-ai w-full py-8 border-b border-black/10">
            <div class="max-w-4xl mx-auto flex gap-6 px-6">
                <div class="w-8 h-8 rounded-sm bg-purple-600 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                </div>
                <div class="text-[15px] leading-relaxed text-gray-200">
                    <p class="mb-4">Halo Hanif! Saya telah membaca <strong>Executive Dashboard</strong>.</p>
                    <p class="mb-4">Saat ini sistem memonitor <strong class="text-purple-400">{{ number_format($kpiData['total_documents'] ?? 785420) }} total dokumen</strong>, yang mana <strong class="text-green-400">{{ number_format($kpiData['document_project'] ?? 589065) }}</strong> di antaranya adalah dokumen proyek. Ada <strong class="text-blue-400">{{ $kpiData['active_users'] ?? 202 }} pengguna aktif</strong> dalam 30 hari terakhir.</p>
                    <p>Apa insight yang ingin Anda gali dari dashboard hari ini?</p>
                </div>
            </div>
        </div>
    </main>

    <footer class="bg-user p-6 pt-0 shrink-0 w-full max-w-4xl mx-auto relative">
        <div class="relative bg-[#40414F] border border-white/10 rounded-xl shadow-lg flex items-end overflow-hidden focus-within:ring-1 focus-within:ring-purple-500 transition-all">
            <textarea id="aiInput" rows="1" placeholder="Tanyakan seputar data dashboard..." class="w-full bg-transparent text-white text-[15px] px-4 py-4 focus:outline-none resize-none max-h-32 min-h-[56px]"></textarea>
            <button onclick="sendMessage()" class="p-2 m-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg transition-colors absolute right-0 bottom-0">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
            </button>
        </div>
        <p class="text-[10px] text-center text-gray-500 font-medium mt-3">BRAIN AI mungkin membuat kesalahan. Verifikasi data krusial.</p>
    </footer>

    <script>
        const chatBox = document.getElementById('chatBox');
        const inputEl = document.getElementById('aiInput');

        inputEl.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' && !e.shiftKey) { e.preventDefault(); sendMessage(); }
        });

        function getAiResponse(query) {
            const q = query.toLowerCase();
            if (q.includes('terbanyak') || q.includes('top')) {
                return `Berdasarkan data bar chart, proyek dengan dokumen terbanyak saat ini adalah **RDMP RU VI Balongan Phase I** diikuti oleh **New Polypropylene Plant Balongan**. Ini sejalan dengan fase konstruksi EPC yang sedang masif di area Balongan.`;
            } else if (q.includes('aktif') || q.includes('pengguna') || q.includes('user')) {
                return `Traffic dashboard menunjukkan ada **{{ $kpiData['active_users'] ?? 202 }} pengguna aktif** dalam 30 hari terakhir. Sebagian besar mengakses direktori "Confidential".`;
            } else if (q.includes('hazop')) {
                return `Saya mendeteksi pencarian terkait HAZOP. Dokumen HAZOP seperti *HAZOP Balongan Tahap 1* telah di-tag sebagai **Restricted** oleh sistem karena mengandung data mitigasi risiko Obvitnas. Anda ingin saya merangkum rekomendasinya?`;
            } else {
                return `Maaf, sebagai AI yang terintegrasi dengan BRAIN Dashboard, saya mengerti konteks "Total Dokumen", "Proyek Terbanyak", atau "User Aktif". Silakan ajukan pertanyaan yang berhubungan dengan metrik-metrik tersebut.`;
            }
        }

        function sendMessage() {
            const msg = inputEl.value.trim();
            if (!msg) return;

            // User Chat
            chatBox.innerHTML += `
            <div class="bg-user w-full py-6">
                <div class="max-w-4xl mx-auto flex gap-6 px-6">
                    <div class="w-8 h-8 rounded-sm bg-blue-600 flex items-center justify-center shrink-0 font-bold text-white text-xs">HN</div>
                    <div class="text-[15px] leading-relaxed text-gray-200">${msg}</div>
                </div>
            </div>`;
            
            inputEl.value = '';
            chatBox.scrollTop = chatBox.scrollHeight;

            // Loading state
            const loadingId = 'loading-' + Date.now();
            chatBox.innerHTML += `
            <div id="${loadingId}" class="bg-ai w-full py-6 border-b border-black/10">
                <div class="max-w-4xl mx-auto flex gap-6 px-6">
                    <div class="w-8 h-8 rounded-sm bg-purple-600 flex items-center justify-center shrink-0"><svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg></div>
                    <div class="text-[15px] text-gray-400 animate-pulse">Menulis jawaban...</div>
                </div>
            </div>`;
            chatBox.scrollTop = chatBox.scrollHeight;

            // AI Reply
            setTimeout(() => {
                document.getElementById(loadingId).remove();
                chatBox.innerHTML += `
                <div class="bg-ai w-full py-6 border-b border-black/10">
                    <div class="max-w-4xl mx-auto flex gap-6 px-6">
                        <div class="w-8 h-8 rounded-sm bg-purple-600 flex items-center justify-center shrink-0"><svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg></div>
                        <div class="text-[15px] leading-relaxed text-gray-200">${getAiResponse(msg)}</div>
                    </div>
                </div>`;
                chatBox.scrollTop = chatBox.scrollHeight;
            }, 1000);
        }

        // Transisi halus keluar halaman
        document.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', function(e) {
                if (this.target === '_blank') return;
                e.preventDefault();
                document.body.classList.add('fade-out');
                setTimeout(() => { window.location.href = this.href; }, 200);
            });
        });
    </script>
</body>
</html>