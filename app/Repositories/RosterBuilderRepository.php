<?php

namespace App\Repositories;

use App\Interfaces\RosterBuilderInterface;
use App\Models\Nurse;
use App\Models\Shift;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use App\Repositories\RosterFormatterRepository;

class RosterBuilderRepository implements RosterBuilderInterface
{
    public static function loadNursesFromFile(string $filename): Collection
    {
        // Check if file exists
        if (!Storage::exists($filename)) {
            throw new Exception("File {$filename} does not exist");
        }

        // Get the file content
        $content = Storage::get($filename);

        // Decode the JSON content to array
        $nurses = json_decode($content, true);

        // Map the array to Nurse objects
        $nurses = collect($nurses)->map(function ($nurseName) {
            return new Nurse(['name' => $nurseName]);
        });

        return $nurses;
    }

    public static function buildRoster(Collection $nurses, Carbon $startDate, Carbon $endDate): Collection
    {
        // Initialize the roster collection
        $roster = new Collection();
    
        // Initialize an array to keep track of allocated nurses for each day
        $allocatedNurses = [];
    
        // Iterate through each date from start date to end date
        $date = $startDate->copy(); // Clone the start date
    
        while ($date->lte($endDate)) { 
            // Array for type of shifts
            $shiftTypes = [Shift::SHIFT_TYPE_MORNING, Shift::SHIFT_TYPE_EVENING, Shift::SHIFT_TYPE_NIGHT];
    
            // Iterate through each shift type
            foreach ($shiftTypes as $type) {
                // Create a new Shift object for each shift type
                $shifts = new Shift();
                $shifts->date = $date->copy(); // Create a copy of the date for this shift
                $shifts->type = $type;
                $shifts->nurses = new Collection();
    
                // Assign 5 nurses to each shift
                $nursesCount = 0;
                while ($nursesCount < 5) {
                    // Randomly select a nurse
                    $selectedNurse = $nurses->random();
    
                    // Check if the nurse has already been allocated for this date
                    if (!isset($allocatedNurses[$date->format('Y-m-d')][$selectedNurse->name])) {
                        // Add the selected nurse to the shift's nurses collection
                        $shifts->nurses->push($selectedNurse);
    
                        // Mark the nurse as allocated for this date
                        $allocatedNurses[$date->format('Y-m-d')][$selectedNurse->name] = true;
    
                        $nursesCount++;
                    }
                }
    
                // Add the shift to the roster collection
                $roster->push($shifts); 
            }
    
            // Move to the next day
            $date->addDay();
        }
    
        // Save the updated allocated rosters to the output file outside the loop
        Storage::put("sample_data/roster.json", json_encode($roster));
    
        return $roster;
    }    

      
}