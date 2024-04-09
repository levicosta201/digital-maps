<?php
namespace Tests\Unit\src\Domain\Services;

use App\src\Application\DTO\PointDto;
use App\src\Domain\Cache\CacheInterface;
use App\src\Domain\Repositories\PointRepositoryInterface;
use App\src\Domain\Services\PointsService;
use Mockery;
use Tests\TestCase;

class PointsServiceTest extends TestCase
{
    protected PointRepositoryInterface $pointRepository;
    protected CacheInterface $cache;

    protected function setUp(): void
    {
        parent::setUp(); // TODO: Change the autogenerated stub
        $this->pointRepository = Mockery::mock(PointRepositoryInterface::class);
        $this->pointRepository
            ->shouldReceive('create')
            ->andReturn(PointDto::fromArray([
                'uuid' => 'mock-mock-mock-mock',
                'name' => 'test',
                'latitude' => 15,
                'longitude' => 30,
                'open_hour' => '08:17',
                'close_hour' => '18:21',
            ]));

        $this->cache = Mockery::mock(CacheInterface::class);
        $this->cache
            ->shouldReceive('delete')
            ->andReturn(true);
        $this->cache
            ->shouldReceive('get')
            ->andReturn(null);
    }

    public function testCreateSuccess()
    {
        $pointDto = PointDto::fromArray([
            'uuid' => 'mock-mock-mock-mock',
            'name' => 'test',
            'latitude' => 15,
            'longitude' => 30,
            'open_hour' => '08:17',
            'close_hour' => '18:21',
        ]);

        $pointsService = new PointsService($this->pointRepository, $this->cache);
        $point = $pointsService->create($pointDto);

        $this->assertEquals('mock-mock-mock-mock', $point->uuid);
    }

    public function testCreateException()
    {
        $pointDto = PointDto::fromArray([
            'uuid' => 'mock-mock-mock-mock',
            'name' => 'test',
            'latitude' => 15,
            'longitude' => 30,
            'open_hour' => '08:17',
            'close_hour' => '18:21',
        ]);

        $pointRepository = Mockery::mock(PointRepositoryInterface::class);
        $pointRepository
            ->shouldReceive('create')
            ->andThrow(new \Exception('Error'));

        $pointsService = new PointsService($pointRepository, $this->cache);

        $this->expectException(\Exception::class);
        $pointsService->create($pointDto);
    }

    public function testGetPointSuccess()
    {
        $points = [
            PointDto::fromArray([
                'uuid' => 'mock-mock-mock-mock',
                'name' => 'test',
                'latitude' => 15,
                'longitude' => 30,
                'open_hour' => '08:17',
                'close_hour' => '18:21',
            ]),
            PointDto::fromArray([
                'uuid' => 'mock-mock-mock-mock',
                'name' => 'test',
                'latitude' => 15,
                'longitude' => 30,
                'open_hour' => '08:17',
                'close_hour' => '18:21',
            ]),
        ];

        $this->pointRepository
            ->shouldReceive('all')
            ->andReturn($points);

        $this->cache
            ->shouldReceive('set')
            ->andReturn(true);

        $pointsService = new PointsService($this->pointRepository, $this->cache);
        $point = $pointsService->getPoints();

        $this->assertEquals(2, count($point));
    }

    public function testGetPointsException()
    {
        $points = [
            PointDto::fromArray([
                'uuid' => 'mock-mock-mock-mock',
                'name' => 'test',
                'latitude' => 15,
                'longitude' => 30,
                'open_hour' => '08:17',
                'close_hour' => '18:21',
            ]),
            PointDto::fromArray([
                'uuid' => 'mock-mock-mock-mock',
                'name' => 'test',
                'latitude' => 15,
                'longitude' => 30,
                'open_hour' => '08:17',
                'close_hour' => '18:21',
            ]),
        ];
        $pointRepository = Mockery::mock(PointRepositoryInterface::class);
        $pointRepository
            ->shouldReceive('all')
            ->andThrow(new \Exception('Error'));

        $cache = Mockery::mock(CacheInterface::class);
        $cache
            ->shouldReceive('get')
            ->andReturn($points);

        $pointsService = new PointsService($pointRepository, $cache);
        $pointsResponse = $pointsService->getPoints();

        $this->assertIsArray($pointsResponse);
    }

    public function testGetPointsFromCache()
    {
        $pointRepository = Mockery::mock(PointRepositoryInterface::class);
        $pointRepository
            ->shouldReceive('all')
            ->andThrow(new \Exception('Error'));

        $cache = Mockery::mock(CacheInterface::class);
        $cache
            ->shouldReceive('get')
            ->andReturn([]);

        $pointsService = new PointsService($pointRepository, $cache);
        $pointsResponse = $pointsService->getPoints();

        $this->assertIsArray($pointsResponse);
        $this->assertEquals([], $pointsResponse);
    }

    /**
     * @throws \Exception
     */
    public function testUpdatePointSuccess()
    {
        $pointDto = PointDto::fromArray([
            'uuid' => 'mock-mock-mock-mock',
            'name' => 'test',
            'latitude' => 15,
            'longitude' => 30,
            'open_hour' => '08:17',
            'close_hour' => '18:21',
        ]);

        $this->pointRepository
            ->shouldReceive('update')
            ->andReturn(1);

        $pointsService = new PointsService($this->pointRepository, $this->cache);
        $point = $pointsService->update($pointDto);

        $this->assertEquals('mock-mock-mock-mock', $point->uuid);
    }

    public function testUpdatePointNotFound()
    {
        $pointDto = PointDto::fromArray([
            'uuid' => 'mock-mock-mock-mock',
            'name' => 'test',
            'latitude' => 15,
            'longitude' => 30,
            'open_hour' => '08:17',
            'close_hour' => '18:21',
        ]);

        $this->pointRepository
            ->shouldReceive('update')
            ->andReturn(0);

        $this->expectException(\Exception::class);
        $pointsService = new PointsService($this->pointRepository, $this->cache);
        $point = $pointsService->update($pointDto);
    }

    public function testDeletePointSuccess()
    {
        $mockUuid = 'mock-mock-mock-mock';

        $this->pointRepository
            ->shouldReceive('delete')
            ->andReturn(1);

        $pointsService = new PointsService($this->pointRepository, $this->cache);
        $point = $pointsService->delete($mockUuid);

        $this->assertTrue($point);
    }

    public function testDeletePointException()
    {
        $mockUuid = 'mock-mock-mock-mock';

        $this->pointRepository
            ->shouldReceive('delete')
            ->andThrow(new \Exception('Falha Mockada de DB'));

        $this->expectException(\Exception::class);
        $pointsService = new PointsService($this->pointRepository, $this->cache);
        $point = $pointsService->delete($mockUuid);

        $this->assertTrue($point);
    }

    public function testGetNearSuccess()
    {
        $points = [
            [
                'uuid' => 'mock-mock-mock-mock',
                'name' => 'test',
                'latitude' => 15,
                'longitude' => 30,
                'open_hour' => '08:17:00',
                'close_hour' => '18:21:00',
            ],
            [
                'uuid' => 'mock-mock-mock-mock',
                'name' => 'test',
                'latitude' => 15,
                'longitude' => 30,
                'open_hour' => null,
                'close_hour' => null,
            ],
        ];

        $this->cache
            ->shouldReceive('set')
            ->andReturn(true);

        $this->pointRepository
            ->shouldReceive('getNear')
            ->andReturn($points);

        $pointsService = new PointsService($this->pointRepository, $this->cache);
        $point = $pointsService->getNear(15, 30, 2, '08:22');

        $this->assertEquals(2, count($point));
    }

    public function testGetNearSuccessNotInRangeHour()
    {
        $points = [
            [
                'uuid' => 'mock-mock-mock-mock',
                'name' => 'test',
                'latitude' => 15,
                'longitude' => 30,
                'open_hour' => '08:17:00',
                'close_hour' => '18:21:00',
            ],
            [
                'uuid' => 'mock-mock-mock-mock',
                'name' => 'test',
                'latitude' => 15,
                'longitude' => 30,
                'open_hour' => null,
                'close_hour' => null,
            ],
        ];

        $this->cache
            ->shouldReceive('set')
            ->andReturn(true);

        $this->pointRepository
            ->shouldReceive('getNear')
            ->andReturn($points);

        $pointsService = new PointsService($this->pointRepository, $this->cache);
        $point = $pointsService->getNear(15, 30, 2, '19:22');

        $this->assertEquals(2, count($point));
    }

    public function testGetNearSuccessCachedData()
    {
        $points = [
            [
                'uuid' => 'mock-mock-mock-mock',
                'name' => 'test',
                'latitude' => 15,
                'longitude' => 30,
                'open_hour' => '08:17:00',
                'close_hour' => '18:21:00',
            ],
            [
                'uuid' => 'mock-mock-mock-mock',
                'name' => 'test',
                'latitude' => 15,
                'longitude' => 30,
                'open_hour' => null,
                'close_hour' => null,
            ]
        ];

        $cache = Mockery::mock(CacheInterface::class);
        $cache
            ->shouldReceive('get')
            ->andReturn($points);

        $this->pointRepository
            ->shouldReceive('getNear')
            ->andReturn($points);

        $pointsService = new PointsService($this->pointRepository, $cache);
        $point = $pointsService->getNear(15, 30, 2, '08:22');

        $this->assertEquals(2, count($point));
    }

    public function testGetNearExceptionWithoutCachedData()
    {
        $points = null;

        $cache = Mockery::mock(CacheInterface::class);
        $cache
            ->shouldReceive('get')
            ->andReturn($points);

        $this->pointRepository
            ->shouldReceive('getNear')
            ->andThrow(new \Exception('Error DB Mocked'));

        $this->expectException(\Exception::class);
        $pointsService = new PointsService($this->pointRepository, $cache);
        $point = $pointsService->getNear(15, 30, 2, '08:22');
    }
}
