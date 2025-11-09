<?php

namespace Tests\Feature\Performance;

use App\Jobs\Common\CreateContact;
use App\Models\Common\Contact;
use App\Models\Common\ContactPerson;
use App\Models\Document\Document;
use App\Models\Document\DocumentHistory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\Feature\FeatureTestCase;

/**
 * Test class to validate Vendor N+1 query optimizations
 * Ensures contact_persons and document_histories N+1 fixes work correctly
 */
class VendorN1QueryTest extends FeatureTestCase
{
    use RefreshDatabase;

    /**
     * Test contact_persons withCount optimization prevents N+1
     */
    public function testContactPersonsWithCountOptimization()
    {
        $this->loginAs();
        
        // Create vendors using factory
        $vendors = Contact::factory()->vendor()->count(10)->create([
            'company_id' => $this->company->id
        ]);
        
        // Add contact persons to vendors (with required type field)
        foreach ($vendors as $vendor) {
            for ($j = 0; $j < rand(1, 3); $j++) {
                ContactPerson::create([
                    'company_id' => $this->company->id,
                    'type' => 'vendor', // Required field
                    'contact_id' => $vendor->id,
                    'name' => 'Person ' . $j,
                    'email' => 'person' . $vendor->id . '_' . $j . '@example.com',
                    'phone' => '123-456-7890'
                ]);
            }
        }

        // Test optimized query with withCount
        DB::enableQueryLog();
        
        $optimizedVendors = Contact::vendor()
            ->withCount([
                'contact_persons as contact_persons_with_email_count' => function ($query) {
                    $query->whereNotNull('email');
                }
            ])
            ->limit(10)
            ->get();
        
        // Access has_email attribute (uses cached count)
        foreach ($optimizedVendors as $vendor) {
            $hasEmail = $vendor->has_email;
        }
        
        $queryCount = count(DB::getQueryLog());
        DB::disableQueryLog();

        // Assertions
        $this->assertEquals(10, $optimizedVendors->count());
        $this->assertLessThan(5, $queryCount, 'Should use less than 5 queries');
        
        // Verify count attribute is loaded
        foreach ($optimizedVendors as $vendor) {
            $this->assertTrue(isset($vendor->contact_persons_with_email_count));
        }
    }

    /**
     * Test document histories eager loading prevents N+1
     */
    public function testDocumentHistoriesEagerLoadingOptimization()
    {
        $this->loginAs();
        
        // Create vendor with bills
        $vendor = Contact::factory()->vendor()->create([
            'company_id' => $this->company->id
        ]);
        
        // Create bills with histories
        for ($i = 0; $i < 5; $i++) {
            $bill = Document::factory()->bill()->create([
                'company_id' => $this->company->id,
                'contact_id' => $vendor->id
            ]);
            
            // Add sent and received histories (with required type field)
            DocumentHistory::create([
                'company_id' => $this->company->id,
                'type' => Document::BILL_TYPE, // Required field
                'document_id' => $bill->id,
                'status' => 'sent',
                'notify' => 0,
                'description' => 'Sent to vendor'
            ]);
            
            DocumentHistory::create([
                'company_id' => $this->company->id,
                'type' => Document::BILL_TYPE, // Required field
                'document_id' => $bill->id,
                'status' => 'received',
                'notify' => 0,
                'description' => 'Received from vendor'
            ]);
        }

        // Test optimized query with eager loading
        DB::enableQueryLog();
        
        $optimizedBills = Document::bill()
            ->where('contact_id', $vendor->id)
            ->with('histories')
            ->get();
        
        // Access sent_at and received_at attributes (uses eager loaded data)
        foreach ($optimizedBills as $bill) {
            $sentAt = $bill->sent_at;
            $receivedAt = $bill->received_at;
        }
        
        $queryCount = count(DB::getQueryLog());
        DB::disableQueryLog();

        // Assertions
        $this->assertEquals(5, $optimizedBills->count());
        $this->assertLessThan(5, $queryCount, 'Should use less than 5 queries');
        
        // Verify histories relationship is loaded
        foreach ($optimizedBills as $bill) {
            $this->assertTrue($bill->relationLoaded('histories'));
        }
    }

    /**
     * Test vendors index page uses optimized queries
     */
    public function testVendorsIndexPageOptimization()
    {
        $this->loginAs();
        
        // Create realistic test data
        $vendors = Contact::factory()->vendor()->count(10)->create([
            'company_id' => $this->company->id
        ]);
        
        foreach ($vendors->take(5) as $vendor) {
            // Add contact persons (with required type field)
            ContactPerson::create([
                'company_id' => $this->company->id,
                'type' => 'vendor', // Required field
                'contact_id' => $vendor->id,
                'name' => 'Test Person',
                'email' => 'person@example.com',
                'phone' => '1234567890'
            ]);
            
            // Add bills with histories
            for ($i = 0; $i < 2; $i++) {
                $bill = Document::factory()->bill()->create([
                    'company_id' => $this->company->id,
                    'contact_id' => $vendor->id
                ]);
                
                DocumentHistory::create([
                    'company_id' => $this->company->id,
                    'type' => Document::BILL_TYPE, // Required field
                    'document_id' => $bill->id,
                    'status' => 'sent',
                    'notify' => 0,
                    'description' => 'Sent'
                ]);
            }
        }

        // Test optimized controller query
        DB::enableQueryLog();
        
        $optimizedVendors = Contact::with([
                'media',
                'bills.histories',
                'bills.totals',
                'bills.transactions',
                'bills.media'
            ])
            ->withCount([
                'contact_persons as contact_persons_with_email_count' => function ($query) {
                    $query->whereNotNull('email');
                }
            ])
            ->vendor()
            ->collect();
        
        // Simulate view access
        foreach ($optimizedVendors as $vendor) {
            $hasEmail = $vendor->has_email;
            $open = $vendor->open;
            
            foreach ($vendor->bills as $bill) {
                $sentAt = $bill->sent_at;
            }
        }
        
        $queryCount = count(DB::getQueryLog());
        DB::disableQueryLog();

        // Assertions
        $this->assertGreaterThan(0, $optimizedVendors->count());
        $this->assertLessThan(15, $queryCount, 'Should use less than 15 queries');
        
        // Verify relationships are loaded
        foreach ($optimizedVendors as $vendor) {
            $this->assertTrue(isset($vendor->contact_persons_with_email_count));
            
            if ($vendor->bills->count() > 0) {
                $this->assertTrue($vendor->bills->first()->relationLoaded('histories'));
            }
        }
    }

    /**
     * Test actual HTTP request to vendors index
     */
    public function testVendorsIndexHttpRequest()
    {
        $this->loginAs();
        
        // Create test data
        Contact::factory()->vendor()->count(5)->create([
            'company_id' => $this->company->id
        ]);

        DB::enableQueryLog();
        
        $response = $this->get(route('vendors.index'));
        
        $queryCount = count(DB::getQueryLog());
        DB::disableQueryLog();

        $response->assertStatus(200);
        $response->assertViewIs('purchases.vendors.index');
        
        // Should not use excessive queries
        $this->assertLessThan(30, $queryCount, 'Vendors index should use reasonable number of queries');
    }
}
