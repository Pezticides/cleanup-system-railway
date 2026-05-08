<?php

use App\Models\CleanUpReport;

test('report can be created', function () {
    $response = $this->post('/report', [
        'reporter_name' => 'John Doe',
        'location' => 'Park',
        'concern_type' => 'Litter',
        'description' => 'There is litter in the park.',
    ]);

    $response->assertRedirect('/');
    $this->assertDatabaseHas('clean_up_reports', [
        'reporter_name' => 'John Doe',
        'location' => 'Park',
        'concern_type' => 'Litter',
        'description' => 'There is litter in the park.',
        'status' => 'Pending',
    ]);
});

test('report creation requires required fields', function () {
    $response = $this->post('/report', []);

    $response->assertSessionHasErrors(['reporter_name', 'location', 'concern_type', 'description']);
});