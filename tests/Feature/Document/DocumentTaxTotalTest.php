<?php

namespace Tests\Feature\Document;

use App\Jobs\Document\CreateDocument;
use App\Models\Document\Document;
use App\Models\Setting\Currency;
use App\Models\Setting\Tax;
use Tests\Feature\FeatureTestCase;

/**
 * What a document comes to for every tax type Akaunting has, alone and mixed: every
 * combination of the five types one line can carry, the same type twice on a line,
 * and several lines at once with their own quantities and discounts.
 *
 * Every total below was read off the form first: the calculation methods of
 * resources/assets/js/views/common/documents.js were run on the same input and
 * the totals they report are what is asserted here. A line that stops matching
 * means the saved document no longer says what the form said, which is the one
 * thing a customer notices.
 *
 * The sub total and the tax rows are asserted too, so a change that keeps the
 * total right by moving an error from one row to another is still caught. The
 * tax rows are stored as absolute amounts, so a withholding tax adds to the
 * figure asserted here while subtracting from the total.
 */
class DocumentTaxTotalTest extends FeatureTestCase
{
    /**
     * One line of 110 at quantity 1, so a rate of 10 reads directly as 10%.
     */
    private const PRICE = 110;

    public function testItStoresWhatTheFormShowsForEveryTaxType(): void
    {
        $taxes = $this->createTaxes();

        // Every combination of tax types one line can carry, and the same type twice.
        // tax types | discount | sub total | discount | taxes | total
        $matrix = [
            ['none',                                       'no discount', 110, 0, 0, 110],
            ['none',                                       '10%',         110, 11, 0, 99],
            ['none',                                       'fixed 11',    110, 11, 0, 99],
            ['normal',                                     'no discount', 110, 0, 11, 121],
            ['normal',                                     '10%',         110, 11, 9.9, 108.9],
            ['normal',                                     'fixed 11',    110, 11, 9.9, 108.9],
            ['inclusive',                                  'no discount', 100, 0, 10, 110],
            ['inclusive',                                  '10%',         101, 11, 9, 99],
            ['inclusive',                                  'fixed 11',    101, 11, 9, 99],
            ['fixed',                                      'no discount', 110, 0, 10, 120],
            ['fixed',                                      '10%',         110, 11, 10, 109],
            ['fixed',                                      'fixed 11',    110, 11, 10, 109],
            ['withholding',                                'no discount', 110, 0, 11, 99],
            ['withholding',                                '10%',         110, 11, 9.9, 89.1],
            ['withholding',                                'fixed 11',    110, 11, 9.9, 89.1],
            ['compound',                                   'no discount', 110, 0, 11, 121],
            ['compound',                                   '10%',         110, 11, 9.9, 108.9],
            ['compound',                                   'fixed 11',    110, 11, 9.9, 108.9],
            ['normal+inclusive',                           'no discount', 100, 0, 20, 120],
            ['normal+inclusive',                           '10%',         101, 11, 18, 108],
            ['normal+inclusive',                           'fixed 11',    101, 11, 18, 108],
            ['normal+fixed',                               'no discount', 110, 0, 21, 131],
            ['normal+fixed',                               '10%',         110, 11, 19.9, 118.9],
            ['normal+fixed',                               'fixed 11',    110, 11, 19.9, 118.9],
            ['inclusive+fixed',                            'no discount', 100, 0, 20, 120],
            ['inclusive+fixed',                            '10%',         101, 11, 19, 109],
            ['inclusive+fixed',                            'fixed 11',    101, 11, 19, 109],
            ['normal+withholding',                         'no discount', 110, 0, 22, 110],
            ['normal+withholding',                         '10%',         110, 11, 19.8, 99],
            ['normal+withholding',                         'fixed 11',    110, 11, 19.8, 99],
            ['inclusive+withholding',                      'no discount', 100, 0, 20, 100],
            ['inclusive+withholding',                      '10%',         101, 11, 18, 90],
            ['inclusive+withholding',                      'fixed 11',    101, 11, 18, 90],
            ['fixed+withholding',                          'no discount', 110, 0, 21, 109],
            ['fixed+withholding',                          '10%',         110, 11, 19.9, 99.1],
            ['fixed+withholding',                          'fixed 11',    110, 11, 19.9, 99.1],
            ['normal+compound',                            'no discount', 110, 0, 23.1, 133.1],
            ['normal+compound',                            '10%',         110, 11, 20.79, 119.79],
            ['normal+compound',                            'fixed 11',    110, 11, 20.79, 119.79],
            ['inclusive+compound',                         'no discount', 100, 0, 21, 121],
            ['inclusive+compound',                         '10%',         101, 11, 18.9, 108.9],
            ['inclusive+compound',                         'fixed 11',    101, 11, 18.9, 108.9],
            ['fixed+compound',                             'no discount', 110, 0, 22, 132],
            ['fixed+compound',                             '10%',         110, 11, 20.9, 119.9],
            ['fixed+compound',                             'fixed 11',    110, 11, 20.9, 119.9],
            ['withholding+compound',                       'no discount', 110, 0, 20.9, 108.9],
            ['withholding+compound',                       '10%',         110, 11, 18.81, 98.01],
            ['withholding+compound',                       'fixed 11',    110, 11, 18.81, 98.01],
            ['normal+inclusive+fixed',                     'no discount', 100, 0, 30, 130],
            ['normal+inclusive+fixed',                     '10%',         101, 11, 28, 118],
            ['normal+inclusive+fixed',                     'fixed 11',    101, 11, 28, 118],
            ['normal+inclusive+withholding',               'no discount', 100, 0, 30, 110],
            ['normal+inclusive+withholding',               '10%',         101, 11, 27, 99],
            ['normal+inclusive+withholding',               'fixed 11',    101, 11, 27, 99],
            ['normal+fixed+withholding',                   'no discount', 110, 0, 32, 120],
            ['normal+fixed+withholding',                   '10%',         110, 11, 29.8, 109],
            ['normal+fixed+withholding',                   'fixed 11',    110, 11, 29.8, 109],
            ['inclusive+fixed+withholding',                'no discount', 100, 0, 30, 110],
            ['inclusive+fixed+withholding',                '10%',         101, 11, 28, 100],
            ['inclusive+fixed+withholding',                'fixed 11',    101, 11, 28, 100],
            ['normal+inclusive+compound',                  'no discount', 100, 0, 32, 132],
            ['normal+inclusive+compound',                  '10%',         101, 11, 28.8, 118.8],
            ['normal+inclusive+compound',                  'fixed 11',    101, 11, 28.8, 118.8],
            ['normal+fixed+compound',                      'no discount', 110, 0, 34.1, 144.1],
            ['normal+fixed+compound',                      '10%',         110, 11, 31.79, 130.79],
            ['normal+fixed+compound',                      'fixed 11',    110, 11, 31.79, 130.79],
            ['inclusive+fixed+compound',                   'no discount', 100, 0, 32, 132],
            ['inclusive+fixed+compound',                   '10%',         101, 11, 29.9, 119.9],
            ['inclusive+fixed+compound',                   'fixed 11',    101, 11, 29.9, 119.9],
            ['normal+withholding+compound',                'no discount', 110, 0, 33, 121],
            ['normal+withholding+compound',                '10%',         110, 11, 29.7, 108.9],
            ['normal+withholding+compound',                'fixed 11',    110, 11, 29.7, 108.9],
            ['inclusive+withholding+compound',             'no discount', 100, 0, 30, 110],
            ['inclusive+withholding+compound',             '10%',         101, 11, 27, 99],
            ['inclusive+withholding+compound',             'fixed 11',    101, 11, 27, 99],
            ['fixed+withholding+compound',                 'no discount', 110, 0, 31.9, 119.9],
            ['fixed+withholding+compound',                 '10%',         110, 11, 29.81, 109.01],
            ['fixed+withholding+compound',                 'fixed 11',    110, 11, 29.81, 109.01],
            ['normal+inclusive+fixed+withholding',         'no discount', 100, 0, 40, 120],
            ['normal+inclusive+fixed+withholding',         '10%',         101, 11, 37, 109],
            ['normal+inclusive+fixed+withholding',         'fixed 11',    101, 11, 37, 109],
            ['normal+inclusive+fixed+compound',            'no discount', 100, 0, 43, 143],
            ['normal+inclusive+fixed+compound',            '10%',         101, 11, 39.8, 129.8],
            ['normal+inclusive+fixed+compound',            'fixed 11',    101, 11, 39.8, 129.8],
            ['normal+inclusive+withholding+compound',      'no discount', 100, 0, 41, 121],
            ['normal+inclusive+withholding+compound',      '10%',         101, 11, 36.9, 108.9],
            ['normal+inclusive+withholding+compound',      'fixed 11',    101, 11, 36.9, 108.9],
            ['normal+fixed+withholding+compound',          'no discount', 110, 0, 44, 132],
            ['normal+fixed+withholding+compound',          '10%',         110, 11, 40.7, 119.9],
            ['normal+fixed+withholding+compound',          'fixed 11',    110, 11, 40.7, 119.9],
            ['inclusive+fixed+withholding+compound',       'no discount', 100, 0, 41, 121],
            ['inclusive+fixed+withholding+compound',       '10%',         101, 11, 38, 110],
            ['inclusive+fixed+withholding+compound',       'fixed 11',    101, 11, 38, 110],
            ['normal+inclusive+fixed+withholding+compound','no discount', 100, 0, 52, 132],
            ['normal+inclusive+fixed+withholding+compound','10%',         101, 11, 47.9, 119.9],
            ['normal+inclusive+fixed+withholding+compound','fixed 11',    101, 11, 47.9, 119.9],
            ['normal+normal2',                             'no discount', 110, 0, 16.5, 126.5],
            ['normal+normal2',                             '10%',         110, 11, 14.85, 113.85],
            ['normal+normal2',                             'fixed 11',    110, 11, 14.85, 113.85],
            ['inclusive+inclusive2',                       'no discount', 95.65, 0, 14.35, 110],
            ['inclusive+inclusive2',                       '10%',         97.09, 11, 12.91, 99],
            ['inclusive+inclusive2',                       'fixed 11',    97.09, 11, 12.91, 99],
            ['fixed+fixed2',                               'no discount', 110, 0, 15, 125],
            ['fixed+fixed2',                               '10%',         110, 11, 15, 114],
            ['fixed+fixed2',                               'fixed 11',    110, 11, 15, 114],
            ['withholding+withholding2',                   'no discount', 110, 0, 16.5, 93.5],
            ['withholding+withholding2',                   '10%',         110, 11, 14.85, 84.15],
            ['withholding+withholding2',                   'fixed 11',    110, 11, 14.85, 84.15],
            ['compound+compound2',                         'no discount', 110, 0, 16.5, 126.5],
            ['compound+compound2',                         '10%',         110, 11, 14.85, 113.85],
            ['compound+compound2',                         'fixed 11',    110, 11, 14.85, 113.85],
        ];

        $wrong = [];

        foreach ($matrix as [$combination, $discount_name, $sub, $discount, $tax, $total]) {
            $items = [['price' => self::PRICE, 'tax_ids' => $this->taxIds($taxes, $combination)]];

            $got = $this->totalsOf($items, $discount_name);

            $expected = [
                'sub' => round((float) $sub, 2),
                'discount' => round((float) $discount, 2),
                'tax' => round((float) $tax, 2),
                'total' => round((float) $total, 2),
            ];

            if ($got !== $expected) {
                $wrong[$combination . ' | ' . $discount_name] = ['expected' => $expected, 'got' => $got];
            }
        }

        // Every cell at once, so one run says which combinations moved
        $this->assertSame([], $wrong, 'Saved totals no longer match the form');
    }

    public function testItStoresWhatTheFormShowsForMoreThanOneLine(): void
    {
        $taxes = $this->createTaxes();

        $items = [
            ['price' => 110, 'tax_ids' => [$taxes['inclusive']->id]],
            ['price' => 100, 'tax_ids' => [$taxes['normal']->id]],
        ];

        $this->assertSame(
            ['sub' => 200.0, 'discount' => 0.0, 'tax' => 20.0, 'total' => 220.0],
            $this->totalsOf($items, 'no discount')
        );

        $this->assertSame(
            ['sub' => 201.0, 'discount' => 21.0, 'tax' => 18.0, 'total' => 198.0],
            $this->totalsOf($items, '10%')
        );

        // A fixed discount is shared out by what each line comes to
        $this->assertSame(
            ['sub' => 200.52, 'discount' => 11.0, 'tax' => 18.96, 'total' => 208.48],
            $this->totalsOf($items, 'fixed 11')
        );
    }

    public function testItStoresWhatTheFormShowsWithADiscountOnTheLineAsWell(): void
    {
        $taxes = $this->createTaxes();

        $items = [[
            'price' => 110,
            'tax_ids' => [$taxes['inclusive']->id],
            'discount' => 10,
            'discount_type' => 'percentage',
        ]];

        $this->assertSame(
            ['sub' => 90.0, 'discount' => 0.0, 'tax' => 9.0, 'total' => 99.0],
            $this->totalsOf($items, 'no discount')
        );

        $this->assertSame(
            ['sub' => 90.9, 'discount' => 9.9, 'tax' => 8.1, 'total' => 89.1],
            $this->totalsOf($items, '10%')
        );

        $this->assertSame(
            ['sub' => 91.0, 'discount' => 11.0, 'tax' => 8.0, 'total' => 88.0],
            $this->totalsOf($items, 'fixed 11')
        );
    }

    /**
     * The same arithmetic runs for a bill, so the tax types that are worked out
     * from the line total are checked there too.
     */
    public function testItStoresWhatTheFormShowsOnABill(): void
    {
        $taxes = $this->createTaxes();

        $expected = [
            'inclusive' => 99.0,
            'normal' => 108.9,
            'withholding' => 89.1,
            'compound' => 108.9,
        ];

        foreach ($expected as $type => $total) {
            $items = [['price' => self::PRICE, 'tax_ids' => [$taxes[$type]->id]]];

            $bill = $this->create($items, '10%', 'bill');

            $this->assertSame($total, round((float) $bill->amount, 2), 'bill with a ' . $type . ' tax');
        }
    }

    /**
     * A price carries both of its inclusive taxes at the same time, so the amount
     * without them is the price divided by the two rates added up. Taking each rate
     * out of the full price on its own reported more tax than the price holds.
     */
    public function testItTakesTwoInclusiveTaxesOutOfThePriceTogether(): void
    {
        $taxes = $this->createTaxes();

        // 110 carrying 10% and 5% is 95.65, which those rates turn into 9.57 and 4.78
        $items = [['price' => 110, 'tax_ids' => [$taxes['inclusive']->id, $taxes['inclusive2']->id]]];

        $document = $this->create($items, 'no discount');

        $amounts = $document->refresh()->item_taxes->pluck('amount', 'tax_id');

        $this->assertSame(9.57, round((float) $amounts[$taxes['inclusive']->id], 2));
        $this->assertSame(4.78, round((float) $amounts[$taxes['inclusive2']->id], 2));

        // The price is what it was, so the customer is asked for the same 110
        $this->assertSame(110.0, round((float) $document->amount, 2));
        $this->assertSame(95.65, round((float) $document->totals->where('code', 'sub_total')->sum('amount'), 2));
    }

    /**
     * A currency with no minor units rounds at every step, which is where a total
     * worked out twice drifts the furthest from the one the form showed.
     */
    public function testItStoresWhatTheFormShowsOnACurrencyWithoutMinorUnits(): void
    {
        $taxes = $this->createTaxes();

        $this->createYen();

        $items = [['price' => 1000, 'tax_ids' => [$taxes['inclusive']->id]]];

        $this->assertSame(
            ['sub' => 918.0, 'discount' => 100.0, 'tax' => 82.0, 'total' => 900.0],
            $this->totalsOf($items, '10%', 'JPY')
        );

        $items = [['price' => 1000, 'tax_ids' => [$taxes['inclusive']->id, $taxes['inclusive2']->id]]];

        $this->assertSame(
            ['sub' => 883.0, 'discount' => 100.0, 'tax' => 117.0, 'total' => 900.0],
            $this->totalsOf($items, '10%', 'JPY')
        );
    }

    /**
     * More than one line at a time: quantities above one, a different mix of taxes on
     * every line, line discounts of both kinds under a document discount, and the five
     * tax types spread over a line each.
     */
    public function testItStoresWhatTheFormShowsForSeveralLinesAtOnce(): void
    {
        $taxes = $this->createTaxes();

        $lines = [
            '3 lines mixed qty' => [
                ['price' => 110, 'quantity' => 2, 'tax_ids' => [$taxes['inclusive']->id]],
                ['price' => 33.33, 'quantity' => 3, 'tax_ids' => [$taxes['normal']->id, $taxes['compound']->id]],
                ['price' => 7, 'quantity' => 1, 'tax_ids' => [$taxes['withholding']->id, $taxes['fixed']->id]],
            ],
            '2 lines line-discounts' => [
                ['price' => 110, 'quantity' => 1, 'tax_ids' => [$taxes['inclusive']->id, $taxes['inclusive2']->id], 'discount' => 10, 'discount_type' => 'percentage'],
                ['price' => 55, 'quantity' => 2, 'tax_ids' => [$taxes['normal']->id], 'discount' => 5, 'discount_type' => 'fixed'],
            ],
            '5 lines one type each' => [
                ['price' => 20, 'tax_ids' => [$taxes['normal']->id]],
                ['price' => 30, 'tax_ids' => [$taxes['inclusive']->id]],
                ['price' => 40, 'tax_ids' => [$taxes['fixed']->id]],
                ['price' => 50, 'tax_ids' => [$taxes['withholding']->id]],
                ['price' => 60, 'tax_ids' => [$taxes['compound']->id]],
            ],
            '2 lines all five taxes' => [
                ['price' => 110, 'quantity' => 1, 'tax_ids' => $this->taxIds($taxes, 'normal+inclusive+fixed+withholding+compound')],
                ['price' => 90, 'quantity' => 2, 'tax_ids' => $this->taxIds($taxes, 'normal+inclusive+fixed+withholding+compound')],
            ],
        ];

        // lines | discount | sub total | discount | taxes | total
        $matrix = [
            ['3 lines mixed qty',      'no discount', 306.99, 0,    51.7,   357.29],
            ['3 lines mixed qty',      '10%',         308.99, 32.7, 47.53,  322.56],
            ['3 lines mixed qty',      'fixed 11',    307.66, 11,   50.3,   345.6],
            ['2 lines line-discounts', 'no discount', 191.09, 0,    23.41,  214.5],
            ['2 lines line-discounts', '10%',         192.38, 20.4, 21.07,  193.05],
            // The form shows 202.93 here: it adds up line totals whose tax was rounded
            // a line at a time, while the saved total is the saved rows added up
            ['2 lines line-discounts', 'fixed 11',    191.78, 11,   22.14,  202.92],
            ['5 lines one type each',  'no discount', 197.27, 0,    25.73,  213],
            ['5 lines one type each',  '10%',         197.55, 20,   24.15,  192.7],
            ['5 lines one type each',  'fixed 11',    197.42, 11,   24.87,  201.83],
            ['2 lines all five taxes', 'no discount', 263.64, 0,    141.08, 352],
            ['2 lines all five taxes', '10%',         266.27, 29,   130.29, 320.1],
            ['2 lines all five taxes', 'fixed 11',    264.64, 11,   136.98, 339.9],
        ];

        $wrong = [];

        foreach ($matrix as [$name, $discount_name, $sub, $discount, $tax, $total]) {
            $got = $this->totalsOf($lines[$name], $discount_name);

            $expected = [
                'sub' => round((float) $sub, 2),
                'discount' => round((float) $discount, 2),
                'tax' => round((float) $tax, 2),
                'total' => round((float) $total, 2),
            ];

            if ($got !== $expected) {
                $wrong[$name . ' | ' . $discount_name] = ['expected' => $expected, 'got' => $got];
            }
        }

        $this->assertSame([], $wrong, 'Saved totals no longer match the form');
    }

    /**
     * A compound tax is charged on the price and the other taxes. Two of them on one
     * line are both charged on that same amount: charging the second on top of the
     * first made the figure depend on the order the taxes happened to be in.
     */
    public function testItChargesEveryCompoundTaxOnTheSameAmount(): void
    {
        $taxes = $this->createTaxes();

        $items = [['price' => 110, 'tax_ids' => [$taxes['compound']->id, $taxes['compound2']->id]]];

        $document = $this->create($items, 'no discount');

        $amounts = $document->refresh()->item_taxes->pluck('amount', 'tax_id');

        // 10% and 5% of 110, not 5% of 121
        $this->assertSame(11.0, round((float) $amounts[$taxes['compound']->id], 2));
        $this->assertSame(5.5, round((float) $amounts[$taxes['compound2']->id], 2));

        $this->assertSame(126.5, round((float) $document->amount, 2));
    }

    /**
     * One line carrying all five tax types, row by row, so a type that quietly starts
     * working from the wrong amount is caught even when the total still adds up.
     */
    public function testItStoresEveryTaxRowOfALineCarryingAllFiveTypes(): void
    {
        $taxes = $this->createTaxes();

        $items = [['price' => 110, 'tax_ids' => $this->taxIds($taxes, 'normal+inclusive+fixed+withholding+compound')]];

        $document = $this->create($items, 'no discount');

        $amounts = $document->refresh()->item_taxes->pluck('amount', 'tax_id');

        $expected = [
            // 110 carrying a 10% inclusive tax is 100, which is what the rest work from
            'inclusive' => 10.0,
            'fixed' => 10.0,
            'normal' => 10.0,
            'withholding' => 10.0,
            // 100 and every other tax: 100 + 10 + 10 + 10 - 10
            'compound' => 12.0,
        ];

        $got = [];

        foreach ($expected as $type => $amount) {
            $got[$type] = round((float) $amounts[$taxes[$type]->id], 2);
        }

        $this->assertSame($expected, $got);

        $this->assertSame(132.0, round((float) $document->amount, 2));
    }

    // -------------------------------------------------------------------------
    // Helpers
    // -------------------------------------------------------------------------

    /**
     * @return array<string, Tax>
     */
    private function createTaxes(): array
    {
        return [
            'normal' => Tax::factory()->enabled()->normal()->create(['rate' => 10]),
            'inclusive' => Tax::factory()->enabled()->inclusive()->create(['rate' => 10]),
            'fixed' => Tax::factory()->enabled()->fixed()->create(['rate' => 10]),
            'withholding' => Tax::factory()->enabled()->withholding()->create(['rate' => 10]),
            'compound' => Tax::factory()->enabled()->compound()->create(['rate' => 10]),

            // A second tax of each type, at half the rate, for the lines that carry
            // two taxes of the same kind
            'normal2' => Tax::factory()->enabled()->normal()->create(['rate' => 5]),
            'inclusive2' => Tax::factory()->enabled()->inclusive()->create(['rate' => 5]),
            'fixed2' => Tax::factory()->enabled()->fixed()->create(['rate' => 5]),
            'withholding2' => Tax::factory()->enabled()->withholding()->create(['rate' => 5]),
            'compound2' => Tax::factory()->enabled()->compound()->create(['rate' => 5]),
        ];
    }

    private function createYen(): Currency
    {
        return Currency::factory()->enabled()->create([
            'name' => 'Japanese Yen',
            'code' => 'JPY',
            'rate' => 1,
            'precision' => currency('JPY')->getPrecision(),
            'symbol' => currency('JPY')->getSymbol(),
            'symbol_first' => currency('JPY')->isSymbolFirst(),
            'decimal_mark' => currency('JPY')->getDecimalMark(),
            'thousands_separator' => currency('JPY')->getThousandsSeparator(),
        ]);
    }

    /**
     * 'inclusive+normal' reads as the ids of those two taxes, 'none' as no tax.
     *
     * @param array<string, Tax> $taxes
     * @return array<int>
     */
    private function taxIds(array $taxes, string $combination): array
    {
        if ($combination === 'none') {
            return [];
        }

        return array_map(fn ($type) => $taxes[$type]->id, explode('+', $combination));
    }

    /**
     * @return array{sub: float, discount: float, tax: float, total: float}
     */
    private function totalsOf(array $items, string $discount_name, ?string $currency_code = null): array
    {
        $document = $this->create($items, $discount_name, 'invoice', $currency_code);

        $totals = $document->refresh()->totals;

        return [
            'sub' => round((float) $totals->where('code', 'sub_total')->sum('amount'), 2),
            'discount' => round((float) $totals->where('code', 'discount')->sum('amount'), 2),
            'tax' => round((float) $totals->where('code', 'tax')->sum('amount'), 2),
            'total' => round((float) $document->amount, 2),
        ];
    }

    private function create(array $items, string $discount_name, string $type = 'invoice', ?string $currency_code = null): Document
    {
        [$discount, $discount_type] = match ($discount_name) {
            'no discount' => [0, 'percentage'],
            '10%' => [10, 'percentage'],
            'fixed 11' => [11, 'fixed'],
        };

        $request = Document::factory()->{$type}()->items()->raw();

        $request['items'] = array_map(fn ($item) => [
            'name' => 'Item',
            'description' => '',
            'quantity' => $item['quantity'] ?? 1,
            'price' => $item['price'],
            'tax_ids' => $item['tax_ids'],
            'discount' => $item['discount'] ?? 0,
            'discount_type' => $item['discount_type'] ?? 'percentage',
        ], $items);

        $request['discount'] = $discount;
        $request['discount_type'] = $discount_type;

        if ($currency_code) {
            $request['currency_code'] = $currency_code;
            $request['currency_rate'] = 1;
        }

        return $this->dispatch(new CreateDocument($request));
    }
}
