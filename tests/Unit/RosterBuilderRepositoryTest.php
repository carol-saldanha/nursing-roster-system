<?php
namespace Tests\Unit;

use App\Models\Nurse;
use App\Models\Shift;
use App\Repositories\RosterBuilderRepository;
use Carbon\Carbon;
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
        $nurses = collect();
        $startDate = Carbon::parse('2022-01-01');
        $endDate = Carbon::parse('2022-01-31');

        // Act
        $result = RosterBuilderRepository::buildRoster($nurses, $startDate, $endDate);
    }
}
