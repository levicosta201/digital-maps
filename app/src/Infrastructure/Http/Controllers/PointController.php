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
     * @OA\Post(
     *     path="/api/points",
     *     summary="Cria um novo ponto",
     *     description="Cria um novo ponto com o nome, latitude e longitude fornecidos.",
     *     tags={"Points"},
     *     @OA\RequestBody(
     *         description="Dados necessários para criar um novo ponto",
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name","latitude","longitude"},
     *             @OA\Property(
     *                 property="name",
     *                 type="string",
     *                 description="Nome do ponto"
     *             ),
     *             @OA\Property(
     *                 property="latitude",
     *                 type="number",
     *                 format="float",
     *                 description="Latitude do ponto"
     *             ),
     *             @OA\Property(
     *                 property="longitude",
     *                 type="number",
     *                 format="float",
     *                 description="Longitude do ponto"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Ponto criado com sucesso.",
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
     *                 example="Point created successfully"
     *             ),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(
     *                     property="point",
     *                     type="object",
     *                     @OA\Property(
     *                         property="uuid",
     *                         type="string",
     *                         format="uuid",
     *                         description="UUID único do ponto criado",
     *                         example="bb06c005-7f51-4761-b74f-32414a7d417b"
     *                     )
     *                 )
     *             )
     *         )
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

    /**
     * @OA\Get(
     *     path="/api/points",
     *     summary="Lista todos os pontos",
     *     description="Retorna uma lista completa de todos os pontos disponíveis.",
     *     tags={"Points"},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de pontos obtida com sucesso.",
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
     *                 @OA\Items(ref="#/components/schemas/PointDetailList")
     *             )
     *         )
     *     )
     * )
     *
     * @OA\Schema(
     *     schema="PointDetailList",
     *     type="object",
     *     @OA\Property(
     *         property="id",
     *         type="integer",
     *         format="int64",
     *         description="Identificador único do ponto"
     *     ),
     *     @OA\Property(
     *         property="uuid",
     *         type="string",
     *         format="uuid",
     *         description="UUID único do ponto"
     *     ),
     *     @OA\Property(
     *         property="name",
     *         type="string",
     *         description="Nome do ponto"
     *     ),
     *     @OA\Property(
     *         property="latitude",
     *         type="number",
     *         format="float",
     *         description="Latitude do ponto"
     *     ),
     *     @OA\Property(
     *         property="longitude",
     *         type="number",
     *         format="float",
     *         description="Longitude do ponto"
     *     ),
     *     @OA\Property(
     *         property="open_hour",
     *         type="string",
     *         nullable=true,
     *         description="Hora de abertura do ponto, se aplicável"
     *     ),
     *     @OA\Property(
     *         property="close_hour",
     *         type="string",
     *         nullable=true,
     *         description="Hora de fechamento do ponto, se aplicável"
     *     ),
     *     @OA\Property(
     *         property="created_at",
     *         type="string",
     *         format="date-time",
     *         description="Data e hora da criação do ponto"
     *     ),
     *     @OA\Property(
     *         property="updated_at",
     *         type="string",
     *         format="date-time",
     *         description="Data e hora da última atualização do ponto"
     *     )
     * )
     */
    public function list(ListPointsAction $listPointsAction): JsonResponse
    {
        $points = $listPointsAction->execute();
        return response()->json([
            'success' => true,
            'message' => 'Points found successfully',
            'data' => $points,
        ], 201);
    }

    /**
     * @OA\Put(
     *     path="/api/points/{uuid}",
     *     summary="Atualiza um ponto existente",
     *     description="Atualiza um ponto existente com o UUID fornecido com os novos valores fornecidos para nome, latitude, longitude, hora de abertura e fechamento.",
     *     tags={"Points"},
     *     @OA\Parameter(
     *         name="uuid",
     *         in="path",
     *         required=true,
     *         description="UUID do ponto a ser atualizado",
     *         @OA\Schema(
     *             type="string",
     *             format="uuid"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         description="Dados para atualizar o ponto",
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name","latitude","longitude","open_hour","close_hour"},
     *             @OA\Property(
     *                 property="name",
     *                 type="string",
     *                 description="Nome do ponto"
     *             ),
     *             @OA\Property(
     *                 property="latitude",
     *                 type="number",
     *                 format="float",
     *                 description="Latitude do ponto"
     *             ),
     *             @OA\Property(
     *                 property="longitude",
     *                 type="number",
     *                 format="float",
     *                 description="Longitude do ponto"
     *             ),
     *             @OA\Property(
     *                 property="open_hour",
     *                 type="string",
     *                 description="Hora de abertura do ponto",
     *                 example="10:00"
     *             ),
     *             @OA\Property(
     *                 property="close_hour",
     *                 type="string",
     *                 description="Hora de fechamento do ponto",
     *                 example="19:00"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Ponto atualizado com sucesso.",
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
     *                 example="Point updated successfully"
     *             ),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(
     *                     property="point",
     *                     type="object",
     *                     @OA\Property(
     *                         property="uuid",
     *                         type="string",
     *                         format="uuid",
     *                         description="UUID do ponto atualizado",
     *                         example="6ebbc829-1390-402f-8e47-d149cc5ca424"
     *                     )
     *                 )
     *             )
     *         )
     *     )
     * )
     */
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

    /**
     * @OA\Delete(
     *     path="/api/points/{uuid}",
     *     summary="Exclui um ponto específico",
     *     description="Exclui um ponto com o UUID fornecido.",
     *     tags={"Points"},
     *     @OA\Parameter(
     *         name="uuid",
     *         in="path",
     *         required=true,
     *         description="UUID do ponto a ser excluído",
     *         @OA\Schema(
     *             type="string",
     *             format="uuid"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Ponto excluído com sucesso.",
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
     *                 example="Point deleted successfully"
     *             ),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 description="Um array vazio, indicando que nenhum dado adicional é retornado.",
     *                 example={}
     *             )
     *         )
     *     )
     * )
     */
    public function delete(string $uuid, DeletePointAction $deletePointAction): JsonResponse
    {
        $deletePointAction->execute($uuid);
        return response()->json([
            'success' => true,
            'message' => 'Point deleted successfully',
            'data' => []
        ]);
    }

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
     *         property="uuid",
     *         type="string",
     *         format="uuid",
     *         description="UUID único do ponto"
     *     ),
     *     @OA\Property(
     *         property="name",
     *         type="string",
     *         description="Nome do ponto"
     *     ),
     *     @OA\Property(
     *         property="latitude",
     *         type="number",
     *         format="float",
     *         description="Latitude do ponto"
     *     ),
     *     @OA\Property(
     *         property="longitude",
     *         type="number",
     *         format="float",
     *         description="Longitude do ponto"
     *     ),
     *     @OA\Property(
     *         property="open_hour",
     *         type="string",
     *         nullable=true,
     *         description="Hora de abertura do ponto, se aplicável"
     *     ),
     *     @OA\Property(
     *         property="close_hour",
     *         type="string",
     *         nullable=true,
     *         description="Hora de fechamento do ponto, se aplicável"
     *     ),
     *     @OA\Property(
     *         property="isClosed",
     *         type="integer",
     *         description="Indica se o ponto está fechado (0 para aberto, 1 para fechado)",
     *         example=0
     *     )
     * )
     */
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
