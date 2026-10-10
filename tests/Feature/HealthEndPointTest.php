<?php

test('health endpoint returns the expected API response', function (): void {
    $response = $this->getJson('/api/v1/health');

    $response
        ->assertOk()
        ->assertJsonPath('success', true)
        ->assertJsonPath('message', 'StreamHub API is running')
        ->assertJsonPath('data.version', 'v1');
});
