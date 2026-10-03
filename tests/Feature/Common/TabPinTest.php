<?php

namespace Tests\Feature\Common;

use App\Http\Livewire\Tab\Pin;
use Livewire\Livewire;
use Tests\Feature\FeatureTestCase;

class TabPinTest extends FeatureTestCase
{
    public function testItShouldPinAndUnpinOnlyItsOwnList()
    {
        $this->loginAs();

        $key = 'favorites.tab.' . $this->user->id;

        // Lists share tab names, so the invoices' "all" pin comes first and must survive unpinning the bills' one
        setting([$key => json_encode(['invoice' => 'all', 'bill' => 'all', 'estimate' => 'draft'])])->save();

        Livewire::test(Pin::class, ['id' => 'bill-all', 'type' => 'bill', 'tab' => 'all'])
            ->assertSet('pinned', true)
            ->call('changeStatus', 'all')
            ->assertSet('pinned', false);

        $this->assertSame(['invoice' => 'all', 'estimate' => 'draft'], json_decode(setting($key), true));

        // A list without that pin removes nothing
        Livewire::test(Pin::class, ['id' => 'bill-all', 'type' => 'bill', 'tab' => 'all'])
            ->call('removePin', 'all');

        $this->assertSame(['invoice' => 'all', 'estimate' => 'draft'], json_decode(setting($key), true));

        // Pinning replaces the list's own pin and leaves the others
        Livewire::test(Pin::class, ['id' => 'estimate-all', 'type' => 'estimate', 'tab' => 'all'])
            ->assertSet('pinned', false)
            ->call('changeStatus', 'all')
            ->assertSet('pinned', true);

        $this->assertSame(['invoice' => 'all', 'estimate' => 'all'], json_decode(setting($key), true));
    }
}
