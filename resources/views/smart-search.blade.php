<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smart Search - BRAIN</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #F8FAFC; animation: fadeIn 0.3s ease-in-out; transition: opacity 0.2s ease-in-out; }
        .fade-out { opacity: 0 !important; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(-10px); } to { opacity: 1; transform: translateY(0); } }
        .search-boolean { font-family: monospace; font-weight: bold; color: #d97706; background-color: #fef3c7; padding: 2px 6px; border-radius: 4px; border: 1px solid #fde68a; }
    </style>
</head>
<body class="text-slate-800 flex flex-col h-screen overflow-hidden">
    
    <header class="bg-white border-b border-gray-200 px-6 py-4 flex justify-between items-center shadow-sm z-10 shrink-0">
        <div class="flex items-center gap-4">
            <a href="{{ route('dashboard') }}" class="text-gray-400 hover:text-gray-800 transition bg-gray-50 hover:bg-gray-100 p-2 rounded-lg border border-gray-200">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
            </a>
            <div>
                <h1 class="text-xl font-black text-slate-900">Smart Search</h1>
                <p class="text-xs font-semibold text-gray-500">Advanced Boolean & Metadata Query</p>
            </div>
        </div>
    </header>

    <main class="flex-1 overflow-y-auto p-8 flex flex-col items-center">
        <div class="w-full max-w-4xl mt-10">
            <div class="bg-blue-50 border border-blue-200 text-blue-800 p-4 rounded-xl mb-6 shadow-sm flex items-start gap-3">
                <svg class="w-5 h-5 text-blue-600 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                <div class="text-sm font-medium">
                    Engine ini mendukung operator Boolean. Gunakan <span class="font-bold">AND</span>, <span class="font-bold">OR</span>, dan <span class="font-bold">NOT</span> dengan huruf kapital untuk mencari kombinasi spesifik di dalam metadata dokumen.
                </div>
            </div>

            <form action="{{ route('smart.search') }}" method="GET" class="relative bg-white p-2.5 rounded-2xl shadow-lg border border-gray-200 flex items-center mb-6">
                <div class="pl-4 pr-2 text-gray-400">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                </div>
                <input type="text" name="q" id="smartInput" value="{{ $query }}" placeholder="Contoh: Contract AND (Tuban OR Dumai) NOT Draft" class="w-full text-lg font-bold text-gray-800 bg-transparent border-none focus:ring-0 py-3 px-2 outline-none">
                <button type="submit" class="bg-[#168F4A] hover:bg-[#0D6230] text-white px-8 py-3.5 rounded-xl font-bold shadow-md transition text-sm">Cari</button>
            </form>

            <div id="booleanVisualizer" class="mb-8 hidden transition-all">
                <span class="text-[11px] text-gray-400 font-bold uppercase tracking-wider">Sistem Membaca Query Anda Sebagai:</span>
                <div class="mt-2 text-slate-700 font-medium text-lg bg-white border border-gray-200 p-4 rounded-xl shadow-sm" id="booleanResult"></div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                @if(empty($query))
                    <div class="p-16 text-center text-gray-500">
                        <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 16l2.879-2.879m0 0a3 3 0 104.243-4.242 3 3 0 00-4.243 4.242zM21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        <p class="font-bold text-lg text-gray-800">Mulai Pencarian</p>
                        <p class="text-sm mt-1">Ketikkan kata kunci atau logika Boolean di atas.</p>
                    </div>
                @elseif(empty($results))
                    <div class="p-16 text-center text-gray-500">
                        <p class="font-bold text-lg text-gray-800">Tidak ada hasil</p>
                        <p class="text-sm mt-1">Tidak ditemukan dokumen yang cocok dengan "{{ $query }}"</p>
                    </div>
                @else
                    <table class="w-full text-left text-sm">
                        <thead class="text-xs text-gray-500 uppercase bg-gray-50 border-b border-gray-200 font-black">
                            <tr>
                                <th class="py-4 pl-6">Document Title</th>
                                <th>Project Origin</th>
                                <th>Category / Meta</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($results as $doc)
                            <tr class="border-b border-gray-100 hover:bg-green-50 cursor-pointer transition" onclick="window.location.href='{{ route('document.preview', ['doc' => $doc['title']]) }}'">
                                <td class="py-4 pl-6 font-bold text-gray-900 text-blue-600 hover:underline">{{ $doc['title'] }}</td>
                                <td class="py-4 font-semibold text-gray-700">{{ $doc['project'] }}</td>
                                <td class="py-4"><span class="bg-gray-100 border border-gray-200 px-2.5 py-1 rounded-md text-xs font-bold text-gray-600">{{ $doc['category'] }}</span></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    </main>

    <script>
        const input = document.getElementById('smartInput');
        const visualizer = document.getElementById('booleanVisualizer');
        const result = document.getElementById('booleanResult');

        function renderBoolean() {
            let val = input.value;
            if(val.trim().length > 0) {
                visualizer.classList.remove('hidden');
                // Highlight kata AND, OR, NOT (case sensitive uppercase)
                let htmlVal = val.replace(/\b(AND|OR|NOT)\b/g, '<span class="search-boolean">$1</span>');
                result.innerHTML = htmlVal;
            } else {
                visualizer.classList.add('hidden');
            }
        }
        
        input.addEventListener('input', renderBoolean);
        // Render saat halaman pertama kali load jika ada value
        if(input.value) renderBoolean();

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