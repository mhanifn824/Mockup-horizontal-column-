<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $filterYear = $request->input('year', 'CURRENT'); 
        $filterMonth = $request->input('month', 'ALL');     
        $filterProject = $request->input('project', 'ALL'); 

        $currentDate = Carbon::create(2026, 2, 19); 
        $currentYear = 2026;
        $currentMonth = 2; 

        $projectsData = [
            ['name' => 'RDMP RU V Balikpapan Phase I', 'color' => '#FF2E2E'], 
            ['name' => 'New Polypropylene Plant Balongan', 'color' => '#FF6B00'], 
            ['name' => 'New DHT Dumai', 'color' => '#FF9F1C'], 
            ['name' => 'Restorasi Tanki Balongan', 'color' => '#FFC107'], 
            ['name' => 'SPL SPM Balongan', 'color' => '#007BFF'], 
            ['name' => 'New DHT Cilacap', 'color' => '#00C6FF'], 
            ['name' => 'RFCC Cilacap', 'color' => '#00BCD4'], 
            ['name' => 'Biorefinery Cilacap', 'color' => '#2196F3'], 
            ['name' => 'PLBC Cilacap', 'color' => '#4CAF50'], 
            ['name' => 'Olefin TPPI', 'color' => '#8BC34A'], 
            ['name' => 'RDMP RU IV Cilacap', 'color' => '#CDDC39'], 
            ['name' => 'Relokasi SPM Balongan', 'color' => '#009688'], 
            ['name' => 'New DHT Plaju', 'color' => '#9C27B0'], 
            ['name' => 'Revitalisasi RCC RU VI Balongan', 'color' => '#E91E63'], 
            ['name' => 'Green Refinery Plaju', 'color' => '#673AB7'], 
            ['name' => 'New EWTP Balongan', 'color' => '#FF4081'], 
            ['name' => 'RDMP RU VI Balongan Phase I', 'color' => '#FF5722'], 
            ['name' => 'RDMP RU V Early Works 1', 'color' => '#2ECC71'], 
            ['name' => 'GRR Tuban', 'color' => '#F1C40F'], 
            ['name' => 'Petrochemical Jawa Barat', 'color' => '#1ABC9C'], 
            ['name' => 'RDMP RU V ISBL - OSBL', 'color' => '#3498DB'], 
            ['name' => 'RDMP RU V Lawe - Lawe', 'color' => '#E74C3C'], 
        ];

        $chartCategories = [];   
        $chartTooltipDates = []; 
        $dynamicChartTitle = "";

        $effectiveYear = ($filterYear === 'CURRENT' || $filterYear === 'ALL') ? $currentYear : (int)$filterYear;

        if ($filterMonth !== 'ALL') {
            $startDate = Carbon::createFromDate($effectiveYear, $filterMonth, 1)->startOfMonth();
            $endDate = Carbon::createFromDate($effectiveYear, $filterMonth, 1)->endOfMonth();
            
            $period = CarbonPeriod::create($startDate, '1 day', $endDate);
            $monthNameShort = Carbon::createFromDate(null, $filterMonth, 1)->format('M');
            $monthNameFull = Carbon::createFromDate(null, $filterMonth, 1)->format('F');
            
            foreach ($period as $date) {
                $dayStr = $date->format('d');
                $chartCategories[] = $dayStr; 
                if ($filterYear === 'ALL') {
                    $chartTooltipDates[] = $dayStr . " " . $monthNameShort . " (Total 2025-2026)";
                } else {
                    $chartTooltipDates[] = $date->format('d M Y');
                }
            }
            $dynamicChartTitle = ($filterYear === 'ALL') ? $monthNameFull . " (Total 2025-2026)" : $monthNameFull . " " . $effectiveYear;
        } else {
            $chartCategories = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
            if ($filterYear === 'ALL') {
                $dynamicChartTitle = "Aggregate Monthly (2025 - 2026)";
                foreach ($chartCategories as $cat) { $chartTooltipDates[] = $cat . " (Total 2025-2026)"; }
            } else {
                $yearLabel = ($filterYear === 'CURRENT') ? $currentYear : $filterYear;
                $dynamicChartTitle = ($filterYear === 'CURRENT') ? "Current Year (YTD)" : "Year " . $filterYear;
                foreach ($chartCategories as $cat) { $chartTooltipDates[] = $cat . " " . $yearLabel; }
            }
        }

        $barChartData = []; 
        $waveChartData = []; 
        $grandTotalDocuments = 0; 

        foreach ($projectsData as $proj) {
            $isProjectActive = ($filterProject === 'ALL' || $filterProject === $proj['name']);
            $isGiantProject = ($proj['name'] === 'RDMP RU VI Balongan Phase I');
            $waveDataPoints = [];
            $projectTotal = 0; 

            for ($i = 0; $i < count($chartCategories); $i++) {
                $val = 0;
                $shouldGenerate = true;
                if ($filterYear === 'ALL') {
                    foreach ([2025, 2026] as $simYear) {
                        if ($filterMonth !== 'ALL') {
                             $base = $isGiantProject ? rand(300, 500) : rand(10, 50);
                        } else {
                             $base = $isGiantProject ? rand(12000, 16000) : rand(800, 2000);
                        }
                        $variance = rand(90, 110) / 100;
                        $val += floor($base * $variance);
                    }
                } else {
                    if ($filterMonth !== 'ALL') {
                        $day = $i + 1;
                        if ($effectiveYear == $currentYear && $filterMonth == $currentMonth && $day > 19) $shouldGenerate = false;
                        if ($effectiveYear == $currentYear && $filterMonth > $currentMonth) $shouldGenerate = false;
                        if ($shouldGenerate) $val = $isGiantProject ? rand(300, 500) : rand(10, 50);
                    } else {
                        $month = $i + 1;
                        if ($effectiveYear == $currentYear && $month > $currentMonth) $shouldGenerate = false;
                        if ($shouldGenerate) $val = $isGiantProject ? rand(15000, 20000) : rand(800, 2500);
                    }
                }
                $waveDataPoints[] = (int)$val;
                $projectTotal += (int)$val;
            }

            if ($isProjectActive) {
                $barChartData[] = ['name' => $proj['name'], 'color' => $proj['color'], 'total' => $projectTotal];
                $waveChartData[] = ['name' => $proj['name'], 'color' => $proj['color'], 'data' => $waveDataPoints, 'total_trend' => $projectTotal];
                $grandTotalDocuments += $projectTotal;
            }
        }

        usort($barChartData, function($a, $b) { return $b['total'] <=> $a['total']; });
        $barNamesOrder = array_column($barChartData, 'name');
        usort($waveChartData, function($a, $b) use ($barNamesOrder) {
            $posA = array_search($a['name'], $barNamesOrder);
            $posB = array_search($b['name'], $barNamesOrder);
            return $posA <=> $posB;
        });

        $fullBarNames = array_column($barChartData, 'name');
        $fullBarValues = array_column($barChartData, 'total');
        $fullBarColors = array_column($barChartData, 'color');

        $top10Bar = array_slice($barChartData, 0, 10);
        $others10Bar = array_slice($barChartData, 10);
        $barNames = array_column($top10Bar, 'name');
        $barValues = array_column($top10Bar, 'total');
        $barColors = array_column($top10Bar, 'color');

        if (!empty($others10Bar)) {
            $sumOthers = 0;
            foreach ($others10Bar as $item) $sumOthers += $item['total'];
            $barNames[] = 'Others (' . count($others10Bar) . ' Projects)';
            $barValues[] = $sumOthers;
            $barColors[] = '#CFD8DC';
        }

        $fullWaveSeries = [];
        foreach($waveChartData as $item) $fullWaveSeries[] = ['name' => $item['name'], 'data' => $item['data']];
        $fullWaveColors = array_column($waveChartData, 'color');

        $top5Wave = array_slice($waveChartData, 0, 5);
        $others5Wave = array_slice($waveChartData, 5);
        $waveColors = array_column($top5Wave, 'color');
        $waveSeries = [];
        foreach($top5Wave as $item) $waveSeries[] = ['name' => $item['name'], 'data' => $item['data']];

        if (!empty($others5Wave)) {
            $othersWaveData = array_fill(0, count($chartCategories), 0);
            foreach ($others5Wave as $item) {
                for($k=0; $k<count($chartCategories); $k++) $othersWaveData[$k] += $item['data'][$k];
            }
            $waveSeries[] = ['name' => 'Others (' . count($others5Wave) . ' Projects)', 'data' => $othersWaveData];
            $waveColors[] = '#CFD8DC';
        }

        $kpiData = [
            'total_documents' => $grandTotalDocuments,
            'document_project' => floor($grandTotalDocuments * 0.76),
            'document_fungsi' => floor($grandTotalDocuments * 0.24),
            'total_users' => 1240,
            'active_users_30d' => 202
        ];

        $lifecycleData = [
            ['phase' => '01. Initiation', 'count' => 119, 'active' => true, 'icon' => 'M4 6h16M4 12h16M4 18h7'],
            ['phase' => '02. Pre-FS', 'count' => 22, 'active' => true, 'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
            ['phase' => '03. Pre-FID/Early Work', 'count' => 16, 'active' => true, 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4'],
            ['phase' => '04. BED', 'count' => floor($grandTotalDocuments * 0.30), 'active' => false, 'icon' => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z'],
            ['phase' => '05. FEED', 'count' => floor($grandTotalDocuments * 0.20), 'active' => false, 'icon' => 'M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z'],
            ['phase' => '06. FS & FID', 'count' => 1, 'active' => false, 'icon' => 'M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z'],
            ['phase' => '07. EPC Bidding', 'count' => 27, 'active' => false, 'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z'],
            ['phase' => '08. EPC Work', 'count' => floor($grandTotalDocuments * 0.48), 'active' => false, 'icon' => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4'],
            ['phase' => '09. Operation', 'count' => 6, 'active' => false, 'icon' => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4'],
            ['phase' => '10. Closing', 'count' => 4, 'active' => false, 'icon' => 'M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4'],
        ];

        $phaseDocuments = [
            '01. Initiation' => [
                ['doc_name' => 'Project_Charter_RDMP_Balikpapan_Final.pdf', 'project' => 'RDMP RU V Balikpapan', 'uploader' => 'Andi Wijaya', 'date' => '18-Feb-2026', 'size' => '2.4 MB', 'type' => 'PDF'],
                ['doc_name' => 'MoM_Kickoff_Meeting_Tuban.pdf', 'project' => 'GRR Tuban', 'uploader' => 'Budi Santoso', 'date' => '15-Feb-2026', 'size' => '1.1 MB', 'type' => 'PDF'],
                ['doc_name' => 'Initial_Risk_Assessment_Cilacap.xlsx', 'project' => 'Biorefinery Cilacap', 'uploader' => 'Siti Nurhaliza', 'date' => '10-Feb-2026', 'size' => '845 KB', 'type' => 'XLSX'],
                ['doc_name' => 'Stakeholder_Mapping_Dumai.docx', 'project' => 'New DHT Dumai', 'uploader' => 'Rina Melati', 'date' => '08-Feb-2026', 'size' => '520 KB', 'type' => 'DOCX'],
                ['doc_name' => 'Business_Case_Approval_Form.pdf', 'project' => 'Olefin TPPI', 'uploader' => 'Hanif Naufal', 'date' => '05-Feb-2026', 'size' => '3.2 MB', 'type' => 'PDF'],
            ],
            '02. Pre-FS' => [
                ['doc_name' => 'Pre_Feasibility_Study_Report_Dumai_v2.pdf', 'project' => 'New DHT Dumai', 'uploader' => 'Dimas Pratama', 'date' => '17-Feb-2026', 'size' => '8.6 MB', 'type' => 'PDF'],
                ['doc_name' => 'Market_Analysis_Data_Tuban.xlsx', 'project' => 'GRR Tuban', 'uploader' => 'I Putu Borneo', 'date' => '12-Feb-2026', 'size' => '4.1 MB', 'type' => 'XLSX'],
                ['doc_name' => 'Site_Selection_Criteria.pptx', 'project' => 'Petrochemical Jawa Barat', 'uploader' => 'Citra Dewi', 'date' => '09-Feb-2026', 'size' => '12.5 MB', 'type' => 'PPTX'],
            ]
        ];

        $qaRecentDocs = [
            ['name' => 'Tes Kirim File Fungsi.pdf', 'type' => 'pdf', 'category' => 'Fungsi', 'security' => 'Public', 'date' => '04-Feb-2026 09:20:23'],
            ['name' => 'Mockup New Brain Dashboard.jpg', 'type' => 'image', 'category' => 'Fungsi', 'security' => 'Public', 'date' => '05-Feb-2026 10:38:10'],
            ['name' => 'Minutes_of_Meeting_VP.docx', 'type' => 'word', 'category' => 'Project', 'security' => 'Internal', 'date' => '17-Feb-2026 09:12:00']
        ];

        $qaRecentOpen = [
            ['name' => 'P&ID_RDMP_Balikpapan_v2.pdf', 'type' => 'pdf', 'category' => 'Project', 'security' => 'Internal', 'date' => '19-Feb-2026 08:15:00'],
            ['name' => 'Draft_Kontrak_EPC_Tuban.pdf', 'type' => 'pdf', 'category' => 'Project', 'security' => 'Restricted', 'date' => '18-Feb-2026 14:20:00']
        ];

        $qaConfidential = [
            ['name' => 'Board_Resolution_Q1_2026.pdf', 'type' => 'pdf', 'category' => 'Fungsi', 'security' => 'Confidential', 'date' => '10-Feb-2026 11:00:00'],
            ['name' => 'Financial_Audit_Report.xlsx', 'type' => 'excel', 'category' => 'Fungsi', 'security' => 'Confidential', 'date' => '01-Feb-2026 09:30:00']
        ];

        $qaHandover = [
            ['name' => 'As-Built_Drawing_Area_5.pdf', 'type' => 'pdf', 'category' => 'Project', 'status' => 'Synced to BRAIN', 'status_color' => 'bg-blue-50 text-blue-600 border-blue-200', 'date' => '19-Feb-2026 16:45:00'],
            ['name' => 'Final_HAZOP_Report.docx', 'type' => 'word', 'category' => 'Project', 'status' => 'Indexing', 'status_color' => 'bg-gray-100 text-gray-600 border-gray-300', 'date' => '19-Feb-2026 15:30:00']
        ];

        $aiImpact = [
            'total_queries' => '1,250',
            'documents_summarized' => '430',
            'growth' => '+18%'
        ];

        $trendingKeywords = [
            '#HAZOP_Balongan', '#Kontrak_EPC_Tuban', '#P&ID_Cilacap'
        ];

        $dummyFiles = [
            ['name' => 'Tes Kirim File Fungsi', 'type' => 'pdf', 'category' => 'Fungsi', 'security' => 'Public', 'date' => '04-Feb-2026 09:20:23'],
            ['name' => 'Mockup New Brain Dashboard', 'type' => 'image', 'category' => 'Fungsi', 'security' => 'Public', 'date' => '05-Feb-2026 10:38:10']
        ];

        return view('brain', compact(
            'kpiData', 'projectsData', 
            'fullBarNames', 'fullBarValues', 'fullBarColors',
            'barNames', 'barValues', 'barColors',
            'fullWaveSeries', 'fullWaveColors', 
            'waveSeries', 'waveColors',
            'lifecycleData', 'chartCategories', 'chartTooltipDates', 
            'filterYear', 'filterMonth', 'filterProject', 'dummyFiles', 
            'dynamicChartTitle', 'phaseDocuments',
            'qaRecentDocs', 'qaRecentOpen', 'qaConfidential', 'qaHandover',
            'aiImpact', 'trendingKeywords'
        ));
    }
}