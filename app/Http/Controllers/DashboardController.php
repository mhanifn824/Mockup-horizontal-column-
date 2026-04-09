<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class DashboardController extends Controller
{
    // =========================================================================
    // 1. DASHBOARD UTAMA
    // =========================================================================
    public function index(Request $request)
    {
        // 1. Filter Setup
        $filterYear = $request->input('year', 'CURRENT'); 
        $filterMonth = $request->input('month', 'ALL');     
        $filterProject = $request->input('project', 'ALL'); 

        // TIMELINE REFERENCE: APRIL 2026
        $currentDate = Carbon::create(2026, 4, 9); 
        $currentYear = 2026;
        $currentMonth = 4; 
        $effectiveYear = ($filterYear === 'CURRENT' || $filterYear === 'ALL') ? $currentYear : (int)$filterYear;

        // 2. Project Data
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

        // 3. X-Axis Generator
        $chartCategories = [];   
        $chartTooltipDates = []; 
        $dynamicChartTitle = "";

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
                    $chartTooltipDates[] = $dayStr . " " . $monthNameShort . " (2025-2026)";
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

        // 4. Data Generation Logic
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

                if ($filterMonth !== 'ALL') {
                    $day = $i + 1;
                    $generateDaily = function($yearToSim) use ($day, $filterMonth, $currentYear, $currentMonth, $currentDate, $isGiantProject) {
                        if ($yearToSim == $currentYear) {
                            if ($filterMonth > $currentMonth) return 0;
                            if ($filterMonth == $currentMonth && $day > $currentDate->day) return 0;
                        }
                        return $isGiantProject ? rand(300, 500) : rand(10, 50);
                    };

                    $val = ($filterYear === 'ALL') ? $generateDaily(2025) + $generateDaily(2026) : $generateDaily($effectiveYear);
                } else {
                    $month = $i + 1;
                    $generateMonthly = function($yearToSim) use ($month, $currentYear, $currentMonth, $isGiantProject) {
                        if ($yearToSim == $currentYear && $month > $currentMonth) return 0; 
                        return $isGiantProject ? rand(15000, 20000) : rand(800, 2500);
                    };

                    $val = ($filterYear === 'ALL') ? $generateMonthly(2025) + $generateMonthly(2026) : $generateMonthly($effectiveYear);
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

        // Sorting
        usort($barChartData, function($a, $b) { return $b['total'] <=> $a['total']; });
        $barNamesOrder = array_column($barChartData, 'name');
        usort($waveChartData, function($a, $b) use ($barNamesOrder) {
            $posA = array_search($a['name'], $barNamesOrder);
            $posB = array_search($b['name'], $barNamesOrder);
            return $posA <=> $posB;
        });

        // 5. Chart Arrays
        $top10Bar = array_slice($barChartData, 0, 10);
        $others10Bar = array_slice($barChartData, 10);
        $barNames = array_column($top10Bar, 'name');
        $barValues = array_column($top10Bar, 'total');
        $barColors = array_column($top10Bar, 'color');

        if (!empty($others10Bar)) {
            $sumOthers = array_sum(array_column($others10Bar, 'total'));
            $barNames[] = 'Others (' . count($others10Bar) . ' Projects)';
            $barValues[] = $sumOthers;
            $barColors[] = '#CFD8DC';
        }

        $fullBarNames = array_column($barChartData, 'name');
        $fullBarValues = array_column($barChartData, 'total');
        $fullBarColors = array_column($barChartData, 'color');

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

        $fullWaveSeries = [];
        foreach($waveChartData as $item) $fullWaveSeries[] = ['name' => $item['name'], 'data' => $item['data']];
        $fullWaveColors = array_column($waveChartData, 'color');

        // 6. Static Data & KPIs
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
                ['doc_name' => 'Project_Charter_RDMP_Balikpapan_Final.pdf', 'project' => 'RDMP RU V Balikpapan', 'uploader' => 'Andi Wijaya', 'date' => '08-Apr-2026', 'size' => '2.4 MB', 'type' => 'PDF'],
                ['doc_name' => 'MoM_Kickoff_Meeting_Tuban.pdf', 'project' => 'GRR Tuban', 'uploader' => 'Budi Santoso', 'date' => '05-Apr-2026', 'size' => '1.1 MB', 'type' => 'PDF'],
                ['doc_name' => 'Initial_Risk_Assessment_Cilacap.xlsx', 'project' => 'Biorefinery Cilacap', 'uploader' => 'Siti Nurhaliza', 'date' => '28-Mar-2026', 'size' => '845 KB', 'type' => 'XLSX'],
                ['doc_name' => 'Stakeholder_Mapping_Dumai.docx', 'project' => 'New DHT Dumai', 'uploader' => 'Rina Melati', 'date' => '20-Mar-2026', 'size' => '520 KB', 'type' => 'DOCX'],
                ['doc_name' => 'Business_Case_Approval_Form.pdf', 'project' => 'Olefin TPPI', 'uploader' => 'Hanif Naufal', 'date' => '15-Mar-2026', 'size' => '3.2 MB', 'type' => 'PDF'],
            ],
            '02. Pre-FS' => [
                ['doc_name' => 'Pre_Feasibility_Study_Report_Dumai.pdf', 'project' => 'New DHT Dumai', 'uploader' => 'Dimas Pratama', 'date' => '07-Apr-2026', 'size' => '8.6 MB', 'type' => 'PDF'],
                ['doc_name' => 'Market_Analysis_Data_Tuban.xlsx', 'project' => 'GRR Tuban', 'uploader' => 'I Putu Borneo', 'date' => '02-Apr-2026', 'size' => '4.1 MB', 'type' => 'XLSX'],
                ['doc_name' => 'Site_Selection_Criteria.pptx', 'project' => 'Petrochemical Jawa Barat', 'uploader' => 'Citra Dewi', 'date' => '25-Mar-2026', 'size' => '12.5 MB', 'type' => 'PPTX'],
                ['doc_name' => 'Environmental_Screening_Report.pdf', 'project' => 'PLBC Cilacap', 'uploader' => 'Ahmad Fauzi', 'date' => '20-Mar-2026', 'size' => '3.8 MB', 'type' => 'PDF'],
                ['doc_name' => 'Initial_Capex_Estimate.xlsx', 'project' => 'RDMP RU IV Cilacap', 'uploader' => 'Nadia Saphira', 'date' => '10-Mar-2026', 'size' => '1.2 MB', 'type' => 'XLSX'],
            ],
            '03. Pre-FID/Early Work' => [
                ['doc_name' => 'Land_Acquisition_Permit_Phase1.pdf', 'project' => 'GRR Tuban', 'uploader' => 'Bima Sakti', 'date' => '09-Apr-2026', 'size' => '5.5 MB', 'type' => 'PDF'],
                ['doc_name' => 'Early_Works_Contract_Agreement.docx', 'project' => 'RDMP RU V Early Works 1', 'uploader' => 'Siti Nurhaliza', 'date' => '01-Apr-2026', 'size' => '1.8 MB', 'type' => 'DOCX'],
                ['doc_name' => 'Soil_Investigation_Report.pdf', 'project' => 'New DHT Plaju', 'uploader' => 'Hendra Gunawan', 'date' => '28-Mar-2026', 'size' => '15.2 MB', 'type' => 'PDF'],
                ['doc_name' => 'Site_Preparation_Schedule.xlsx', 'project' => 'Relokasi SPM Balongan', 'uploader' => 'Putri Kusuma', 'date' => '15-Mar-2026', 'size' => '900 KB', 'type' => 'XLSX'],
                ['doc_name' => 'Local_Community_Socialization_MoM.pdf', 'project' => 'GRR Tuban', 'uploader' => 'Dewi Lestari', 'date' => '05-Mar-2026', 'size' => '2.1 MB', 'type' => 'PDF'],
            ],
            '04. BED' => [
                ['doc_name' => 'Basic_Engineering_Design_Data.pdf', 'project' => 'RDMP RU VI Balongan Phase I', 'uploader' => 'Hanif Naufal', 'date' => '08-Apr-2026', 'size' => '22.4 MB', 'type' => 'PDF'],
                ['doc_name' => 'Process_Flow_Diagram_Draft.pdf', 'project' => 'New Polypropylene Plant Balongan', 'uploader' => 'Andi Wijaya', 'date' => '05-Apr-2026', 'size' => '18.1 MB', 'type' => 'PDF'],
                ['doc_name' => 'Equipment_List_Preliminary.xlsx', 'project' => 'RDMP RU V ISBL - OSBL', 'uploader' => 'Dimas Pratama', 'date' => '01-Apr-2026', 'size' => '3.5 MB', 'type' => 'XLSX'],
                ['doc_name' => 'Utility_Consumption_Calculation.xlsx', 'project' => 'Green Refinery Plaju', 'uploader' => 'Rina Melati', 'date' => '20-Mar-2026', 'size' => '1.5 MB', 'type' => 'XLSX'],
                ['doc_name' => 'Licensor_Technology_Agreement.pdf', 'project' => 'Olefin TPPI', 'uploader' => 'Budi Santoso', 'date' => '12-Mar-2026', 'size' => '4.7 MB', 'type' => 'PDF'],
            ],
            '05. FEED' => [
                ['doc_name' => 'FEED_Executive_Summary_Rev1.pdf', 'project' => 'RDMP RU V Balikpapan Phase I', 'uploader' => 'Nadia Saphira', 'date' => '09-Apr-2026', 'size' => '14.3 MB', 'type' => 'PDF'],
                ['doc_name' => '3D_Model_Review_Report.pdf', 'project' => 'RFCC Cilacap', 'uploader' => 'Hendra Gunawan', 'date' => '04-Apr-2026', 'size' => '28.9 MB', 'type' => 'PDF'],
                ['doc_name' => 'Instrument_Index_Final.xlsx', 'project' => 'New EWTP Balongan', 'uploader' => 'Citra Dewi', 'date' => '29-Mar-2026', 'size' => '5.2 MB', 'type' => 'XLSX'],
                ['doc_name' => 'Hazard_and_Operability_HAZOP.pdf', 'project' => 'RDMP RU V Balikpapan Phase I', 'uploader' => 'Ahmad Fauzi', 'date' => '18-Mar-2026', 'size' => '11.5 MB', 'type' => 'PDF'],
                ['doc_name' => 'Electrical_Single_Line_Diagram.pdf', 'project' => 'Restorasi Tanki Balongan', 'uploader' => 'Bima Sakti', 'date' => '10-Mar-2026', 'size' => '8.4 MB', 'type' => 'PDF'],
            ],
        ];

        $qaRecentDocs = [
            ['name' => 'P&ID_RDMP_Balikpapan_v2.pdf', 'type' => 'pdf', 'category' => 'Project', 'security' => 'Internal', 'date' => '09-Apr-2026 08:15:00'],
            ['name' => 'MoM_Weekly_Progress.docx', 'type' => 'word', 'category' => 'Project', 'security' => 'Internal', 'date' => '08-Apr-2026 09:12:00'],
            ['name' => 'Mockup_New_Brain_Dashboard.jpg', 'type' => 'image', 'category' => 'Fungsi', 'security' => 'Public', 'date' => '07-Apr-2026 10:38:10'],
            ['name' => 'Design_Review_Notes_Tuban.pdf', 'type' => 'pdf', 'category' => 'Project', 'security' => 'Internal', 'date' => '05-Apr-2026 14:00:00']
        ];

        $qaRecentOpen = [
            ['name' => 'Draft_Kontrak_EPC_Tuban.pdf', 'type' => 'pdf', 'category' => 'Project', 'security' => 'Restricted', 'date' => '09-Apr-2026 14:20:00'],
            ['name' => 'Budget_Plan_Q2_2026.xlsx', 'type' => 'excel', 'category' => 'Fungsi', 'security' => 'Confidential', 'date' => '08-Apr-2026 11:15:00'],
            ['name' => 'Architecture_Blueprint_Phase1.pdf', 'type' => 'pdf', 'category' => 'Project', 'security' => 'Internal', 'date' => '06-Apr-2026 09:30:00'],
            ['name' => 'Vendor_List_Approved_2026.xlsx', 'type' => 'excel', 'category' => 'Fungsi', 'security' => 'Internal', 'date' => '05-Apr-2026 16:45:00']
        ];

        $qaConfidential = [
            ['name' => 'Board_Resolution_Q1_2026.pdf', 'type' => 'pdf', 'category' => 'Fungsi', 'security' => 'Confidential', 'date' => '05-Apr-2026 11:00:00'],
            ['name' => 'Financial_Audit_Report.xlsx', 'type' => 'excel', 'category' => 'Fungsi', 'security' => 'Confidential', 'date' => '01-Apr-2026 09:30:00'],
            ['name' => 'Bidding_Evaluation_Summary.docx', 'type' => 'word', 'category' => 'Project', 'security' => 'Confidential', 'date' => '28-Mar-2026 14:00:00'],
            ['name' => 'Risk_Register_Critical_Projects.pdf', 'type' => 'pdf', 'category' => 'Project', 'security' => 'Confidential', 'date' => '25-Mar-2026 10:15:00']
        ];

        $qaHandover = [
            ['name' => 'As-Built_Drawing_Area_5.pdf', 'type' => 'pdf', 'category' => 'Project', 'status' => 'Synced to BRAIN', 'status_color' => 'bg-blue-50 text-blue-600 border-blue-200', 'date' => '09-Apr-2026 16:45:00'],
            ['name' => 'Final_HAZOP_Report.docx', 'type' => 'word', 'category' => 'Project', 'status' => 'Indexing', 'status_color' => 'bg-gray-100 text-gray-600 border-gray-300', 'date' => '08-Apr-2026 15:30:00'],
            ['name' => 'Handover_Certificate_Unit_A.pdf', 'type' => 'pdf', 'category' => 'Project', 'status' => 'Verified', 'status_color' => 'bg-green-50 text-green-600 border-green-200', 'date' => '05-Apr-2026 11:00:00'],
            ['name' => 'Equipment_Data_Sheets_All.xlsx', 'type' => 'excel', 'category' => 'Project', 'status' => 'Synced to BRAIN', 'status_color' => 'bg-blue-50 text-blue-600 border-blue-200', 'date' => '02-Apr-2026 09:20:00']
        ];

        $aiImpact = [
            'total_queries' => '1,250',
            'documents_summarized' => '430',
            'growth' => '+18%'
        ];

        $trendingKeywords = [
            '#HAZOP_Balongan', '#Kontrak_EPC_Tuban', '#P&ID_Cilacap'
        ];

        return view('brain', compact(
            'kpiData', 'projectsData', 
            'fullBarNames', 'fullBarValues', 'fullBarColors',
            'barNames', 'barValues', 'barColors',
            'fullWaveSeries', 'fullWaveColors', 
            'waveSeries', 'waveColors',
            'lifecycleData', 'chartCategories', 'chartTooltipDates', 
            'filterYear', 'filterMonth', 'filterProject',
            'dynamicChartTitle', 'phaseDocuments',
            'qaRecentDocs', 'qaRecentOpen', 'qaConfidential', 'qaHandover',
            'aiImpact', 'trendingKeywords'
        ));
    }

    // =========================================================================
    // 2. SMART SEARCH ROUTE
    // =========================================================================
    public function smartSearch(Request $request)
    {
        $query = $request->input('q', '');
        $results = [];

        // Dummy search logic based on keyword (menggunakan dummy di blade yang anda kirim)
        if (!empty($query)) {
            $results = [
                ['title' => 'Project_Charter_RDMP_Balikpapan_Final.pdf', 'project' => 'RDMP RU V Balikpapan', 'category' => 'Project Document'],
                ['title' => 'MoM_Kickoff_Meeting_Tuban.pdf', 'project' => 'GRR Tuban', 'category' => 'Meeting Notes'],
                ['title' => 'Draft_Kontrak_EPC_Tuban.pdf', 'project' => 'GRR Tuban', 'category' => 'Legal Contract'],
            ];
        }

        return view('smart-search', compact('query', 'results'));
    }

    // =========================================================================
    // 3. AI CHAT ROUTE
    // =========================================================================
    public function chatAi()
    {
        // Parameter untuk membuat Chat AI terlihat mengerti dashboard
        $kpiData = [
            'total_documents' => 785420,
            'document_project' => 589065,
            'active_users' => 202
        ];
        
        return view('chat-ai', compact('kpiData'));
    }

    // =========================================================================
    // 4. DOCUMENT PREVIEW ROUTE
    // =========================================================================
    public function previewDocument(Request $request)
    {
        $docName = $request->input('doc', 'Unknown_Document.pdf');
        
        // Ekstrak Metadata (Dummy untuk tampilan Preview UI Anda)
        $ext = pathinfo($docName, PATHINFO_EXTENSION) ?: 'pdf';
        
        $metadata = [
            'title' => $docName,
            'type'  => strtoupper($ext),
            'size'  => rand(1, 20) . '.' . rand(1, 9) . ' MB',
            'date'  => Carbon::now()->subDays(rand(1, 10))->format('d-M-Y H:i:s'),
            'security' => str_contains(strtolower($docName), 'kontrak') || str_contains(strtolower($docName), 'audit') ? 'Confidential' : 'Internal'
        ];

        return view('preview', compact('metadata'));
    }
}