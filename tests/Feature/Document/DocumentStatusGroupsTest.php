<?php

namespace Tests\Feature\Document;

use App\Models\Document\Document;
use Tests\Feature\FeatureTestCase;

/**
 * Which statuses are in the books (accrued) and which are still owed on (outstanding). The scopes
 * answer it for a query and the is*() methods for one document, and both give the same answer.
 */
class DocumentStatusGroupsTest extends FeatureTestCase
{
    /**
     * Status => [accrued, outstanding]. "issued" stands for a status a module or the API stores.
     */
    protected const STATUSES = [
        'draft'     => [false, false],
        'cancelled' => [false, false],
        'sent'      => [true, true],
        'received'  => [true, true],
        'viewed'    => [true, true],
        'partial'   => [true, true],
        'issued'    => [true, true],
        'paid'      => [true, false],
    ];

    public function testTheScopesAndTheMethodsGiveTheSameStatuses(): void
    {
        $ids = [];

        foreach (self::STATUSES as $status => [$accrued, $outstanding]) {
            // Stored as is, without the events the status would fire
            $document = Document::factory()->invoice()->draft()->create();
            Document::whereKey($document->id)->update(['status' => $status]);
            $document->refresh();

            $this->assertSame($accrued, $document->isAccrued(), $status);
            $this->assertSame($outstanding, $document->isOutstanding(), $status);

            $ids[$status] = $document->id;
        }

        $this->assertEqualsCanonicalizing(
            $this->statusesWhere(0),
            Document::whereIn('id', $ids)->accrued()->pluck('status')->all()
        );

        $this->assertEqualsCanonicalizing(
            $this->statusesWhere(1),
            Document::whereIn('id', $ids)->outstanding()->pluck('status')->all()
        );
    }

    protected function statusesWhere(int $group): array
    {
        return array_keys(array_filter(self::STATUSES, fn ($groups) => $groups[$group]));
    }
}
