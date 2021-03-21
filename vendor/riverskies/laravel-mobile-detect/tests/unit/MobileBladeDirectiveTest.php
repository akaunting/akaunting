<?php

use Riverskies\Laravel\MobileDetect\Directives\MobileBladeDirective;

class MobileBladeDirectiveTest extends TestCase
{
    /**
     * Set up the world.
     */
    public function setUp()
    {
        parent::setUp();

        $this->setUpTemplateEngine(new MobileBladeDirective);
    }

    /** @test */
    public function it_will_render_if_mobile()
    {
        $this->expectMobileDetectReturn(function($md) {
            $md->isMobile()->willReturn(true);
            $md->isTablet()->willReturn(false);
        });

        $html = $this->blade->view()->make('test')->render();

        $this->assertEquals('<h1>Test</h1>', $this->clean($html));
    }

    /** @test */
    public function it_will_not_render_if_not_mobile()
    {
        $this->expectMobileDetectReturn(function($md) {
            $md->isMobile()->willReturn(false);
        });

        $html = $this->blade->view()->make('test')->render();

        $this->assertEquals('', $this->clean($html));
    }

    /** @test */
    public function it_will_display_else_if_exist_and_not_mobile()
    {
        $this->expectMobileDetectReturn(function($md) {
            $md->isMobile()->willReturn(false);
        });

        $html = $this->blade->view()->make('test-else')->render();

        $this->assertEquals('<h1>Else</h1>', $this->clean($html));
    }

    /** @test */
    public function it_will_still_display_mobile_if_is_mobile_and_else_exists()
    {
        $this->expectMobileDetectReturn(function($md) {
            $md->isMobile()->willReturn(true);
            $md->isTablet()->willReturn(false);
        });

        $html = $this->blade->view()->make('test-else')->render();

        $this->assertEquals('<h1>Test</h1>', $this->clean($html));
    }
}
