<?php

namespace Tests\Feature\Wizard;

use App\Events\Wizard\WizardCompleted;
use Illuminate\Support\Facades\Event;
use Tests\Feature\FeatureTestCase;

class FinishTest extends FeatureTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        setting()->set('wizard.completed', 0);
        setting()->save();
    }

    /**
     * The finish step completes the wizard with a POST that carries _method, as the forms send it, so it gets
     * through servers and firewalls that refuse PATCH requests.
     */
    public function testItShouldCompleteTheWizardFromTheFinishStep()
    {
        Event::fake([WizardCompleted::class]);

        $this->loginAs()
            ->postJson(route('wizard.finish.update'), ['_method' => 'PATCH'])
            ->assertOk();

        $this->assertEquals(1, setting('wizard.completed'));

        Event::assertDispatchedTimes(WizardCompleted::class, 1);

        // Once only
        $this->loginAs()
            ->postJson(route('wizard.finish.update'), ['_method' => 'PATCH'])
            ->assertOk();

        Event::assertDispatchedTimes(WizardCompleted::class, 1);
    }

    public function testItShouldCompleteTheWizardWhenTheFinishPageIsOpened()
    {
        $this->loginAs()
            ->get(route('wizard.finish.index'))
            ->assertOk();

        $this->assertEquals(1, setting('wizard.completed'));
    }
}
