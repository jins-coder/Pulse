<?php

declare(strict_types=1);

namespace App\Components;

use Pulse\Component\Component;
use Pulse\AI\AgentMesh;
use Pulse\AI\AgentWorker;

class AgentMeshVisualizer extends Component
{
    public string $goal = 'Analyze high-traffic spikes, optimize fiber pool, and generate security audit';
    public array $swarmLog = [];
    public bool $isExecuting = false;
    public string $status = 'Ready for Swarm Task';
    public ?string $latestSynthesis = null;

    public function runSwarm(): void
    {
        $mesh = AgentMesh::getInstance();
        if (empty($mesh->getWorkers())) {
            $mesh->registerDefaultSwarm();
        }

        $this->isExecuting = true;
        $this->status = 'Swarm In Flight...';

        $result = $mesh->delegate($this->goal);
        $this->swarmLog = $result['log'];
        $this->latestSynthesis = $result['synthesis'];
        $this->status = 'Swarm Completed';
        $this->isExecuting = false;

        $this->toast('🤖 Multi-Agent Mesh completed goal successfully!', 'success');
    }

    public function render(): string
    {
        $mesh = AgentMesh::getInstance();
        if (empty($mesh->getWorkers())) {
            $mesh->registerDefaultSwarm();
        }

        return view('components.agent-mesh-visualizer', [
            'goal' => $this->goal,
            'workers' => $mesh->getWorkers(),
            'swarmLog' => $this->swarmLog,
            'latestSynthesis' => $this->latestSynthesis,
            'status' => $this->status,
        ]);
    }
}
