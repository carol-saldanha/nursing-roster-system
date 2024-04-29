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
        $nurses = new Collection([
            new Nurse(['name' => 'Test Nurse 1']),
            new Nurse(['name' => 'Test Nurse 2'])
        ]);

        $startDate = '2024-01-01';
        $endDate = '2024-01-02';

        $roster = RosterBuilderRepository::buildRoster($nurses, Carbon::parse($startDate), Carbon::parse($endDate));

        $this->assertInstanceOf(Collection::class, $roster);
    }


}

