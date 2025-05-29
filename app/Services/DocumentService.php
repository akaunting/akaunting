<?php

namespace App\Services;

use App\Models\Document\Document;

/**
 * DocumentService provides optimized document loading with all necessary relationships
 * to prevent N+1 queries in templates and views.
 */
class DocumentService
{
    /**
     * Standard relationships needed for document template rendering
     * These relationships are optimized to prevent N+1 queries
     */
    private const TEMPLATE_RELATIONSHIPS = [
        'items.taxes.tax',      // Document items with tax calculations
        'items.item',           // Item details for templates
        'totals',              // Document totals for display
        'contact',             // Contact information
        'currency',            // Currency for formatting
        'category',            // Document category
        'histories',           // Document history for audit trail
        'media'                // Attached files/images
    ];

    /**
     * Additional relationships for show pages
     */
    private const SHOW_RELATIONSHIPS = [
        'transactions',         // Payment/transaction history
        'recurring',           // Recurring document settings
        'children'             // Child documents (recurring)
    ];

    /**
     * Load document with all relationships needed for template rendering
     * Optimized to prevent N+1 queries in document templates
     *
     * @param Document $document
     * @param array $additionalRelationships
     * @return Document
     */
    public function loadForTemplate(Document $document, array $additionalRelationships = []): Document
    {
        $relationships = array_merge(self::TEMPLATE_RELATIONSHIPS, $additionalRelationships);
        
        return $document->load($relationships);
    }

    /**
     * Load document with all relationships needed for show pages
     * Includes template relationships plus show-specific ones
     *
     * @param Document $document
     * @param array $additionalRelationships
     * @return Document
     */
    public function loadForShow(Document $document, array $additionalRelationships = []): Document
    {
        $relationships = array_merge(
            self::TEMPLATE_RELATIONSHIPS,
            self::SHOW_RELATIONSHIPS,
            $additionalRelationships
        );
        
        return $document->load($relationships);
    }

    /**
     * Load minimal relationships for document listing
     * Optimized for index pages with many documents
     *
     * @param Document $document
     * @return Document
     */
    public function loadForIndex(Document $document): Document
    {
        return $document->load([
            'contact',
            'category',
            'currency',
            'last_history'
        ]);
    }

    /**
     * Check if document has all necessary relationships loaded
     * Useful for debugging and ensuring optimal performance
     *
     * @param Document $document
     * @return bool
     */
    public function hasTemplateRelationshipsLoaded(Document $document): bool
    {
        foreach (self::TEMPLATE_RELATIONSHIPS as $relationship) {
            if (!$document->relationLoaded($this->getBaseRelationship($relationship))) {
                return false;
            }
        }
        
        return true;
    }

    /**
     * Get the base relationship name from a nested relationship string
     * e.g., "items.taxes.tax" returns "items"
     *
     * @param string $relationship
     * @return string
     */
    private function getBaseRelationship(string $relationship): string
    {
        return explode('.', $relationship)[0];
    }
} 