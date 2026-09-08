<?php

declare(strict_types=1);

namespace Pulse\AI;

use Pulse\Telemetry\Tracer;

/**
 * Multi-Agent Workflow Pipeline orchestrating sequential and parallel agent execution stages.
 */
class WorkflowPipeline
{
    protected string $name;
    protected array $stages = [];

    public function __construct(string $name = 'DefaultPipeline')
    {
        $this->name = $name;
    }

    public static function create(string $name): self
    {
        return new self($name);
    }

    public function addStage(string $stageName, AgentWorker $worker, ?callable $transformer = null): self
    {
        $this->stages[] = [
            'name' => $stageName,
            'worker' => $worker,
            'transformer' => $transformer,
        ];
        return $this;
    }

    public function run(string $initialInput, array $context = []): array
    {
        $tracer = Tracer::getInstance();
        $span = $tracer->startSpan("pipeline.{$this->name}", 'INTERNAL', [
            'pipeline.name' => $this->name,
            'pipeline.stages_count' => count($this->stages),
        ]);

        $stageResults = [];
        $currentInput = $initialInput;

        try {
            foreach ($this->stages as $index => $stage) {
                $stageName = $stage['name'];
                /** @var AgentWorker $worker */
                $worker = $stage['worker'];
                $transformer = $stage['transformer'];

                $stageSpan = $tracer->startSpan("stage.{$stageName}", 'INTERNAL', [
                    'stage.index' => $index,
                    'worker.id' => $worker->id,
                    'worker.role' => $worker->role,
                ]);

                $result = $worker->execute($currentInput, $context);

                if ($transformer !== null) {
                    $currentInput = (string)$transformer($result['result'], $result);
                } else {
                    $currentInput = (string)$result['result'];
                }

                $stageSpan->end();

                $stageResults[] = [
                    'stage' => $stageName,
                    'worker' => $worker->name,
                    'role' => $worker->role,
                    'output' => $result,
                ];
            }

            $span->setStatus('OK');
            return [
                'pipeline' => $this->name,
                'status' => 'completed',
                'stages' => $stageResults,
                'final_output' => $currentInput,
                'timestamp' => microtime(true),
            ];
        } catch (\Throwable $e) {
            $span->recordException($e);
            throw $e;
        } finally {
            $tracer->endSpan($span);
        }
    }
}
