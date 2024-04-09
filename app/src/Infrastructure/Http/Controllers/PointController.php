<?php
declare(strict_types=1);

namespace App\src\Infrastructure\Http\Controllers;

use App\Http\Controllers\Controller;
use App\src\Application\Actions\CreatePointAction;
use App\src\Application\Actions\DeletePointAction;
use App\src\Application\Actions\ListNearPointsAction;
use App\src\Application\Actions\ListPointsAction;
use App\src\Application\Actions\UpdatePointAction;
use App\src\Application\DTO\PointDto;
use App\src\Infrastructure\Http\Requests\PointRequest;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class PointController extends Controller
{
    public function store(PointRequest $request, CreatePointAction $createPointAction): JsonResponse
    {
        $validatedData = $request->validated();
        $point = $createPointAction->execute(PointDto::fromRequest($validatedData));
        return response()->json([
            'success' => true,
            'message' => 'Point created successfully',
            'data' => [
                'point' => [
                    'uuid' => $point->uuid,
                ]
            ]
        ]);
    }

    public function list(ListPointsAction $listPointsAction): JsonResponse
    {
        $points = $listPointsAction->execute();
        return response()->json($points, 201);
    }

    public function update(
        string $uuid,
        PointRequest $request,
        UpdatePointAction $updatePointAction
    ): JsonResponse {
        $validatedData = $request->validated();
        $validatedData['uuid'] = $uuid;
        $point = $updatePointAction->execute(PointDto::fromArray($validatedData));
        return response()->json([
            'success' => true,
            'message' => 'Point updated successfully',
            'data' => [
                'point' => [
                    'uuid' => $point->uuid,
                ]
            ]
        ]);
    }

    public function delete(string $uuid, DeletePointAction $deletePointAction): JsonResponse
    {
        $deletePointAction->execute($uuid);
        return response()->json([
            'success' => true,
            'message' => 'Point deleted successfully',
            'data' => []
        ]);
    }

    public function near(
        int $latitude,
        int $longitude,
        int $distance,
        string $hour,
        ListNearPointsAction $listNearPointsAction
    ): JsonResponse {
        $points = $listNearPointsAction->execute($latitude, $longitude, $distance, $hour);
        return response()->json([
            'success' => true,
            'message' => 'Points found successfully',
            'data' => $points,
        ], 201);
    }
}
