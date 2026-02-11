<?php

namespace App\Console\Commands;

use App\Models\AcademicEvent;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class FetchHolidaysCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'holidays:fetch {year?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch Indonesian holidays from API and save to academic events';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $year = $this->argument('year') ?? now()->year;
        
        $this->info("Fetching holidays for year {$year}...");
        
        try {
            $holidays = $this->fetchHolidaysFromAPI($year);
            
            if (empty($holidays)) {
                $this->warn('API not available, using manual data...');
                $holidays = $this->getManualHolidays($year);
                
                if (empty($holidays)) {
                    $this->warn('No holidays found for this year');
                    return 0;
                }
            }
            
            $this->info("Found " . count($holidays) . " holidays");
            
            $created = 0;
            $updated = 0;
            
            foreach ($holidays as $holiday) {
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
                    $this->line("↻ Updated: {$holiday['name']} ({$date->format('d M Y')})");
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
                    $this->line("✓ Created: {$holiday['name']} ({$date->format('d M Y')})");
                }
            }
            
            $this->newLine();
            $this->info("Summary:");
            $this->info("- Created: {$created}");
            $this->info("- Updated: {$updated}");
            $this->info("- Total processed: " . count($holidays));
            
            return 0;
            
        } catch (\Exception $e) {
            $this->error('Error: ' . $e->getMessage());
            return 1;
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
