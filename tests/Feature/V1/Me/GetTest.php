<?php

//phpcs:disable PSR1.Methods.CamelCapsMethodName.NotCamelCaps

namespace Tests\Feature\V1\Me;

use Tagd\Core\Tests\Traits\NeedsConsumers;

class GetTest extends Base
{
    use NeedsConsumers;

    /**
     * GET /status
     *
     * @return void
     */
    public function test_me_get_request()
    {
        $consumer = $this->aConsumer();

        $response = $this
            ->actingAsAConsumer($consumer)
            ->get(static::URL_ME)
            ->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'data' => [
                    'id',
                    'email',
                    'name',
                    'actors',
                ],
            ]);
    }

    /**
     * GET /status
     *
     * @return void
     */
    public function test_me_get_request_noauth()
    {
        $response = $this
            ->get(static::URL_ME)
            ->assertStatus(403);
    }
}
