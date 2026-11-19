<?php

namespace Tests\Feature\Document;

use App\Jobs\Document\CreateDocument;
use App\Jobs\Setting\CreateCategory;
use App\Jobs\Setting\DeleteCategory;
use App\Listeners\Update\V33\Version332;
use App\Models\Common\Item;
use App\Models\Document\Document;
use App\Models\Setting\Category;
use App\Traits\Import;
use Illuminate\Support\Facades\DB;
use Tests\Feature\FeatureTestCase;

/**
 * An item that is written on an invoice or bill line and not picked from the list is created with the
 * document. The category of the line is an income or expense one, so the item gets the default item
 * category instead.
 */
class DocumentNewItemCategoryTest extends FeatureTestCase
{
    public function testANewCompanyShouldHaveADefaultItemCategory(): void
    {
        $category = Category::find(setting('default.item_category'));

        $this->assertNotNull($category);
        $this->assertSame(Category::ITEM_TYPE, $category->type);
    }

    public function testTheNewItemOfAnInvoiceShouldGetTheDefaultItemCategory(): void
    {
        $this->loginAs();

        $invoice = $this->createDocumentWithNewItem('invoice');

        $this->assertNewItem($invoice, (int) setting('default.item_category'), $invoice->category_id);
    }

    public function testTheNewItemOfABillShouldGetTheDefaultItemCategory(): void
    {
        $this->loginAs();

        $bill = $this->createDocumentWithNewItem('bill');

        $this->assertNewItem($bill, (int) setting('default.item_category'), $bill->category_id);
    }

    public function testTheNewItemShouldNotGetTheCategoryOfItsLine(): void
    {
        $this->loginAs();

        // A line can have its own category, i.e. the account of the line with Double Entry
        $line_category = $this->createCategory(Category::INCOME_TYPE);

        $invoice = $this->createDocumentWithNewItem('invoice', $line_category->id);

        $this->assertNewItem($invoice, (int) setting('default.item_category'), $line_category->id);
    }

    public function testTheNewItemShouldHaveNoCategoryWithoutADefaultItemCategory(): void
    {
        $this->loginAs();

        setting(['default.item_category' => '']);

        $invoice = $this->createDocumentWithNewItem('invoice');

        $this->assertNewItem($invoice, null, $invoice->category_id);
    }

    public function testTheNewItemOfAnImportedDocumentShouldGetTheDefaultItemCategory(): void
    {
        $this->loginAs();

        // The invoice and bill item sheets create the item of a line that is not in the company yet
        $importer = new class {
            use Import;
        };

        $item_id = $importer->getItemIdFromName([
            'item_name' => 'Imported item ' . $this->faker->unique()->numberBetween(1000, 9999),
            'price' => 100,
        ]);

        $this->assertSame((int) setting('default.item_category'), (int) Item::find($item_id)->category_id);
    }

    public function testTheDefaultItemCategoryShouldBeSavedInTheDefaultSettings(): void
    {
        $category = $this->createCategory(Category::ITEM_TYPE);

        $this->loginAs()
            ->patch(route('settings.default.update'), [
                '_prefix' => 'default',
                'account' => setting('default.account'),
                'currency' => setting('default.currency'),
                'locale' => setting('default.locale', 'en-GB'),
                'income_category' => setting('default.income_category'),
                'expense_category' => setting('default.expense_category'),
                'item_category' => $category->id,
                'payment_method' => setting('default.payment_method'),
                'address_format' => setting('default.address_format'),
            ])
            ->assertStatus(200);

        $this->assertDatabaseHas('settings', [
            'company_id' => $this->company->id,
            'key' => 'default.item_category',
            'value' => $category->id,
        ]);
    }

    public function testTheDefaultItemCategoryShouldNotBeDeleted(): void
    {
        $this->loginAs();

        $this->expectException(\Exception::class);

        $this->dispatch(new DeleteCategory(Category::find(setting('default.item_category'))));
    }

    public function testTheUpdateShouldSetTheDefaultItemCategoryOfACompanyWithoutOne(): void
    {
        $this->loginAs();

        $first = (int) DB::table('categories')
            ->where('company_id', $this->company->id)
            ->where('type', Category::ITEM_TYPE)
            ->where('enabled', 1)
            ->whereNull('deleted_at')
            ->min('id');

        DB::table('settings')
            ->where('company_id', $this->company->id)
            ->where('key', 'default.item_category')
            ->delete();

        (new Version332())->updateDefaultItemCategory();

        $this->assertSame((string) $first, $this->getStoredDefaultItemCategory());
    }

    public function testTheUpdateShouldSetTheDefaultItemCategoryACompanyLeftEmpty(): void
    {
        $this->loginAs();

        $default = setting('default.item_category');

        DB::table('settings')
            ->where('company_id', $this->company->id)
            ->where('key', 'default.item_category')
            ->update(['value' => '']);

        (new Version332())->updateDefaultItemCategory();

        $this->assertSame((string) $default, $this->getStoredDefaultItemCategory());
        $this->assertSame(1, DB::table('settings')->where('company_id', $this->company->id)->where('key', 'default.item_category')->count());
    }

    public function testTheUpdateShouldKeepTheDefaultItemCategoryACompanySet(): void
    {
        $this->loginAs();

        $category = $this->createCategory(Category::ITEM_TYPE);

        DB::table('settings')
            ->where('company_id', $this->company->id)
            ->where('key', 'default.item_category')
            ->update(['value' => $category->id]);

        (new Version332())->updateDefaultItemCategory();

        $this->assertSame((string) $category->id, $this->getStoredDefaultItemCategory());
    }

    public function testTheUpdateShouldMoveTheItemsOffTheCategoryOfTheirDocument(): void
    {
        $this->loginAs();

        $default = (int) setting('default.item_category');
        $own = $this->createCategory(Category::ITEM_TYPE);

        $wrong = Item::factory()->create(['company_id' => $this->company->id, 'category_id' => setting('default.income_category')]);
        $right = Item::factory()->create(['company_id' => $this->company->id, 'category_id' => $own->id]);
        $none = Item::factory()->create(['company_id' => $this->company->id, 'category_id' => null]);

        (new Version332())->updateItemCategories();

        $this->assertSame($default, (int) $wrong->fresh()->category_id);
        $this->assertSame($own->id, (int) $right->fresh()->category_id);
        $this->assertNull($none->fresh()->category_id);
    }

    private function createDocumentWithNewItem(string $type, ?int $line_category_id = null): Document
    {
        $request = Document::factory()->$type()->items()->raw();

        $request['items'][0]['item_id'] = null;
        $request['items'][0]['name'] = 'New item ' . $this->faker->unique()->numberBetween(1000, 9999);
        $request['items'][0]['category_id'] = $line_category_id;

        return $this->dispatch(new CreateDocument($request));
    }

    private function assertNewItem(Document $document, ?int $item_category_id, ?int $line_category_id): void
    {
        $line = $document->items()->first();

        $this->assertNotNull($line->item_id);
        $this->assertSame($line_category_id, $line->category_id ? (int) $line->category_id : null);

        $item = Item::find($line->item_id);

        $this->assertSame($line->name, $item->name);
        $this->assertSame($item_category_id, $item->category_id ? (int) $item->category_id : null);
    }

    private function createCategory(string $type): Category
    {
        return $this->dispatch(new CreateCategory([
            'company_id' => $this->company->id,
            'name' => $this->faker->unique()->text(15),
            'type' => $type,
            'color' => '#328aef',
            'enabled' => '1',
        ]));
    }

    private function getStoredDefaultItemCategory(): ?string
    {
        return DB::table('settings')
            ->where('company_id', $this->company->id)
            ->where('key', 'default.item_category')
            ->value('value');
    }
}
