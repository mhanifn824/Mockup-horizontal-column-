<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Preview: {{ $metadata['title'] }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #E2E8F0; animation: fadeIn 0.3s ease-in-out; transition: opacity 0.2s ease-in-out; overflow: hidden; }
        .fade-out { opacity: 0 !important; }
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
        .custom-scrollbar::-webkit-scrollbar { width: 8px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background-color: #cbd5e1; border-radius: 10px; }
    </style>
</head>
<body class="h-screen flex flex-col">

    <header class="h-16 bg-[#202124] flex justify-between items-center px-4 shrink-0 border-b border-gray-800 shadow-md z-20">
        <div class="flex items-center gap-4">
            <a href="javascript:history.back()" class="text-gray-400 hover:text-white transition bg-white/10 p-2 rounded-lg">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
            </a>
            <div class="flex items-center gap-3 border-l border-gray-700 pl-4">
                <svg class="w-6 h-6 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" /></svg>
                <h1 class="font-bold text-sm tracking-wide text-gray-100 truncate max-w-lg">{{ $metadata['title'] }}</h1>
            </div>
        </div>
        
        <div class="flex items-center gap-4 text-gray-300 hidden md:flex">
            <span class="text-xs font-bold bg-[#323639] px-3 py-1.5 rounded border border-gray-600">1 / 14</span>
            <div class="w-px h-5 bg-gray-600 mx-1"></div>
            <button class="hover:text-white transition"><svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M20 12H4" /></svg></button>
            <span class="text-xs font-bold w-12 text-center">100%</span>
            <button class="hover:text-white transition"><svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" /></svg></button>
        </div>
        
        <div class="flex items-center gap-4 text-gray-300">
            <button class="bg-[#168F4A] hover:bg-[#0D6230] text-white px-4 py-2 rounded-lg font-bold text-xs flex items-center gap-2 transition shadow-sm border border-green-700">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>
                Unduh Original
            </button>
        </div>
    </header>

    <div class="flex flex-1 overflow-hidden">
        <main class="w-full lg:w-[75%] bg-[#323639] flex items-center justify-center p-6 relative overflow-y-auto custom-scrollbar shadow-inner">
            <div class="bg-white shadow-2xl w-full max-w-4xl min-h-[900px] h-full rounded-sm flex flex-col items-center justify-center text-center p-10 relative">
                <div class="absolute inset-0 flex items-center justify-center pointer-events-none opacity-[0.03] transform -rotate-45 select-none">
                    <span class="text-7xl font-black tracking-widest text-gray-900">PT PERTAMINA PATRA NIAGA</span>
                </div>
                <svg class="w-28 h-28 text-gray-300 mb-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                <h2 class="text-3xl font-black text-gray-800 break-words px-10 leading-tight">{{ $metadata['title'] }}</h2>
                <p class="text-sm text-gray-500 mt-4 font-medium px-10">Sistem ini mem-preview dokumen asli secara aman. Fitur <i>editing</i> dinonaktifkan sesuai protokol BRAIN.</p>
                
                <div class="mt-8 border border-gray-200 px-6 py-2 rounded bg-gray-50 text-gray-400 font-black uppercase tracking-widest text-xs">
                    CLASS: {{ $metadata['security'] }}
                </div>
            </div>
        </main>

        <aside class="hidden lg:flex w-[25%] bg-white flex-col h-full border-l border-gray-200 shadow-2xl z-10">
            <div class="p-6 border-b border-gray-100 shrink-0">
                <h3 class="text-xl font-black text-gray-900 mb-1">Properties</h3>
                <p class="text-xs text-gray-500 font-medium">Informasi Metadata & AI Insight.</p>
            </div>
            
            <div class="p-6 flex-grow overflow-y-auto custom-scrollbar flex flex-col gap-6">
                <div>
                    <div class="text-[10px] text-gray-400 font-bold uppercase tracking-wider mb-2">Security Level</div>
                    @if($metadata['security'] === 'Confidential')
                        <span class="bg-red-50 text-red-700 border border-red-200 px-3 py-1.5 rounded-lg text-xs font-black shadow-sm flex items-center gap-2 w-fit"><svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg> Confidential</span>
                    @else
                        <span class="bg-blue-50 text-blue-700 border border-blue-200 px-3 py-1.5 rounded-lg text-xs font-black shadow-sm w-fit">Internal</span>
                    @endif
                </div>

                <div>
                    <div class="text-[10px] text-gray-400 font-bold uppercase tracking-wider mb-2">File Information</div>
                    <div class="space-y-3 text-sm text-gray-600 bg-gray-50 p-4 rounded-xl border border-gray-100">
                        <div class="flex justify-between items-center border-b border-gray-200 pb-2">
                            <span class="font-medium text-gray-500">Tipe File:</span> 
                            <span class="font-black text-gray-800">{{ $metadata['type'] }}</span>
                        </div>
                        <div class="flex justify-between items-center border-b border-gray-200 pb-2">
                            <span class="font-medium text-gray-500">Ukuran:</span> 
                            <span class="font-black text-gray-800">{{ $metadata['size'] }}</span>
                        </div>
                        <div class="flex justify-between items-center border-b border-gray-200 pb-2">
                            <span class="font-medium text-gray-500">Jumlah Halaman:</span> 
                            <span class="font-black text-gray-800">14 Hal</span>
                        </div>
                        <div class="flex justify-between items-center pt-1">
                            <span class="font-medium text-gray-500">Waktu Upload:</span> 
                            <span class="font-black text-gray-800">{{ $metadata['date'] }}</span>
                        </div>
                    </div>
                </div>

                <div class="mt-4">
                    <div class="flex items-center gap-2 mb-3">
                        <div class="w-6 h-6 bg-purple-100 rounded-lg flex items-center justify-center shrink-0 border border-purple-200 shadow-sm">
                            <svg class="w-3.5 h-3.5 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                        </div>
                        <div class="text-[11px] text-purple-800 font-black uppercase tracking-wider">BRAIN AI Insight</div>
                    </div>
                    <div class="text-xs text-gray-700 font-medium leading-relaxed bg-gradient-to-br from-purple-50 to-indigo-50 p-4 rounded-xl border border-purple-100 shadow-sm text-justify">
                        Dokumen ini memuat spesifikasi teknis dan matriks persetujuan. Sistem mendeteksi adanya perubahan metodologi pada Bab 3 terkait standar kepatuhan PT Pertamina Patra Niaga. Harap perhatikan Klausul 4.1.
                    </div>
                </div>
            </div>
            
            <div class="p-6 bg-gray-50 border-t border-gray-200 shrink-0">
                <button class="w-full bg-white hover:bg-gray-100 text-gray-700 font-bold py-3.5 rounded-xl border border-gray-300 shadow-sm transition flex justify-center items-center gap-2 text-sm">
                    <svg class="w-4 h-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z" /></svg>
                    Salin Link Berbagi
                </button>
            </div>
        </aside>
    </div>

    <script>
        document.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', function(e) {
                if (this.getAttribute('href') === 'javascript:history.back()') {
                    e.preventDefault();
                    document.body.classList.add('fade-out');
                    setTimeout(() => { window.history.back(); }, 200);
                    return;
                }
                if (this.target === '_blank') return;
                e.preventDefault();
                document.body.classList.add('fade-out');
                setTimeout(() => { window.location.href = this.href; }, 200);
            });
        });
    </script>
</body>
</html>