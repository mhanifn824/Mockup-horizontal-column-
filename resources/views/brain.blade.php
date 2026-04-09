<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BRAIN Executive Dashboard</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script type="module" src="https://cdn.skypack.dev/@hotwired/turbo"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #F8FAFC; animation: fadeIn 0.3s ease-in-out; transition: opacity 0.2s ease-in-out; }
        .text-pertamina-blue { color: #005596; }
        .chart-wrapper:not(:hover) .apexcharts-tooltip { opacity: 0 !important; visibility: hidden !important; transition: opacity 0.1s ease; }
        .apexcharts-tooltip { background: #fff !important; border-color: #e2e8f0 !important; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25) !important; pointer-events: none !important; z-index: 9999 !important; border-radius: 8px !important; }
        .apexcharts-tooltip-title { background: #f8fafc !important; border-bottom: 1px solid #e2e8f0 !important; font-family: 'Inter', sans-serif !important; font-size: 14px !important; font-weight: 800 !important; padding: 12px 16px !important; }
        
        .custom-scrollbar::-webkit-scrollbar { width: 8px; height: 8px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: #f1f5f9; border-radius: 10px;}
        .custom-scrollbar::-webkit-scrollbar-thumb { background-color: #cbd5e1; border-radius: 10px; border: 2px solid #f1f5f9; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background-color: #94a3b8; }

        .lifecycle-pill { background-color: rgba(255,255,255,0.2); color: #ffffff; box-shadow: 0 2px 4px rgba(0,0,0,0.1); backdrop-filter: blur(4px); }
        .sidebar-dark { background-color: #032B25; } 
        .nav-item-active { background-color: #ffffff; border-left: 3px solid #4ade80; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05); }

        .turbo-progress-bar { height: 3px; background-color: #168F4A; }
        .fade-out { opacity: 0 !important; }
        @keyframes fadeIn { from { opacity: 0.3; } to { opacity: 1; } }

        @keyframes rowEnter {
            0% { opacity: 0; transform: translateX(-15px); background-color: #dcfce7; }
            15% { opacity: 1; transform: translateX(0); background-color: #dcfce7; }
            30% { background-color: #dcfce7; }
            100% { opacity: 1; transform: translateX(0); background-color: transparent; }
        }
        .animate-new-row > td { animation: rowEnter 2.5s cubic-bezier(0.2, 0.8, 0.2, 1) forwards; }
    </style>

    <script>
        localStorage.setItem('brain_kpi_total_docs', '{{ number_format($kpiData["total_documents"] ?? 0) }}');
        localStorage.setItem('brain_kpi_project_docs', '{{ number_format($kpiData["document_project"] ?? 0) }}');
        localStorage.setItem('brain_kpi_fungsi_docs', '{{ number_format($kpiData["document_fungsi"] ?? 0) }}');
        localStorage.setItem('brain_kpi_active_users', '{{ number_format($kpiData["active_users_30d"] ?? 0) }}');
        localStorage.setItem('brain_top_project', 'RDMP RU VI Balongan Phase I');
    </script>
</head>
<body class="text-slate-800 flex h-screen overflow-hidden">
    
    <aside class="w-14 sidebar-dark flex flex-col items-center py-4 gap-6 z-50 flex-shrink-0 transition-all">
        <div class="w-8 h-8 bg-green-600 rounded-lg flex items-center justify-center shadow-lg text-white">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>
        </div>
        <div class="flex flex-col gap-4 w-full items-center">
            <button class="p-2 text-gray-400 hover:text-white transition"><svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg></button>
            <button class="p-2 text-gray-400 hover:text-white transition"><svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" /></svg></button>
            <button class="p-2 text-gray-400 hover:text-white transition"><svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /></svg></button>
        </div>
    </aside>

    <aside class="w-56 bg-[#F5F7FA] border-r border-gray-200 flex flex-col py-4 px-3 flex-shrink-0 transition-all">
        <div class="mb-5">
            <span class="text-[10px] text-gray-500 font-bold uppercase tracking-widest pl-2">Document</span>
            <button class="mt-2 w-full bg-[#168F4A] hover:bg-[#0D6230] text-white font-bold py-2.5 px-3 rounded-xl flex items-center justify-center gap-2 shadow-md shadow-green-900/10 transition-all text-sm">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                Upload file
            </button>
        </div>
        <nav class="space-y-1">
            <a href="#" class="nav-item-active flex items-center gap-3 px-3 py-2.5 rounded-lg text-slate-900 font-bold text-sm">
                <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20"><path d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM11 13a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" /></svg>
                Dashboard
            </a>
            <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-gray-600 hover:bg-gray-100 font-semibold transition text-sm">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" /></svg>
                Files
            </a>
            <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-gray-600 hover:bg-gray-100 font-semibold transition text-sm">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" /></svg>
                AI Assistant
            </a>
        </nav>
    </aside>

    <main class="flex-1 flex flex-col h-screen overflow-hidden bg-[#F8FAFC]" id="main-content">
        <header class="bg-white/70 backdrop-blur-md px-8 py-4 flex justify-between items-center border-b border-gray-200 z-40 shrink-0">
            <div>
                <h1 class="text-2xl font-black text-slate-900">Dashboard</h1>
                <div class="text-xs text-gray-500 font-semibold mt-1">Document / <span class="text-slate-800 font-bold">Dashboard</span></div>
            </div>
            <div class="flex items-center gap-5">
                <div class="hidden lg:flex items-center gap-2 bg-green-50 border border-green-200 px-3 py-1.5 rounded-full shadow-sm">
                    <span class="relative flex h-2.5 w-2.5">
                      <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-500 opacity-75"></span>
                      <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-green-600"></span>
                    </span>
                    <span class="text-[11px] font-black text-green-800 tracking-wider"><span id="live-online-counter">42</span> ONLINE</span>
                </div>
                <div class="text-right hidden md:block border-l border-gray-200 pl-5">
                    <div class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mb-0.5">Welcome Back,</div>
                    <div class="text-sm font-black text-gray-800">Muhammad Hanif Naufal</div>
                </div>
                <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-700 font-black border-2 border-blue-300 text-sm">HN</div>
            </div>
        </header>

        <div class="flex-1 overflow-y-auto p-6 custom-scrollbar">
            <div class="space-y-6 max-w-[1600px] mx-auto pb-10">
                
                <div class="grid grid-cols-5 gap-5">
                    <div class="bg-gradient-to-br from-[#9b63b3] to-[#75448c] p-5 rounded-2xl shadow-md relative overflow-hidden flex items-center h-28 hover:shadow-xl transition-shadow">
                        <div class="relative z-10 w-14 h-14 bg-white/95 backdrop-blur-sm rounded-2xl flex items-center justify-center shadow-inner shrink-0 mr-4">
                            <svg class="w-7 h-7 text-[#75448c]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4" /></svg>
                        </div>
                        <div class="relative z-10 flex-grow text-right text-white">
                            <h3 class="text-3xl font-black leading-none tracking-tight">{{ number_format($kpiData['total_documents']) }}</h3>
                            <p class="text-[11px] font-bold tracking-widest mt-1 text-white/90 uppercase">Total Documents</p>
                        </div>
                    </div>

                    <div class="bg-gradient-to-br from-[#ef956b] to-[#d4744d] p-5 rounded-2xl shadow-md relative overflow-hidden flex items-center h-28 hover:shadow-xl transition-shadow">
                        <div class="relative z-10 w-14 h-14 bg-white/95 backdrop-blur-sm rounded-2xl flex items-center justify-center shadow-inner shrink-0 mr-4">
                            <svg class="w-7 h-7 text-[#d4744d]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                        </div>
                        <div class="relative z-10 flex-grow text-right text-white">
                            <h3 class="text-3xl font-black leading-none tracking-tight">{{ number_format($kpiData['document_project']) }}</h3>
                            <p class="text-[11px] font-bold tracking-widest mt-1 text-white/90 uppercase">Project Docs</p>
                        </div>
                    </div>

                    <div class="bg-gradient-to-br from-[#5b7cce] to-[#3a589e] p-5 rounded-2xl shadow-md relative overflow-hidden flex items-center h-28 hover:shadow-xl transition-shadow">
                        <div class="relative z-10 w-14 h-14 bg-white/95 backdrop-blur-sm rounded-2xl flex items-center justify-center shadow-inner shrink-0 mr-4">
                            <svg class="w-7 h-7 text-[#3a589e]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                        </div>
                        <div class="relative z-10 flex-grow text-right text-white">
                            <h3 class="text-3xl font-black leading-none tracking-tight">{{ number_format($kpiData['document_fungsi']) }}</h3>
                            <p class="text-[11px] font-bold tracking-widest mt-1 text-white/90 uppercase">Fungsi Docs</p>
                        </div>
                    </div>

                    <div class="bg-gradient-to-br from-[#a5c6c6] to-[#7ea4a4] p-5 rounded-2xl shadow-md relative overflow-hidden flex items-center h-28 hover:shadow-xl transition-shadow">
                        <div class="relative z-10 w-14 h-14 bg-white/95 backdrop-blur-sm rounded-2xl flex items-center justify-center shadow-inner shrink-0 mr-4">
                            <svg class="w-7 h-7 text-[#7ea4a4]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                        </div>
                        <div class="relative z-10 flex-grow text-right text-white">
                            <h3 class="text-3xl font-black leading-none tracking-tight">{{ number_format($kpiData['total_users']) }}</h3>
                            <p class="text-[11px] font-bold tracking-widest mt-1 text-white/90 uppercase">Total Users</p>
                        </div>
                    </div>

                    <div class="bg-gradient-to-br from-[#4ea1e6] to-[#2563eb] p-5 rounded-2xl shadow-md relative overflow-hidden flex items-center h-28 hover:shadow-xl transition-shadow">
                        <div class="relative z-10 w-14 h-14 bg-white/95 backdrop-blur-sm rounded-2xl flex items-center justify-center shadow-inner shrink-0 mr-4">
                            <svg class="w-7 h-7 text-[#2563eb]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        </div>
                        <div class="relative z-10 flex-grow text-right text-white">
                            <h3 class="text-3xl font-black leading-none tracking-tight">{{ number_format($kpiData['active_users_30d']) }}</h3>
                            <p class="text-[11px] font-bold tracking-widest mt-1 text-white/90 uppercase">Active (30d)</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-200 mt-6">
                    <div class="flex justify-between items-center mb-4 border-b border-gray-100 pb-3">
                        <div>
                            <h4 class="font-black text-gray-900 text-base flex items-center gap-2">
                                Live System Audit Trail 
                                <span class="relative flex h-2.5 w-2.5">
                                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                                  <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-green-500"></span>
                                </span>
                            </h4>
                            <p class="text-[12px] text-gray-500 mt-1 font-medium">Monitoring real-time user activities across BRAIN modules</p>
                        </div>
                    </div>
                    <div class="overflow-x-auto overflow-hidden">
                        <table class="w-full text-left">
                            <thead class="text-[12px] text-gray-500 uppercase font-black border-b border-gray-200 bg-gray-50/50">
                                <tr>
                                    <th class="py-2.5 pl-3 w-1/4 rounded-tl-md">User Identity</th>
                                    <th class="py-2.5 w-36">Action Performed</th>
                                    <th class="py-2.5">Target Details</th>
                                    <th class="py-2.5 text-right pr-3 w-32 rounded-tr-md">Timestamp</th>
                                </tr>
                            </thead>
                            <tbody class="text-xs" id="liveAuditTableBody"></tbody>
                        </table>
                    </div>
                </div>

                <div class="grid grid-cols-12 gap-5 mt-6 mb-4">
                    <a href="#" class="col-span-12 lg:col-span-6 bg-gradient-to-r from-[#f0f4ff] to-white border border-blue-200 p-5 rounded-2xl shadow-sm relative overflow-hidden flex flex-col justify-center cursor-pointer hover:shadow-md hover:border-blue-400 transition-all group block">
                        <div class="absolute top-4 right-4 text-blue-200 transition-transform duration-500 group-hover:scale-125 group-hover:text-blue-300">
                            <svg class="w-16 h-16" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z" clip-rule="evenodd"></path></svg>
                        </div>
                        <div class="relative z-10">
                            <p class="text-xs text-blue-700 font-black uppercase tracking-wider mb-1.5 flex items-center gap-2">
                                AI Assistant Impact
                                <span class="text-[10px] font-bold bg-blue-100 text-blue-800 px-2 py-0.5 rounded shadow-sm normal-case">Last 30 Days</span>
                            </p>
                            <h3 class="text-4xl font-black text-gray-900 leading-tight"><span id="dynamic-queries">{{ $aiImpact['total_queries'] ?? '1,250' }}</span> <span class="text-sm font-bold text-gray-500">Queries</span></h3>
                            <p class="text-xs text-gray-600 mt-1.5 font-semibold"><span id="dynamic-docs" class="text-green-600 font-black text-sm">{{ $aiImpact['documents_summarized'] ?? '430' }}</span> Documents Summarized automatically</p>
                        </div>
                    </a>

                    <div class="col-span-12 lg:col-span-6 bg-white border border-gray-200 p-5 rounded-2xl shadow-sm flex flex-col justify-center relative">
                        <div class="flex justify-between items-start mb-3">
                            <p class="text-xs text-orange-600 font-black uppercase tracking-wider flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" /></svg>
                                Trending Searches
                                <span class="text-[10px] font-bold bg-orange-100 text-orange-800 px-2 py-0.5 rounded shadow-sm normal-case">Last 30 Days</span>
                            </p>
                            <a href="#" class="text-[11px] text-blue-700 font-bold hover:text-white flex items-center gap-1 bg-blue-50 hover:bg-blue-600 px-3 py-1.5 rounded-lg transition border border-blue-200 shadow-sm">Open Smart Search <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg></a>
                        </div>
                        <div class="flex flex-col gap-2">
                            @foreach($trendingKeywords ?? [] as $keyword)
                            <a href="#" class="trending-item px-3 py-2 bg-gray-50 border border-gray-200 text-gray-700 text-xs font-bold rounded-lg truncate hover:bg-orange-50 hover:text-orange-700 hover:border-orange-300 transition-all duration-500 cursor-pointer block shadow-sm">
                                {{ $keyword }}
                            </a>
                            @endforeach
                        </div>
                    </div>
                </div>

                <form id="filterForm" class="bg-white p-3 rounded-xl shadow-sm border border-gray-100 flex flex-wrap sm:flex-nowrap gap-4 items-end mb-2 mt-2">
                    <div class="flex-grow">
                        <label class="block text-[10px] font-bold text-gray-500 mb-1 uppercase tracking-wide">Project Name</label>
                        <select name="project" id="projectFilter" class="w-full bg-gray-50 border border-gray-200 text-gray-700 text-sm rounded-lg focus:ring-blue-500 block p-2 outline-none cursor-pointer">
                            <option value="ALL">All Projects</option>
                            @foreach($projectsData as $p) <option value="{{ $p['name'] }}" {{ $filterProject == $p['name'] ? 'selected' : '' }}>{{ $p['name'] }}</option> @endforeach
                        </select>
                    </div>
                    <div class="w-40">
                        <label class="block text-[10px] font-bold text-gray-500 mb-1 uppercase tracking-wide">Year / Period</label>
                        <select name="year" id="yearFilter" class="w-full bg-gray-50 border border-gray-200 text-gray-700 text-sm rounded-lg focus:ring-blue-500 block p-2 outline-none cursor-pointer">
                            <option value="CURRENT">Current (YTD)</option>
                            <option value="ALL">All Years</option>
                            <option value="2026">2026</option>
                            <option value="2025">2025</option>
                        </select>
                    </div>
                    <div class="w-40">
                        <label class="block text-[10px] font-bold text-gray-500 mb-1 uppercase tracking-wide">Month</label>
                        <select name="month" id="monthFilter" class="w-full bg-gray-50 border border-gray-200 text-gray-700 text-sm rounded-lg focus:ring-blue-500 block p-2 outline-none cursor-pointer">
                            <option value="ALL">All Months</option>
                            @for($m=1; $m<=12; $m++) <option value="{{ $m }}">{{ date('F', mktime(0, 0, 0, $m, 10)) }}</option> @endfor
                        </select>
                    </div>
                </form>

                <div class="grid grid-cols-12 gap-5 mt-4">
                    <div class="col-span-5 bg-white p-5 rounded-xl shadow-sm border border-gray-200 flex flex-col chart-wrapper relative" style="height: calc(100vh - 300px); min-height: 570px;">
                        <div class="mb-3 flex justify-between items-start shrink-0">
                            <div>
                                <h4 class="font-black text-gray-900 text-base">Total Document per Project <span class="text-blue-600 ml-1 font-bold">(<span id="projectTitleLabel">{{ $filterProject == 'ALL' ? 'All Projects' : $filterProject }}</span> <span class="mx-1 text-gray-300">|</span> <span id="timeTitleLabel">{{ $dynamicChartTitle }}</span>)</span></h4>
                                <p class="text-xs text-gray-500 mt-1 font-medium">Total volume based on selected period</p>
                            </div>
                            <button onclick="toggleProjectChart()" id="btnProject" class="text-[10px] font-bold text-blue-700 bg-blue-50 hover:bg-blue-600 hover:text-white px-3 py-1.5 rounded-lg transition border border-blue-200 cursor-pointer shadow-sm shrink-0">View All Projects</button>
                        </div>
                        <div id="chartProject" class="flex-grow w-full min-h-0 transition-opacity duration-300"></div>
                    </div>

                    <div class="col-span-7 bg-white p-5 rounded-xl shadow-sm border border-gray-200 flex flex-col chart-wrapper relative" style="height: calc(100vh - 300px); min-height: 570px;">
                        <div class="mb-2 flex justify-between items-start shrink-0">
                            <div>
                                <h4 class="font-black text-gray-900 text-base">Document Upload Trend <span class="text-orange-600 ml-1 font-bold">(<span id="projectTrendTitleLabel">{{ $filterProject == 'ALL' ? 'All Projects' : $filterProject }}</span> <span class="mx-1 text-gray-300">|</span> <span id="timeTrendTitleLabel">{{ $dynamicChartTitle }}</span>)</span></h4>
                                <p class="text-xs text-gray-500 mt-1 font-medium">Vertical Grouped Column (Scroll horizontally to view more)</p>
                            </div>
                            <button onclick="toggleTrendChart()" id="btnTrend" class="text-[10px] font-bold text-blue-700 bg-blue-50 hover:bg-blue-600 hover:text-white px-3 py-1.5 rounded-lg transition border border-blue-200 cursor-pointer shadow-sm shrink-0">View All Trends</button>
                        </div>
                        <div id="trendChartWrapper" class="w-full overflow-x-auto custom-scrollbar flex-grow pb-2 mt-2">
                            <div id="chartTrend" class="min-h-full transition-opacity duration-300"></div>
                        </div>
                    </div>
                </div>

                <div class="mt-8 mb-4">
                    <h4 class="font-black text-gray-900 text-base">Project Lifecycle Documents</h4>
                    <p class="text-xs text-gray-500 mt-1 font-medium">Total synchronized documents grouped by project execution phase.</p>
                </div>
                <div class="grid grid-cols-5 gap-4 mb-6">
                    @foreach($lifecycleData as $step)
                    <div class="relative h-[80px] rounded-xl p-3 text-white flex flex-col justify-center transition-all duration-300 shadow-sm bg-gradient-to-br from-[#168F4A] to-[#0D6230] {{ in_array($step['phase'], ['01. Initiation', '02. Pre-FS', '03. Pre-FID/Early Work', '04. BED', '05. FEED']) ? 'hover:-translate-y-1 hover:shadow-lg cursor-pointer' : 'cursor-default opacity-90' }}" 
                         @if(in_array($step['phase'], ['01. Initiation', '02. Pre-FS', '03. Pre-FID/Early Work', '04. BED', '05. FEED'])) onclick="openPhaseModal('{{ $step['phase'] }}')" @endif>
                        <div class="flex justify-between items-center gap-2">
                            <div class="flex items-center gap-3">
                                <div class="bg-black/20 w-10 h-10 rounded-lg flex items-center justify-center shrink-0 border border-white/20 shadow-inner">
                                    <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $step['icon'] }}" /></svg>
                                </div>
                                <div class="text-2xl font-black leading-none tracking-tight">{{ number_format($step['count']) }}</div>
                            </div>
                            <div class="bg-white/20 backdrop-blur-md px-2.5 py-1 rounded-md text-[9px] font-black uppercase tracking-widest shadow-sm border border-white/30 text-center w-24 line-clamp-2">
                                {{ $step['phase'] }}
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-200 mt-6 transition-all duration-300 mb-8 relative pt-4 pb-2">
                    <div class="absolute top-4 right-4 z-10">
                        <button onclick="toggleQuickAccess()" class="text-[10px] text-blue-700 font-bold hover:bg-blue-100 flex items-center gap-1 focus:outline-none bg-blue-50 px-3 py-1.5 rounded-md border border-blue-200 transition-colors shadow-sm">
                            <span id="qaToggleText">Hide table</span>
                            <svg id="qaToggleIcon" class="w-3.5 h-3.5 transition-transform duration-300 transform rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 15l7-7 7 7" /></svg>
                        </button>
                    </div>

                    <div class="px-5 mb-4">
                        <h4 class="font-black text-gray-900 text-base mb-3">Quick Access Files</h4>
                        <div class="flex items-center gap-2 border-b border-gray-200 pb-2 overflow-x-auto custom-scrollbar">
                            <button onclick="switchQaTab('recent')" id="tab-recent" class="qa-tab whitespace-nowrap active-tab bg-[#168F4A] text-white px-4 py-2 rounded-lg text-xs font-bold transition-all shadow-md">Recent document</button>
                            <button onclick="switchQaTab('open')" id="tab-open" class="qa-tab whitespace-nowrap text-gray-500 hover:text-gray-800 bg-gray-50 hover:bg-gray-100 px-4 py-2 rounded-lg text-xs font-bold transition-all border border-gray-200">Recent open</button>
                            <button onclick="switchQaTab('confidential')" id="tab-confidential" class="qa-tab whitespace-nowrap text-gray-500 hover:text-gray-800 bg-gray-50 hover:bg-gray-100 px-4 py-2 rounded-lg text-xs font-bold transition-all border border-gray-200">Confidential Document</button>
                            <button onclick="switchQaTab('handover')" id="tab-handover" class="qa-tab whitespace-nowrap text-gray-500 hover:text-gray-800 bg-gray-50 hover:bg-gray-100 px-4 py-2 rounded-lg text-xs font-bold transition-all border border-gray-200">Latest handover document</button>
                        </div>
                    </div>

                    <div id="quickAccessContent" class="transition-all duration-300 origin-top px-5 pb-3">
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-xs text-gray-600">
                                <thead class="text-[10px] text-gray-500 uppercase bg-gray-100 border-y border-gray-200 font-black">
                                    <tr>
                                        <th class="py-2.5 pl-4 w-10 rounded-l-lg"><input type="checkbox" class="rounded border-gray-400 text-blue-600 focus:ring-blue-500 w-3.5 h-3.5 cursor-pointer"></th>
                                        <th class="py-2.5">File Name</th>
                                        <th class="py-2.5">Category</th>
                                        <th class="py-2.5" id="qa-th-security">Security Level</th>
                                        <th class="py-2.5 pr-4 text-right rounded-r-lg">Last Updated</th>
                                    </tr>
                                </thead>
                                
                                <tbody id="tbody-recent" class="qa-tbody">
                                    @foreach($qaRecentDocs ?? [] as $file)
                                    <tr class="bg-white border-b border-gray-100 hover:bg-green-50 transition-colors cursor-pointer group">
                                        <td class="py-3 pl-4"><input type="checkbox" class="rounded border-gray-300 text-green-600 focus:ring-green-500 w-3.5 h-3.5 cursor-pointer" onclick="event.stopPropagation()"></td>
                                        <td class="py-3 font-bold text-gray-900 flex items-center gap-3 group-hover:text-[#168F4A] transition-colors text-sm">
                                            @if(strtolower($file['type']) == 'pdf') <div class="w-7 h-7 bg-red-100 border border-red-200 rounded-lg flex items-center justify-center text-red-600 font-black text-[8px] shrink-0 shadow-sm">PDF</div>
                                            @elseif(in_array(strtolower($file['type']), ['word', 'docx'])) <div class="w-7 h-7 bg-blue-100 border border-blue-200 rounded-lg flex items-center justify-center text-blue-600 font-black text-[8px] shrink-0 shadow-sm">DOC</div>
                                            @elseif(in_array(strtolower($file['type']), ['excel', 'xlsx'])) <div class="w-7 h-7 bg-green-100 border border-green-200 rounded-lg flex items-center justify-center text-green-600 font-black text-[8px] shrink-0 shadow-sm">XLS</div>
                                            @else <div class="w-7 h-7 bg-purple-100 border border-purple-200 rounded-lg flex items-center justify-center text-purple-600 font-black text-[8px] shrink-0 shadow-sm">IMG</div> @endif
                                            <span class="truncate max-w-[250px]">{{ $file['name'] }}</span>
                                        </td>
                                        <td class="py-3"><span class="bg-gray-100 text-gray-700 text-[9px] font-bold px-2 py-1 rounded border border-gray-200 uppercase flex w-fit items-center shadow-sm">{{ $file['category'] }}</span></td>
                                        <td class="py-3 text-gray-700 font-semibold">{{ $file['security'] }}</td>
                                        <td class="py-3 text-gray-500 pr-4 text-right font-medium text-xs">{{ $file['date'] }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>

                                <tbody id="tbody-open" class="qa-tbody hidden">
                                    @foreach($qaRecentOpen ?? [] as $file)
                                    <tr class="bg-white border-b border-gray-100 hover:bg-green-50 transition-colors cursor-pointer group">
                                        <td class="py-3 pl-4"><input type="checkbox" class="rounded border-gray-300 text-green-600 focus:ring-green-500 w-3.5 h-3.5 cursor-pointer" onclick="event.stopPropagation()"></td>
                                        <td class="py-3 font-bold text-gray-900 flex items-center gap-3 group-hover:text-[#168F4A] transition-colors text-sm">
                                            @if(strtolower($file['type']) == 'pdf') <div class="w-7 h-7 bg-red-100 border border-red-200 rounded-lg flex items-center justify-center text-red-600 font-black text-[8px] shrink-0 shadow-sm">PDF</div>
                                            @elseif(in_array(strtolower($file['type']), ['word', 'docx'])) <div class="w-7 h-7 bg-blue-100 border border-blue-200 rounded-lg flex items-center justify-center text-blue-600 font-black text-[8px] shrink-0 shadow-sm">DOC</div>
                                            @elseif(in_array(strtolower($file['type']), ['excel', 'xlsx'])) <div class="w-7 h-7 bg-green-100 border border-green-200 rounded-lg flex items-center justify-center text-green-600 font-black text-[8px] shrink-0 shadow-sm">XLS</div>
                                            @else <div class="w-7 h-7 bg-purple-100 border border-purple-200 rounded-lg flex items-center justify-center text-purple-600 font-black text-[8px] shrink-0 shadow-sm">IMG</div> @endif
                                            <span class="truncate max-w-[250px]">{{ $file['name'] }}</span>
                                        </td>
                                        <td class="py-3"><span class="bg-gray-100 text-gray-700 text-[9px] font-bold px-2 py-1 rounded border border-gray-200 uppercase flex w-fit items-center shadow-sm">{{ $file['category'] }}</span></td>
                                        <td class="py-3 text-gray-700 font-semibold">{{ $file['security'] }}</td>
                                        <td class="py-3 text-gray-500 pr-4 text-right font-medium text-xs">{{ $file['date'] }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>

                                <tbody id="tbody-confidential" class="qa-tbody hidden">
                                    @foreach($qaConfidential ?? [] as $file)
                                    <tr class="bg-white border-b border-gray-100 hover:bg-green-50 transition-colors cursor-pointer group">
                                        <td class="py-3 pl-4"><input type="checkbox" class="rounded border-gray-300 text-green-600 focus:ring-green-500 w-3.5 h-3.5 cursor-pointer" onclick="event.stopPropagation()"></td>
                                        <td class="py-3 font-bold text-gray-900 flex items-center gap-3 group-hover:text-[#168F4A] transition-colors text-sm">
                                            @if(strtolower($file['type']) == 'pdf') <div class="w-7 h-7 bg-red-100 border border-red-200 rounded-lg flex items-center justify-center text-red-600 font-black text-[8px] shrink-0 shadow-sm">PDF</div>
                                            @elseif(in_array(strtolower($file['type']), ['word', 'docx'])) <div class="w-7 h-7 bg-blue-100 border border-blue-200 rounded-lg flex items-center justify-center text-blue-600 font-black text-[8px] shrink-0 shadow-sm">DOC</div>
                                            @elseif(in_array(strtolower($file['type']), ['excel', 'xlsx'])) <div class="w-7 h-7 bg-green-100 border border-green-200 rounded-lg flex items-center justify-center text-green-600 font-black text-[8px] shrink-0 shadow-sm">XLS</div>
                                            @else <div class="w-7 h-7 bg-purple-100 border border-purple-200 rounded-lg flex items-center justify-center text-purple-600 font-black text-[8px] shrink-0 shadow-sm">IMG</div> @endif
                                            <span class="truncate max-w-[250px]">{{ $file['name'] }}</span>
                                        </td>
                                        <td class="py-3"><span class="bg-gray-100 text-gray-700 text-[9px] font-bold px-2 py-1 rounded border border-gray-200 uppercase flex w-fit items-center shadow-sm">{{ $file['category'] }}</span></td>
                                        <td class="py-3">
                                            <span class="text-red-700 bg-red-50 border border-red-300 px-2 py-0.5 rounded font-bold text-[9px] flex items-center gap-1 w-fit shadow-sm">
                                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                                                {{ $file['security'] }}
                                            </span>
                                        </td>
                                        <td class="py-3 text-gray-500 pr-4 text-right font-medium text-xs">{{ $file['date'] }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>

                                <tbody id="tbody-handover" class="qa-tbody hidden">
                                    @foreach($qaHandover ?? [] as $file)
                                    <tr class="bg-white border-b border-gray-100 hover:bg-green-50 transition-colors cursor-pointer group">
                                        <td class="py-3 pl-4"><input type="checkbox" class="rounded border-gray-300 text-green-600 focus:ring-green-500 w-3.5 h-3.5 cursor-pointer" onclick="event.stopPropagation()"></td>
                                        <td class="py-3 font-bold text-gray-900 flex items-center gap-3 group-hover:text-[#168F4A] transition-colors text-sm">
                                            @if(strtolower($file['type']) == 'pdf') <div class="w-7 h-7 bg-red-100 border border-red-200 rounded-lg flex items-center justify-center text-red-600 font-black text-[8px] shrink-0 shadow-sm">PDF</div>
                                            @elseif(in_array(strtolower($file['type']), ['word', 'docx'])) <div class="w-7 h-7 bg-blue-100 border border-blue-200 rounded-lg flex items-center justify-center text-blue-600 font-black text-[8px] shrink-0 shadow-sm">DOC</div>
                                            @elseif(in_array(strtolower($file['type']), ['excel', 'xlsx'])) <div class="w-7 h-7 bg-green-100 border border-green-200 rounded-lg flex items-center justify-center text-green-600 font-black text-[8px] shrink-0 shadow-sm">XLS</div>
                                            @else <div class="w-7 h-7 bg-purple-100 border border-purple-200 rounded-lg flex items-center justify-center text-purple-600 font-black text-[8px] shrink-0 shadow-sm">IMG</div> @endif
                                            <span class="truncate max-w-[250px]">{{ $file['name'] }}</span>
                                        </td>
                                        <td class="py-3"><span class="bg-gray-100 text-gray-700 text-[9px] font-bold px-2 py-1 rounded border border-gray-200 uppercase flex w-fit items-center shadow-sm">{{ $file['category'] }}</span></td>
                                        <td class="py-3"><span class="px-2 py-1 rounded font-bold text-[9px] border {{ $file['status_color'] }} shadow-sm">{{ $file['status'] }}</span></td>
                                        <td class="py-3 text-gray-500 pr-4 text-right font-medium text-xs">{{ $file['date'] }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </main>

    <div id="phaseModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 hidden flex justify-center items-center transition-opacity duration-300 opacity-0">
        <div id="phaseModalContent" class="bg-white w-full max-w-4xl rounded-2xl shadow-2xl flex flex-col transform scale-95 transition-transform duration-300 max-h-[85vh]">
            <div class="px-6 py-5 border-b border-gray-200 flex justify-between items-center bg-gray-50/80 rounded-t-2xl">
                <div class="flex items-center gap-4">
                    <div class="bg-green-100 p-2.5 rounded-xl text-[#168F4A] shadow-inner border border-green-200">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2" /></svg>
                    </div>
                    <div>
                        <h3 class="font-black text-gray-900 text-xl leading-tight">Phase Documents Directory</h3>
                        <p id="phaseModalSubtitle" class="text-xs text-gray-500 mt-1 font-semibold">Showing records for selected phase</p>
                    </div>
                </div>
                <button onclick="closePhaseModal()" class="text-gray-400 hover:text-red-600 transition p-2 rounded-xl hover:bg-red-50 focus:outline-none">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>
            <div class="p-6 overflow-y-auto custom-scrollbar flex-grow">
                <table class="w-full text-left text-xs text-gray-600">
                    <thead class="text-[10px] text-gray-500 uppercase font-black border-b-2 border-gray-200 bg-white sticky top-0 z-10 shadow-sm">
                        <tr>
                            <th class="py-3 pl-3">Document Title</th>
                            <th class="py-3">Project / Origin</th>
                            <th class="py-3">Uploader</th>
                            <th class="py-3 text-right pr-3">Upload Date</th>
                        </tr>
                    </thead>
                    <tbody id="phaseModalTableBody"></tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        function switchQaTab(tabName) {
            document.querySelectorAll('.qa-tab').forEach(el => { 
                el.classList.remove('bg-[#168F4A]', 'text-white', 'shadow-md', 'active-tab'); 
                el.classList.add('bg-gray-50', 'text-gray-500', 'border-gray-200'); 
            });
            const activeTab = document.getElementById('tab-' + tabName);
            activeTab.classList.remove('bg-gray-50', 'text-gray-500', 'border-gray-200'); 
            activeTab.classList.add('bg-[#168F4A]', 'text-white', 'shadow-md', 'active-tab');
            document.getElementById('qa-th-security').innerText = tabName === 'handover' ? 'Handover Status' : 'Security Level';
            document.querySelectorAll('.qa-tbody').forEach(el => el.classList.add('hidden'));
            document.getElementById('tbody-' + tabName).classList.remove('hidden');
        }

        function toggleQuickAccess() {
            const content = document.getElementById('quickAccessContent');
            const text = document.getElementById('qaToggleText');
            const icon = document.getElementById('qaToggleIcon');
            if (content.classList.contains('hidden')) { content.classList.remove('hidden'); text.innerText = "Hide"; icon.classList.remove('rotate-180'); } 
            else { content.classList.add('hidden'); text.innerText = "Show"; icon.classList.add('rotate-180'); }
        }

        // ========================================================
        // 1. DATA MASTER DARI CONTROLLER 
        // ========================================================
        const topProjectNames = @json($barNames); 
        const topProjectValues = @json($barValues);
        const topProjectColors = @json($barColors);
        const fullProjectNames = @json($fullBarNames);
        const fullProjectValues = @json($fullBarValues);
        const fullProjectColors = @json($fullBarColors);
        
        const waveSeries = @json($waveSeries);
        const waveColors = @json($waveColors);
        const fullWaveSeries = @json($fullWaveSeries);
        const fullWaveColors = @json($fullWaveColors);

        const chartCategories = @json($chartCategories);
        const tooltipDates = @json($chartTooltipDates); 
        
        const masterProjectData = fullProjectNames.map((name, index) => ({
            name: name,
            value: fullProjectValues[index],
            color: fullProjectColors[index]
        }));
        
        let projectChartInstance = null; let trendChartInstance = null;
        let isProjectExpanded = false; let isTrendExpanded = false;

        // ========================================================
        // 2. FUNGSI DINAMIS UNTUK HORIZONTAL SCROLL
        // ========================================================
        function adjustTrendChartWidth(numCategories, numSeries) {
            const wrapper = document.getElementById('trendChartWrapper');
            const containerWidth = wrapper.clientWidth || 800; 
            let finalWidth = containerWidth;
            
            if (numCategories > 6) {
                let widthPerCategory = containerWidth / 6;
                finalWidth = widthPerCategory * numCategories;
                
                let absoluteMinWidth = numCategories * ((numSeries * 18) + 30);
                if (finalWidth < absoluteMinWidth) {
                    finalWidth = absoluteMinWidth;
                }
            }
            document.getElementById('chartTrend').style.minWidth = finalWidth + 'px';
        }

        // ========================================================
        // 3. FUNGSI FILTER TANPA RELOAD (SIMULASI AJAX)
        // ========================================================
        function updateChartsSmoothly() {
            const selectedProject = document.getElementById('projectFilter').value;
            const selectedMonth = document.getElementById('monthFilter').value;
            const selectedYear = document.getElementById('yearFilter').value;
            
            document.querySelector('#chartProject').style.opacity = '0.4';
            document.querySelector('#chartTrend').style.opacity = '0.4';

            setTimeout(() => {
                let filteredBarData = [];
                let filteredBarNames = [];
                let filteredBarColors = [];
                let filteredWaveData = [];
                let filteredWaveColors = [];

                if (selectedProject === 'ALL') {
                    filteredBarNames = isProjectExpanded ? fullProjectNames : topProjectNames;
                    filteredBarData = isProjectExpanded ? fullProjectValues : topProjectValues;
                    filteredBarColors = isProjectExpanded ? fullProjectColors : topProjectColors;
                    
                    filteredWaveData = isTrendExpanded ? fullWaveSeries : waveSeries;
                    filteredWaveColors = isTrendExpanded ? fullWaveColors : waveColors;
                } else {
                    const projMatch = masterProjectData.find(item => item.name === selectedProject);
                    if(projMatch) {
                        filteredBarNames = [projMatch.name];
                        filteredBarData = [projMatch.value];
                        filteredBarColors = [projMatch.color];
                    }
                    
                    const waveMatch = fullWaveSeries.find(series => series.name === selectedProject);
                    if(waveMatch) {
                        filteredWaveData = [waveMatch];
                        const wIndex = fullWaveSeries.findIndex(s => s.name === selectedProject);
                        filteredWaveColors = wIndex > -1 ? [fullWaveColors[wIndex]] : ['#005596'];
                    }
                }

                let newCategories = [];
                let titleTimeLabel = "";
                
                if (selectedMonth === 'ALL') {
                    newCategories = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
                    titleTimeLabel = selectedYear === 'ALL' ? 'Aggregate (2025-2026)' : 'Year ' + selectedYear;
                    
                    if(selectedYear !== 'CURRENT') {
                        filteredWaveData = filteredWaveData.map(series => ({
                            name: series.name,
                            data: series.data.map(val => Math.floor(val * (0.8 + Math.random() * 0.4)))
                        }));
                        filteredBarData = filteredBarData.map(val => Math.floor(val * (0.8 + Math.random() * 0.4)));
                    }
                    
                } else {
                    let yearToUse = selectedYear === 'ALL' || selectedYear === 'CURRENT' ? 2026 : parseInt(selectedYear);
                    let daysInMonth = new Date(yearToUse, parseInt(selectedMonth), 0).getDate();
                    const monthName = getMonthName(selectedMonth);
                    
                    for (let i = 1; i <= daysInMonth; i++) {
                        newCategories.push(i.toString() + ' ' + monthName);
                    }
                    titleTimeLabel = monthName + ' ' + yearToUse;
                    
                    filteredWaveData = filteredWaveData.map(series => {
                        let dailyData = [];
                        const isGiantProject = series.name === 'RDMP RU VI Balongan Phase I';
                        for(let i=0; i<daysInMonth; i++) {
                            dailyData.push(isGiantProject ? Math.floor(Math.random() * 200 + 300) : Math.floor(Math.random() * 40 + 10));
                        }
                        return { name: series.name, data: dailyData };
                    });
                    
                    filteredBarData = filteredWaveData.map(series => series.data.reduce((a, b) => a + b, 0));
                }

                adjustTrendChartWidth(newCategories.length, filteredWaveData.length);

                projectChartInstance.updateOptions({
                    xaxis: { categories: filteredBarNames },
                    colors: filteredBarColors
                }, false, false, false);
                
                projectChartInstance.updateSeries([{
                    name: 'Documents',
                    data: filteredBarData
                }]);

                trendChartInstance.updateOptions({
                    xaxis: { categories: newCategories },
                    colors: filteredWaveColors
                }, false, false, false);
                
                trendChartInstance.updateSeries(filteredWaveData);
                
                document.getElementById('projectTitleLabel').innerText = selectedProject === 'ALL' ? 'All Projects' : selectedProject;
                document.getElementById('projectTrendTitleLabel').innerText = selectedProject === 'ALL' ? 'All Projects' : selectedProject;
                document.getElementById('timeTitleLabel').innerText = titleTimeLabel;
                document.getElementById('timeTrendTitleLabel').innerText = titleTimeLabel;

                document.querySelector('#chartProject').style.opacity = '1';
                document.querySelector('#chartTrend').style.opacity = '1';
                
            }, 300); 
        }

        function getMonthName(monthNumber) {
            const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
            return months[parseInt(monthNumber) - 1];
        }

        document.getElementById('projectFilter').addEventListener('change', updateChartsSmoothly);
        document.getElementById('yearFilter').addEventListener('change', updateChartsSmoothly);
        document.getElementById('monthFilter').addEventListener('change', updateChartsSmoothly);

        // ========================================================
        // 4. KONFIGURASI APEX CHARTS
        // ========================================================
        const getProjectOptions = (names, values, colors) => {
            return {
                series: [{ name: 'Documents', data: values }],
                chart: { type: 'bar', height: 595, toolbar: { show: false }, fontFamily: 'Inter, sans-serif' },
                plotOptions: { bar: { borderRadius: 2, horizontal: true, barHeight: '70%', distributed: true, dataLabels: { position: 'top' } } },
                colors: colors,
                dataLabels: { enabled: true, textAnchor: 'start', offsetX: 30, style: { fontSize: '12px', colors: ['#1e293b'], fontWeight: 700, fontFamily: 'Inter, sans-serif' }, formatter: function (val) { return val.toLocaleString('id-ID'); }, dropShadow: { enabled: false } },
                xaxis: { categories: names, labels: { show: true, formatter: val => (val >= 1000000 ? (val/1000000).toFixed(1)+'m' : (val/1000).toFixed(0)+'k'), style: { fontSize: '10px', colors: ['#94a3b8'] } }, axisBorder: { show: false }, axisTicks: { show: false } },
                yaxis: { labels: { style: { fontSize: '12px', fontWeight: 600, fontFamily: 'Inter' }, maxWidth: 180 } },
                grid: { borderColor: '#f1f5f9', strokeDashArray: 3, padding: { bottom: 40, right: 80, left: 10 }, xaxis: { lines: { show: true } }, yaxis: { lines: { show: false } } },
                legend: { show: false },
                tooltip: { theme: 'light', fixed: { enabled: false }, custom: function({series, seriesIndex, dataPointIndex, w}) { let val = series[seriesIndex][dataPointIndex]; let label = w.globals.labels[dataPointIndex]; let color = w.config.colors[dataPointIndex]; return `<div class="px-4 py-3 bg-white border border-gray-300 shadow-2xl rounded-lg min-w-[200px]"><div class="text-[10px] text-gray-400 font-bold uppercase mb-1">Project Name</div><div class="flex items-center gap-2 mb-3"><span class="w-3 h-3 rounded-full flex-shrink-0" style="background-color: ${color}"></span><span class="text-xs font-bold text-gray-800 leading-tight">${label}</span></div><div class="border-t border-gray-200 pt-2 flex justify-between items-center"><span class="text-xs text-gray-500 font-semibold">Total Docs</span><span class="text-lg font-black text-slate-800">${val.toLocaleString('id-ID')}</span></div></div>`; } }
            };
        };

        const getTrendOptions = (seriesData, colors, categories) => {
            return {
                series: seriesData,
                chart: { 
                    type: 'bar', // COLUMN CHART
                    height: 580, 
                    stacked: false, 
                    toolbar: { show: false }, 
                    fontFamily: 'Inter, sans-serif', 
                    zoom: { enabled: false } 
                },
                colors: colors,
                plotOptions: { bar: { horizontal: false, columnWidth: '70%', borderRadius: 2, dataLabels: { position: 'top' } } },
                stroke: { show: true, width: 2, colors: ['transparent'] }, 
                fill: { opacity: 1 }, 
                dataLabels: { enabled: false },
                xaxis: { type: 'category', categories: categories, labels: { style: { fontSize: '10px', fontWeight: 500, colors: '#64748b' } }, axisBorder: { show: false }, axisTicks: { show: false }, tooltip: { enabled: false } },
                yaxis: { labels: { formatter: val => (val >= 1000 ? (val/1000).toFixed(0)+'k' : val), style: { fontSize: '10px', colors: '#94a3b8' } } },
                legend: { position: 'bottom', horizontalAlign: 'center', fontSize: '11px', markers: { radius: 10 }, itemMargin: { horizontal: 10, vertical: 5 }, offsetY: -10 },
                grid: { borderColor: '#f1f5f9', strokeDashArray: 3, padding: { bottom: 30, left: 10, right: 10 } },
                tooltip: { theme: 'light', shared: true, intersect: false, fixed: { enabled: false }, custom: function({series, seriesIndex, dataPointIndex, w}) { if (dataPointIndex < 0 || dataPointIndex >= tooltipDates.length) return null; let fullDateTitle = tooltipDates[dataPointIndex]; let totalVal = 0; let dailyData = []; w.config.series.forEach((s, i) => { if(s.data && s.data[dataPointIndex] > 0) { totalVal += s.data[dataPointIndex]; dailyData.push({ name: s.name, val: s.data[dataPointIndex], color: w.config.colors[i] }); }}); dailyData.sort((a, b) => b.val - a.val); let tooltipList = ""; dailyData.forEach(item => { tooltipList += `<div class="flex justify-between items-center mb-1.5 text-xs"><div class="flex items-center gap-2 overflow-hidden w-[240px]"><span class="w-2.5 h-2.5 rounded-full flex-shrink-0" style="background-color: ${item.color}"></span><span class="text-gray-700 truncate leading-tight font-medium" title="${item.name}">${item.name}</span></div><span class="font-bold text-gray-800 text-xs">${item.val.toLocaleString('id-ID')}</span></div>`; }); return `<div class="bg-white/95 backdrop-blur-sm border border-gray-300 shadow-xl rounded-lg p-4 min-w-[320px]"><div class="text-[10px] text-gray-400 font-bold uppercase tracking-wider mb-3 pb-2 border-b border-gray-100 flex justify-between items-center"><span>Time Period</span><span class="text-gray-800 font-bold">📅 ${fullDateTitle}</span></div><div class="mb-3">${tooltipList}</div><div class="border-t border-gray-200 pt-2 flex justify-between items-center"><span class="text-[10px] text-gray-500 uppercase font-bold">Total Aggregated</span><span class="text-xl font-black text-slate-800">${totalVal.toLocaleString('id-ID')}</span></div></div>`; } }
            };
        };

        const initialTrendSeriesCount = @json($waveSeries).length;
        const initialCategoryCount = chartCategories.length;
        
        projectChartInstance = new ApexCharts(document.querySelector("#chartProject"), getProjectOptions(topProjectNames, topProjectValues, topProjectColors)); 
        projectChartInstance.render();
        
        adjustTrendChartWidth(initialCategoryCount, initialTrendSeriesCount);
        trendChartInstance = new ApexCharts(document.querySelector("#chartTrend"), getTrendOptions(waveSeries, waveColors, chartCategories)); 
        trendChartInstance.render();

        function toggleProjectChart() { 
            isProjectExpanded = !isProjectExpanded; 
            const btn = document.getElementById('btnProject'); 
            if (isProjectExpanded) { 
                projectChartInstance.updateOptions(getProjectOptions(fullProjectNames, fullProjectValues, fullProjectColors)); 
                btn.innerText = "Show Top 10"; 
            } else { 
                projectChartInstance.updateOptions(getProjectOptions(topProjectNames, topProjectValues, topProjectColors)); 
                btn.innerText = "View All Projects"; 
            } 
        }

        function toggleTrendChart() { 
            isTrendExpanded = !isTrendExpanded; 
            const btn = document.getElementById('btnTrend'); 
            const currentCategories = trendChartInstance.w.config.xaxis.categories;
            
            if (isTrendExpanded) { 
                adjustTrendChartWidth(currentCategories.length, fullWaveSeries.length);
                trendChartInstance.updateOptions({ series: fullWaveSeries, colors: fullWaveColors }); 
                btn.innerText = "Show Top 5"; 
            } else { 
                adjustTrendChartWidth(currentCategories.length, waveSeries.length);
                trendChartInstance.updateOptions({ series: waveSeries, colors: waveColors }); 
                btn.innerText = "View All Trends"; 
            } 
        }

        // ========================================================
        // 5. MODAL PHASE
        // ========================================================
        const phaseDocumentsData = @json($phaseDocuments ?? []);
        function openPhaseModal(phaseKey) {
            const m = document.getElementById('phaseModal'), c = document.getElementById('phaseModalContent'), s = document.getElementById('phaseModalSubtitle'), tbody = document.getElementById('phaseModalTableBody');
            s.innerText = `Showing records for ${phaseKey}`;
            tbody.innerHTML = '';
            if(phaseDocumentsData[phaseKey]) {
                phaseDocumentsData[phaseKey].forEach(doc => {
                    let typeBadge = doc.type === 'PDF' ? `<div class="w-7 h-7 bg-red-100 rounded-lg flex items-center justify-center text-red-600 font-black text-[8px] shrink-0 border border-red-200">PDF</div>` : (['word', 'docx'].includes(doc.type.toLowerCase()) ? `<div class="w-7 h-7 bg-blue-100 rounded-lg flex items-center justify-center text-blue-600 font-black text-[8px] shrink-0 border border-blue-200">DOC</div>` : `<div class="w-7 h-7 bg-green-100 rounded-lg flex items-center justify-center text-green-600 font-black text-[8px] shrink-0 border border-green-200">XLS</div>`);
                    tbody.innerHTML += `
                        <tr class="border-b border-gray-100 hover:bg-green-50 transition-colors cursor-pointer group">
                            <td class="py-3 pl-4 flex items-center gap-3">${typeBadge}<div><div class="font-bold text-gray-900 text-xs group-hover:text-[#168F4A] transition-colors">${doc.doc_name}</div><div class="text-[9px] text-gray-500 font-bold mt-0.5">${doc.size}</div></div></td>
                            <td class="py-3 font-bold text-gray-700 text-xs">${doc.project}</td>
                            <td class="py-3 text-gray-600 font-medium text-xs">${doc.uploader}</td>
                            <td class="py-3 text-right pr-4 text-gray-500 text-xs">${doc.date}</td>
                        </tr>
                    `;
                });
            } else { tbody.innerHTML = `<tr><td colspan="4" class="text-center py-8 text-gray-500 font-medium text-xs">No records available for this phase yet.</td></tr>`; }
            m.classList.remove('hidden'); void m.offsetWidth; m.classList.remove('opacity-0'); c.classList.remove('scale-95');
        }
        function closePhaseModal() { const m = document.getElementById('phaseModal'), c = document.getElementById('phaseModalContent'); m.classList.add('opacity-0'); c.classList.add('scale-95'); setTimeout(() => { m.classList.add('hidden'); }, 300); }

        // ========================================================
        // 6. LIVE SYSTEM AUDIT TRAIL LOGIC & LAINNYA
        // ========================================================
        document.addEventListener('DOMContentLoaded', () => {
            const links = document.querySelectorAll('a[href]:not([href="#"]):not([data-turbo="false"])');
            links.forEach(link => {
                link.addEventListener('click', function(e) {
                    if (this.target === '_blank') return;
                    document.body.classList.add('fade-out');
                });
            });
            for(let i = 0; i < 5; i++) { triggerNewLiveLog(); }
            startLiveSimulation(); 
        });

        const fakeUsers = [
            { name: 'Rizky Ramadhan', initial: 'RR' }, { name: 'Nadia Saphira', initial: 'NS' },
            { name: 'Ahmad Fauzi', initial: 'AF' }, { name: 'Dewi Lestari', initial: 'DL' },
            { name: 'Bima Sakti', initial: 'BS' }, { name: 'Putri Kusuma', initial: 'PK' },
            { name: 'Hendra Gunawan', initial: 'HG' }, { name: 'Andi Wijaya', initial: 'AW' },
            { name: 'Siti Nurhaliza', initial: 'SN' }, { name: 'I Putu Borneo', initial: 'IP' }
        ];

        const fakeActions = [
            { label: 'Uploaded', color: 'text-green-700 bg-green-50 border-green-200', avatar: 'bg-green-100 text-green-700', icon: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />' },
            { label: 'Asked AI', color: 'text-purple-700 bg-purple-50 border-purple-200', avatar: 'bg-purple-100 text-purple-700', icon: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />' },
            { label: 'Searched', color: 'text-teal-700 bg-teal-50 border-teal-200', avatar: 'bg-teal-100 text-teal-700', icon: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />' },
            { label: 'Previewed', color: 'text-gray-700 bg-gray-100 border-gray-300', avatar: 'bg-gray-200 text-gray-700', icon: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />' },
            { label: 'Updated Metadata', color: 'text-blue-700 bg-blue-50 border-blue-200', avatar: 'bg-blue-100 text-blue-700', icon: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />' }
        ];

        const fakeTargets = [
            { doc: 'Drawing_Isometric_Area_2_RevA.pdf', loc: 'Project: RDMP RU V Balikpapan', type: 'file' },
            { doc: 'MoM_Weekly_Meeting_EPC_03.docx', loc: 'Project: GRR Tuban', type: 'file' },
            { doc: 'P&ID_Cilacap_Rev_FINAL.pdf', loc: 'Project: RFCC Cilacap', type: 'file' },
            { doc: 'As-Built_Structure_Foundation.pdf', loc: 'Project: New DHT Dumai', type: 'file' },
            { doc: 'Prompt: "Cari pasal penalti keterlambatan..."', loc: 'Module: AI Chatbot', type: 'ai' },
            { doc: 'Keyword: "Valve Specification OR Piping"', loc: 'Module: Smart Search', type: 'search' }
        ];

        function generateRandomLog() {
            const u = fakeUsers[Math.floor(Math.random() * fakeUsers.length)];
            const a = fakeActions[Math.floor(Math.random() * fakeActions.length)];
            let validTargets = [];
            if (a.label === 'Asked AI') validTargets = fakeTargets.filter(t => t.type === 'ai');
            else if (a.label === 'Searched') validTargets = fakeTargets.filter(t => t.type === 'search');
            else validTargets = fakeTargets.filter(t => t.type === 'file');
            const t = validTargets[Math.floor(Math.random() * validTargets.length)];
            return { user: u.name, initial: u.initial, avatar_color: a.avatar, action_label: a.label, action_color: a.color, document: t.doc, location: t.loc, time: 'Just now', icon: a.icon };
        }

        let currentQueries = {{ str_replace(',', '', $aiImpact['total_queries'] ?? '1250') }};
        let currentDocsSummarized = {{ str_replace(',', '', $aiImpact['documents_summarized'] ?? '430') }};

        function updateAiImpactCounter(type) {
            if(type === 'ai_query') { currentQueries += 1; currentDocsSummarized += Math.floor(Math.random() * 4) + 2; } 
            else if(type === 'background_growth') { currentQueries += Math.floor(Math.random() * 2); currentDocsSummarized += Math.floor(Math.random() * 2); }
            if(document.getElementById('dynamic-queries')) document.getElementById('dynamic-queries').innerText = currentQueries.toLocaleString('id-ID');
            if(document.getElementById('dynamic-docs')) document.getElementById('dynamic-docs').innerText = currentDocsSummarized.toLocaleString('id-ID');
        }

        function triggerNewLiveLog() {
            const log = generateRandomLog();
            const safeTitle = log.document.replace(/"/g, '&quot;'); 
            if (log.action_label === 'Asked AI' || log.action_label === 'Searched') updateAiImpactCounter('ai_query');

            const liveTbody = document.getElementById('liveAuditTableBody');
            if(!liveTbody) return;
            const liveTr = document.createElement('tr');
            liveTr.className = 'border-b border-gray-100 hover:bg-green-50 transition-colors animate-new-row group'; 
            liveTr.innerHTML = `
                <td class="py-2.5 pl-3 flex items-center gap-3"><div class="w-8 h-8 rounded-full flex items-center justify-center text-[10px] font-black border-2 border-white shadow-md ${log.avatar_color}">${log.initial}</div><span class="font-bold text-gray-800 text-xs group-hover:text-[#168F4A] transition-colors">${log.user}</span></td>
                <td class="py-2.5"><span class="px-2.5 py-1 rounded-lg text-[9px] font-bold border flex items-center gap-1.5 w-fit shadow-sm ${log.action_color}"><svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">${log.icon}</svg>${log.action_label}</span></td>
                <td class="py-2.5"><div class="font-bold text-gray-900 text-xs truncate max-w-[200px] xl:max-w-[350px]" title="${safeTitle}">${log.document}</div><div class="text-[9px] text-gray-500 mt-0.5"><span class="font-bold uppercase tracking-wider">${log.location}</span></div></td>
                <td class="py-2.5 text-right pr-3"><span class="text-[10px] text-gray-500 font-semibold">${log.time}</span></td>`;
            liveTbody.insertBefore(liveTr, liveTbody.firstChild);
            while (liveTbody.children.length > 5) liveTbody.removeChild(liveTbody.lastElementChild);
        }

        function startLiveSimulation() {
            const nextInterval = Math.floor(Math.random() * (5000 - 2000 + 1)) + 2000;
            setTimeout(() => {
                const eventsToTrigger = Math.random() > 0.8 ? 2 : 1; 
                for(let i=0; i<eventsToTrigger; i++) setTimeout(() => triggerNewLiveLog(), i * 400); 
                updateAiImpactCounter('background_growth');
                startLiveSimulation(); 
            }, nextInterval);
        }

        let currentOnline = 42;
        setInterval(() => {
            let fluctuation = Math.floor(Math.random() * 6) - 2; 
            currentOnline += fluctuation;
            if(currentOnline < 35) currentOnline = 35; 
            if(currentOnline > 55) currentOnline = 55; 
            if(document.getElementById('live-online-counter')) document.getElementById('live-online-counter').innerText = currentOnline;
        }, 3500); 
    </script>
</body>
</html>