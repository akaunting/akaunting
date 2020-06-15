<?php

namespace Tests\Feature\Settings;

use Tests\Feature\FeatureTestCase;

class DoubleEntryTest extends FeatureTestCase
{
    public function testItShouldSeeDoubleEntryEditPage()
    {
        $this->loginAs()
            ->get(route('double-entry.settings.edit'))
            ->assertStatus(200);
    }


    public function testItShouldUpdateDoubleEntrySetting()
    {
        $request = $this->getRequest();

        $this->loginAs()
            ->patch(route('double-entry.settings.update'), $request)
            ->assertStatus(200);

        $this->assertFlashLevel('success');
    }

    public function getRequest()
    {
        return [
            'accounts_receivable' => '120',
            'accounts_payable' => '200',
            'accounts_sales' => '400',
            'accounts_expenses' => '628',
            'types_bank'=>'6',
            'types_tax' => '17'
        ];
    }

}



