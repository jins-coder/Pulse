<?php

declare(strict_types=1);

namespace Pulse\AI;

use Pulse\Telemetry\Tracer;

/**
 * Autonomous Multi-Agent Mesh for Pulse Framework v3.0 (Horizon).
 * Coordinates swarm intelligence, role delegation, shared blackboard memory, and collaborative agent workflows.
 */
class AgentMesh
{
    private static ?AgentMesh $instance = null;
    /** @var array<string, AgentWorker> */
    protected array $workers = [];
    protected array $blackboard = [];
    protected array $delegationHistory = [];

    public function __construct()
    {
        self::$instance = $this;
    }

    public static function getInstance(): AgentMesh
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function registerWorker(AgentWorker $worker): self
    {
        $this->workers[$worker->id] = $worker;
        return $this;
    }

    public function getWorker(string $workerId): ?AgentWorker
    {
        return $this->workers[$workerId] ?? null;
    }

    /**
     * @return array<string, AgentWorker>
     */
    public function getWorkers(): array
    {
        return $this->workers;
    }

    public function setBlackboard(string $key, mixed $value, string $setBy = 'supervisor'): void
    {
        $this->blackboard[$key] = [
            'value' => $value,
            'set_by' => $setBy,
            'updated_at' => microtime(true),
        ];
    }

    public function getBlackboard(string $key, mixed $default = null): mixed
    {
        return $this->blackboard[$key]['value'] ?? $default;
    }

    public function getAllBlackboard(): array
    {
        return $this->blackboard;
    }

    /**
     * Delegate a goal across the agent mesh using supervisor-worker swarm decomposition.
     */
    public function delegate(string $goal, array $context = []): array
    {
        $tracer = Tracer::getInstance();
        $span = $tracer->startSpan("agent_mesh.delegate", 'INTERNAL', [
            'goal' => $goal,
            'workers_count' => count($this->workers),
        ]);

        $executionLog = [];

        try {
            // If no workers registered, register default swarm
            if (empty($this->workers)) {
                $this->registerDefaultSwarm();
            }

            // Step 1: Supervisor decomposes goal
            $this->setBlackboard('active_goal', $goal, 'supervisor');
            $executionLog[] = [
                'type' => 'plan',
                'agent' => 'Supervisor Agent',
                'message' => "Decomposed goal into specialized sub-tasks across mesh.",
                'timestamp' => microtime(true),
            ];

            // Step 2: Route sub-tasks to specialized workers
            foreach ($this->workers as $worker) {
                $workerSpan = $tracer->startSpan("agent_mesh.worker.{$worker->id}", 'INTERNAL', [
                    'worker.role' => $worker->role,
                ]);

                $taskDesc = "Execute {$worker->role} responsibilities for: {$goal}";
                $result = $worker->execute($taskDesc, array_merge($context, ['blackboard' => $this->blackboard]));
                $this->setBlackboard("worker_{$worker->id}_result", $result['result'], $worker->name);

                $workerSpan->end();

                $executionLog[] = [
                    'type' => 'agent_execution',
                    'agent' => $worker->name,
                    'role' => $worker->role,
                    'result' => $result['result'],
                    'timestamp' => microtime(true),
                ];
            }

            // Step 3: Synthesis
            $summary = "Swarm completed execution of goal: '{$goal}' with " . count($this->workers) . " autonomous agents.";
            $this->setBlackboard('latest_synthesis', $summary, 'supervisor');

            $record = [
                'id' => 'del_' . bin2hex(random_bytes(6)),
                'goal' => $goal,
                'status' => 'completed',
                'log' => $executionLog,
                'synthesis' => $summary,
                'timestamp' => microtime(true),
            ];

            $this->delegationHistory[] = $record;
            $span->setStatus('OK');

            return $record;
        } catch (\Throwable $e) {
            $span->recordException($e);
            throw $e;
        } finally {
            $tracer->endSpan($span);
        }
    }

    public function registerDefaultSwarm(): void
    {
        $this->registerWorker(new AgentWorker(
            id: 'architect',
            name: 'Architect Agent',
            role: 'System Design & Structure',
            systemPrompt: 'You design distributed systems, data models, and API interfaces.',
            tools: ['analyzeArchitecture', 'generateInterfaceSchema']
        ));

        $this->registerWorker(new AgentWorker(
            id: 'coder',
            name: 'Code Synthesizer',
            role: 'Code Generation & Optimization',
            systemPrompt: 'You generate high-performance reactive PHP and PulseX components.',
            tools: ['compilePulseX', 'verifyTypes']
        ));

        $this->registerWorker(new AgentWorker(
            id: 'qa_reviewer',
            name: 'QA & Security Verifier',
            role: 'Security Audit & Test Verification',
            systemPrompt: 'You verify cryptographic HMAC state signatures and code safety.',
            tools: ['verifyHmacSignatures', 'runDiagnostics']
        ));
    }

    public function getDelegationHistory(): array
    {
        return $this->delegationHistory;
    }

    public function toArray(): array
    {
        return [
            'workers' => array_map(fn(AgentWorker $w) => $w->toArray(), array_values($this->workers)),
            'blackboard' => $this->blackboard,
            'delegations_count' => count($this->delegationHistory),
        ];
    }
}
