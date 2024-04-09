<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\Validator;
use App\src\Infrastructure\Http\Requests\PointRequest;

class PointRequestTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    /**
     * Testa se a request está sempre autorizada.
     *
     * @return void
     */
    public function testRequestAuthorized()
    {
        $request = new PointRequest();

        $this->assertTrue($request->authorize());
    }

    /**
     * Testa as regras de validação da request.
     *
     * @return void
     */
    public function testRulesValidation()
    {
        $rules = (new PointRequest())->rules();

        $validator = Validator::make([
            'name' => 'Test Name',
            'latitude' => 15,
            'longitude' => 30,
            'open_hour' => '08:00',
            'close_hour' => '18:00',
        ], $rules);

        $this->assertFalse($validator->fails());

        $validator = Validator::make([
            'latitude' => 'not an integer', // invalid latitude
        ], $rules);

        $this->assertTrue($validator->fails());
    }
}
