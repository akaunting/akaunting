<?php

namespace Sentry\Laravel\Tracing\Integrations;

use GraphQL\Language\AST\DocumentNode;
use GraphQL\Language\AST\OperationDefinitionNode;
use Illuminate\Contracts\Events\Dispatcher as EventDispatcher;
use Illuminate\Support\Str;
use Nuwave\Lighthouse\Events\EndExecution;
use Nuwave\Lighthouse\Events\EndRequest;
use Nuwave\Lighthouse\Events\StartExecution;
use Nuwave\Lighthouse\Events\StartRequest;
use Sentry\Event;
use Sentry\Integration\IntegrationInterface;
use Sentry\Laravel\Integration;
use Sentry\Options;
use Sentry\SentrySdk;
use Sentry\State\Scope;
use Sentry\Tracing\SpanContext;
use Sentry\Tracing\TransactionSource;

class LighthouseIntegration implements IntegrationInterface
{
    /** @var array<int, array{?string, \GraphQL\Language\AST\OperationDefinitionNode}> */
    private $operations;

    /** @var \Sentry\Tracing\Span|null */
    private $previousSpan;

    /** @var \Sentry\Tracing\Span|null */
    private $requestSpan;

    /** @var \Sentry\Tracing\Span|null */
    private $operationSpan;

    /** @var \Illuminate\Contracts\Events\Dispatcher */
    private $eventDispatcher;

    /**
     * Indicates if, when building the transaction name, the operation name should be ignored.
     *
     * @var bool
     */
    private $ignoreOperationName;

    public function __construct(EventDispatcher $eventDispatcher, bool $ignoreOperationName = false)
    {
        $this->eventDispatcher     = $eventDispatcher;
        $this->ignoreOperationName = $ignoreOperationName;
    }

    public function setupOnce(): void
    {
        if (!$this->isApplicable()) {
            return;
        }

        $this->eventDispatcher->listen(StartRequest::class, [$this, 'handleStartRequest']);
        $this->eventDispatcher->listen(StartExecution::class, [$this, 'handleStartExecution']);
        $this->eventDispatcher->listen(EndExecution::class, [$this, 'handleEndExecution']);
        $this->eventDispatcher->listen(EndRequest::class, [$this, 'handleEndRequest']);

        Scope::addGlobalEventProcessor(function (Event $event): Event {
            $currentHub = SentrySdk::getCurrentHub();
            $integration = $currentHub->getIntegration(self::class);
            $client = $currentHub->getClient();

            // The client bound to the current hub, if any, could not have this
            // integration enabled. If this is the case, bail out
            if (null === $integration || null === $client) {
                return $event;
            }

            $this->processEvent($event, $client->getOptions());

            return $event;
        });
    }

    private function processEvent(Event $event, Options $options): void
    {
        // Detect if we are processing a GraphQL request, if not skip processing the event
        if (!Str::startsWith($event->getTransaction(), 'lighthouse?')) {
            return;
        }

        $requestData = $event->getRequest();

        // Make sure we have the request data and it contains the query
        if (!isset($requestData['data']['query'])) {
            return;
        }

        // https://develop.sentry.dev/sdk/features/#graphql-client-integrations
        $requestData['api_target'] = 'graphql';

        $event->setRequest($requestData);
    }

    public function handleStartRequest(StartRequest $startRequest): void
    {
        $this->previousSpan = SentrySdk::getCurrentHub()->getSpan();

        if ($this->previousSpan === null) {
            return;
        }

        $context = new SpanContext;
        $context->setOp('graphql.request');

        $this->operations    = [];
        $this->requestSpan   = $this->previousSpan->startChild($context);
        $this->operationSpan = null;

        SentrySdk::getCurrentHub()->setSpan($this->requestSpan);
    }

    public function handleStartExecution(StartExecution $startExecution): void
    {
        if ($this->requestSpan === null) {
            return;
        }

        if (!$startExecution->query instanceof DocumentNode) {
            return;
        }

        /** @var \GraphQL\Language\AST\OperationDefinitionNode|null $operationDefinition */
        $operationDefinition = $startExecution->query->definitions[0] ?? null;

        if (!$operationDefinition instanceof OperationDefinitionNode) {
            return;
        }

        $this->operations[] = [$startExecution->operationName ?? null, $operationDefinition];

        $this->updateTransactionName();

        $context = new SpanContext;
        $context->setOp("graphql.{$operationDefinition->operation}");

        $this->operationSpan = $this->requestSpan->startChild($context);

        SentrySdk::getCurrentHub()->setSpan($this->operationSpan);
    }

    public function handleEndExecution(EndExecution $endExecution): void
    {
        if ($this->operationSpan === null) {
            return;
        }

        $this->operationSpan->finish();
        $this->operationSpan = null;

        SentrySdk::getCurrentHub()->setSpan($this->requestSpan);
    }

    public function handleEndRequest(EndRequest $endRequest): void
    {
        if ($this->requestSpan === null) {
            return;
        }

        $this->requestSpan->finish();
        $this->requestSpan = null;

        SentrySdk::getCurrentHub()->setSpan($this->previousSpan);
        $this->previousSpan = null;

        $this->operations = [];
    }

    private function updateTransactionName(): void
    {
        $transaction = SentrySdk::getCurrentHub()->getTransaction();

        if ($transaction === null) {
            return;
        }

        $groupedOperations = [];

        foreach ($this->operations as [$operationName, $operation]) {
            if (!isset($groupedOperations[$operation->operation])) {
                $groupedOperations[$operation->operation] = [];
            }

            if ($operationName === null || $this->ignoreOperationName) {
                $groupedOperations[$operation->operation] = array_merge(
                    $groupedOperations[$operation->operation],
                    $this->extractOperationNames($operation)
                );
            } else {
                $groupedOperations[$operation->operation][] = $operationName;
            }
        }

        if (empty($groupedOperations)) {
            return;
        }

        array_walk($groupedOperations, static function (&$operations, string $operationType) {
            sort($operations, SORT_STRING);

            $operations = "{$operationType}{" . implode(',', $operations) . '}';
        });

        ksort($groupedOperations, SORT_STRING);

        $transactionName = 'lighthouse?' . implode('&', $groupedOperations);

        $transaction->setName($transactionName);
        $transaction->getMetadata()->setSource(TransactionSource::custom());

        Integration::setTransaction($transactionName);
    }

    /**
     * @return array<int, string>
     */
    private function extractOperationNames(OperationDefinitionNode $operation): array
    {
        if (!$this->ignoreOperationName && $operation->name !== null) {
            return [$operation->name->value];
        }

        $selectionSet = [];

        /** @var \GraphQL\Language\AST\FieldNode $selection */
        foreach ($operation->selectionSet->selections as $selection) {
            // Not respecting aliases because they are only relevant for clients
            // and the tracing we extract here is targeted at server developers.
            $selectionSet[] = $selection->name->value;
        }

        sort($selectionSet, SORT_STRING);

        return $selectionSet;
    }

    private function isApplicable(): bool
    {
        if (!class_exists(StartRequest::class) || !class_exists(StartExecution::class)) {
            return false;
        }

        return property_exists(StartExecution::class, 'query');
    }
}
