<?php

namespace App\Http\Controllers;

use App\Models\AcademicEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Artisan;
use Carbon\Carbon;

class AcademicCalendarController extends Controller
{
    public function index(Request $request)
    {
        $year = $request->get('year', now()->year);
        $month = $request->get('month', now()->month);

        $currentDate = Carbon::create($year, $month, 1);
        $events = AcademicEvent::getEventsForMonth($year, $month);
        $upcomingEvents = AcademicEvent::getUpcomingEvents(5);

        // Generate calendar data
        $startOfMonth = $currentDate->copy()->startOfMonth();
        $endOfMonth = $currentDate->copy()->endOfMonth();
        $startOfCalendar = $startOfMonth->copy()->startOfWeek();
        $endOfCalendar = $endOfMonth->copy()->endOfWeek();

        $calendarDays = [];
        $currentDay = $startOfCalendar->copy();

        while ($currentDay <= $endOfCalendar) {
            $calendarDays[] = [
                'date' => $currentDay->copy(),
                'isCurrentMonth' => $currentDay->month == $month,
                'isToday' => $currentDay->isToday(),
                'events' => $events->filter(function ($event) use ($currentDay) {
                    return $currentDay->between($event->start_date, $event->end_date);
                }),
            ];
            $currentDay->addDay();
        }

        // Check if user is admin
        $isAdmin = auth()->user()->isAdmin ?? false;
        $view = $isAdmin ? 'admin.academic-calendar' : 'academic-calendar.index';

        return view($view, compact(
            'calendarDays',
            'events',
            'upcomingEvents',
            'currentDate',
            'year',
            'month'
        ));
    }

    public function syncHolidays(Request $request)
    {
        $year = $request->get('year', now()->year);
        
        try {
            $holidays = $this->fetchHolidaysFromAPI($year);
            
            if (empty($holidays)) {
                // Fallback to manual data if API fails
                $holidays = $this->getManualHolidays($year);
                
                if (empty($holidays)) {
                    return redirect()->back()->with('info', 'Tidak ada data hari libur untuk tahun ' . $year);
                }
            }
            
            $created = 0;
            $updated = 0;
            
            foreach ($holidays as $holiday) {
                // Parse date
                $date = Carbon::parse($holiday['date']);
                
                // Check if event already exists
                $existingEvent = AcademicEvent::where('start_date', $date->format('Y-m-d'))
                    ->where('type', 'holiday')
                    ->first();
                
                if ($existingEvent) {
                    // Update existing event
                    $existingEvent->update([
                        'title' => $holiday['name'],
                        'description' => $holiday['description'] ?? 'Hari libur nasional',
                        'is_active' => true,
                    ]);
                    $updated++;
                } else {
                    // Create new event
                    AcademicEvent::create([
                        'title' => $holiday['name'],
                        'description' => $holiday['description'] ?? 'Hari libur nasional',
                        'start_date' => $date,
                        'end_date' => $date,
                        'type' => 'holiday',
                        'color' => '#ef4444', // red color for holidays
                        'is_active' => true,
                    ]);
                    $created++;
                }
            }
            
            $message = "Berhasil sinkronisasi hari libur tahun {$year}. ";
            $message .= "Ditambahkan: {$created}, Diperbarui: {$updated}";
            
            return redirect()->back()->with('success', $message);
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal sinkronisasi: ' . $e->getMessage());
        }
    }

    /**
     * Fetch holidays from API with fallback
     */
    private function fetchHolidaysFromAPI($year)
    {
        $apis = [
            'https://api-harilibur.vercel.app/api',
            'https://dayoffapi.vercel.app/api',
        ];
        
        foreach ($apis as $apiUrl) {
            try {
                $response = Http::timeout(10)->get($apiUrl, ['year' => $year]);
                
                if ($response->successful()) {
                    $data = $response->json();
                    
                    if (isset($data['holidays']) && !empty($data['holidays'])) {
                        return array_map(function($holiday) {
                            return [
                                'date' => $holiday['holiday_date'],
                                'name' => $holiday['holiday_name'],
                                'description' => 'Hari libur nasional'
                            ];
                        }, $data['holidays']);
                    }
                }
            } catch (\Exception $e) {
                // Try next API
                continue;
            }
        }
        
        return [];
    }

    /**
     * Get manual holidays data as fallback
     */
    private function getManualHolidays($year)
    {
        // Manual data for 2026 (update this annually)
        $manualData = [
            2026 => [
                ['date' => '2026-01-01', 'name' => 'Tahun Baru Masehi', 'description' => 'Hari libur nasional'],
                ['date' => '2026-02-17', 'name' => 'Isra Mikraj Nabi Muhammad SAW', 'description' => 'Hari libur nasional'],
                ['date' => '2026-03-03', 'name' => 'Tahun Baru Imlek', 'description' => 'Hari libur nasional'],
                ['date' => '2026-03-22', 'name' => 'Hari Suci Nyepi', 'description' => 'Hari libur nasional'],
                ['date' => '2026-03-31', 'name' => 'Idul Fitri', 'description' => 'Hari libur nasional'],
                ['date' => '2026-04-01', 'name' => 'Idul Fitri', 'description' => 'Hari libur nasional'],
                ['date' => '2026-04-03', 'name' => 'Wafat Isa Almasih', 'description' => 'Hari libur nasional'],
                ['date' => '2026-05-01', 'name' => 'Hari Buruh Internasional', 'description' => 'Hari libur nasional'],
                ['date' => '2026-05-14', 'name' => 'Kenaikan Isa Almasih', 'description' => 'Hari libur nasional'],
                ['date' => '2026-06-01', 'name' => 'Hari Lahir Pancasila', 'description' => 'Hari libur nasional'],
                ['date' => '2026-06-07', 'name' => 'Idul Adha', 'description' => 'Hari libur nasional'],
                ['date' => '2026-06-28', 'name' => 'Tahun Baru Islam', 'description' => 'Hari libur nasional'],
                ['date' => '2026-08-17', 'name' => 'Hari Kemerdekaan RI', 'description' => 'Hari libur nasional'],
                ['date' => '2026-09-06', 'name' => 'Maulid Nabi Muhammad SAW', 'description' => 'Hari libur nasional'],
                ['date' => '2026-12-25', 'name' => 'Hari Raya Natal', 'description' => 'Hari libur nasional'],
            ],
        ];
        
        return $manualData[$year] ?? [];
    }
}
