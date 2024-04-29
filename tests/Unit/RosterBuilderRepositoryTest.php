<?php
namespace Tests\Unit;

use App\Models\Nurse;
use App\Models\Shift;
use App\Repositories\RosterBuilderRepository;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class RosterBuilderRepositoryTest extends TestCase
{
    public function testLoadNursesFromFile()
    {
        // Arrange
        Storage::fake('local');
        $filename = 'test.json';
        $nurses = ['Iskra', 'Andronicus', 'Tipene', 'Jaroslav'];
        Storage::put($filename, json_encode($nurses));

        // Act
        $result = RosterBuilderRepository::loadNursesFromFile($filename);

        // Assert
        $this->assertCount(4, $result);
        $this->assertInstanceOf(Nurse::class, $result->first());
        $this->assertEquals('Iskra', $result->first()->name);
    }

    public function testBuildRoster()
    {
        // Arrange
        $nurses = new Collection([
            new Nurse(['name' => 'Test Nurse 1']),
            new Nurse(['name' => 'Test Nurse 2']),
            new Nurse(['name' => 'Test Nurse 3']),
            new Nurse(['name' => 'Test Nurse 4']),
            new Nurse(['name' => 'Test Nurse 5']),
            new Nurse(['name' => 'Test Nurse 6']),
            new Nurse(['name' => 'Test Nurse 7']),
            new Nurse(['name' => 'Test Nurse 8']),
            new Nurse(['name' => 'Test Nurse 9']),
            new Nurse(['name' => 'Test Nurse 10']),
            new Nurse(['name' => 'Test Nurse 11']),
            new Nurse(['name' => 'Test Nurse 12']),
            new Nurse(['name' => 'Test Nurse 13']),
            new Nurse(['name' => 'Test Nurse 14']),
            new Nurse(['name' => 'Test Nurse 15'])      
        ]);

        $startDate = '2024-01-01';
        $endDate = '2024-01-06';

        // Act
        $roster = RosterBuilderRepository::buildRoster($nurses, Carbon::parse($startDate), Carbon::parse($endDate));

        // Check that each shift in the roster has exactly 5 nurses assigned
        $shiftsWithNurses = $roster->filter(function ($shift) {
            return $shift->nurses->count() === 5;
        });

        // Assert
        $this->assertInstanceOf(Collection::class, $roster);
        $this->assertEquals($roster->count(), $shiftsWithNurses->count());
    }

    public function testBuildRosterWithEmptyNursesCollection()
    {
        // Arrange
        $nurses = new Collection();
        $startDate = '2024-01-01';
        $endDate = '2024-01-10';

        // Act
        $roster = RosterBuilderRepository::buildRoster($nurses, Carbon::parse($startDate), Carbon::parse($endDate));

        // Assert
        // Check that the roster is empty when no nurses are provided
        $this->assertTrue($roster->isEmpty());
    }

}

