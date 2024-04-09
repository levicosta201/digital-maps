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

/**
 * @OA\Info(title="Api do Digital Maps", version="0.1")
 */
/**
 * @OA\Schema(
 *     schema="Point",
 *     required={"id", "name", "latitude", "longitude"},
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         format="int64",
 *         description="Identificador único do ponto"
 *     ),
 *     @OA\Property(
 *         property="name",
 *         type="string",
 *         description="Nome do ponto"
 *     ),
 *     @OA\Property(
 *         property="latitude",
 *         type="number",
 *         format="integer",
 *         description="Latitude do ponto"
 *     ),
 *     @OA\Property(
 *         property="longitude",
 *         type="number",
 *         format="integer",
 *         description="Longitude do ponto"
 *     )
 * )
 */
class PointController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/points/near/{latitude}/{longitude}/{radius}/{time}",
     *     summary="Busca pontos próximos a uma localização geográfica",
     *     description="Retorna uma lista de pontos próximos à localização especificada, dentro do raio fornecido e considerando o horário.",
     *     tags={"Points"},
     *     @OA\Parameter(
     *         name="latitude",
     *         in="path",
     *         description="Latitude da localização central para a busca.",
     *         required=true,
     *         @OA\Schema(
     *             type="number",
     *             format="float"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="longitude",
     *         in="path",
     *         description="Longitude da localização central para a busca.",
     *         required=true,
     *         @OA\Schema(
     *             type="number",
     *             format="float"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="radius",
     *         in="path",
     *         description="O raio em torno da localização central, dentro do qual os pontos serão buscados.",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="time",
     *         in="path",
     *         description="Horário especificado para filtragem, no formato HH:MM.",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *             pattern="^\d{2}:\d{2}$"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Uma lista de pontos encontrados.",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="success",
     *                 type="boolean",
     *                 example=true
     *             ),
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Points found successfully"
     *             ),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/PointDetail")
     *             )
     *         )
     *     )
     * )
     *
     * @OA\Schema(
     *     schema="PointDetail",
     *     type="object",
     *     @OA\Property(
     *         property="id",
     *         type="integer",
     *         format="int64"
     *     ),
     *     @OA\Property(
     *         property="uuid",
     *         type="string",
     *         format="uuid"
     *     ),
     *     @OA\Property(
     *         property="name",
     *         type="string"
     *     ),
     *     @OA\Property(
     *         property="latitude",
     *         type="number",
     *         format="float"
     *     ),
     *     @OA\Property(
     *         property="longitude",
     *         type="number",
     *         format="float"
     *     ),
     *     @OA\Property(
     *         property="open_hour",
     *         type="string",
     *         nullable=true
     *     ),
     *     @OA\Property(
     *         property="close_hour",
     *         type="string",
     *         nullable=true
     *     ),
     *     @OA\Property(
     *         property="created_at",
     *         type="string",
     *         format="date-time"
     *     ),
     *     @OA\Property(
     *         property="updated_at",
     *         type="string",
     *         format="date-time"
     *     )
     * )
     */
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
