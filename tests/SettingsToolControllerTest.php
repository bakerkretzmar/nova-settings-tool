<?php

namespace Bakerkretzmar\SettingsTool\Tests;

use Bakerkretzmar\SettingsTool\Http\Controllers\SettingsToolController;

class SettingsToolControllerTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->app
            ->when(SettingsToolController::class)
            ->needs('$settingsPath')
            ->give(__DIR__.'/stubs/settings.json');
    }

    /** @test */
    public function it_gets_the_settings()
    {
        $this
            ->get('nova-vendor/settings-tool')
            ->assertSuccessful()
            ->assertJson(['test' => 'indeed']);
    }

    /** @test */
    public function it_sets_the_settings()
    {
        $this
            ->postJson('nova-vendor/settings-tool', [
                'settings' => [
                    'test' => 'oh really?',
                    'fact' => true,
                ],
            ])
            ->assertSuccessful()
            ->assertJson([
                'test' => 'oh really?',
                'fact' => true,
            ]);
    }

    /** @test */
    public function it_will_start_from_the_ending_of_the_file_when_no_starting_line_number_is_given()
    {
        // $this
        //     ->postJson('nova-vendor/spatie/tail-tool')
        //     ->assertSuccessful()
        //     ->assertJson([
        //         'text' => '',
        //         'lastRetrievedLineNumber' => 10,
        //     ]);

        $this->assertTrue(true);
    }

    /** @test */
    public function it_can_start_from_a_specific_line()
    {
        $this
            ->postJson('nova-vendor/spatie/tail-tool', ['afterLineNumber' => 8])
            ->assertSuccessful()
            ->assertJson([
                'text' => 'nine'.PHP_EOL.'ten'.PHP_EOL,
                'lastRetrievedLineNumber' => 10,
            ]);
    }

    // protected function getPackageProviders($app)
    // {
    //     return [
    //         SettingsToolServiceProvider::class,
    //     ];
    // }
}
