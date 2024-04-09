<?php
declare(strict_types=1);

namespace App\src\Infrastructure\Http\Controllers;

use App\Http\Controllers\Controller;
use App\src\Application\Actions\CreatePointAction;
use App\src\Application\Actions\ListPointsAction;
use App\src\Application\DTO\PointDto;
use App\src\Infrastructure\Http\Requests\PointRequest;

class PointController extends Controller
{
    public function store(PointRequest $request, CreatePointAction $createPointAction)
    {
        $validatedData = $request->validated();
        $point = $createPointAction->execute(PointDto::fromRequest($validatedData));
        return response()->json($point, 201);
    }

    public function list(ListPointsAction $listPointsAction)
    {
        $points = $listPointsAction->execute();
        return response()->json($points, 201);
    }
}
