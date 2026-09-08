<?php

declare(strict_types=1);

namespace App\Components;

use Pulse\Component\Component;
use App\Models\Subscription;

class SubscriptionUpgrade extends Component
{
    public string $currentTier = Subscription::TIER_STARTER;
    public string $selectedTier = Subscription::TIER_PRO;
    public string $billingPeriod = 'annual'; // 'monthly' or 'annual'
    public int $seats = 5;
    public bool $isProcessing = false;
    public ?string $successMessage = null;
    public array $usage = [
        'agent_runs' => 42,
        'queue_jobs' => 4120,
        'fiber_reqs' => 840,
    ];

    public function mount(string $tier = Subscription::TIER_STARTER): void
    {
        $this->currentTier = $tier;
        $this->selectedTier = ($tier === Subscription::TIER_STARTER) ? Subscription::TIER_PRO : $tier;
    }

    public function setPeriod(string $period): void
    {
        $this->billingPeriod = in_array($period, ['monthly', 'annual']) ? $period : 'annual';
    }

    public function selectTier(string $tier): void
    {
        $tiers = Subscription::getTiers();
        if (isset($tiers[$tier])) {
            $this->selectedTier = $tier;
        }
    }

    public function upgradePlan(): void
    {
        $tiers = Subscription::getTiers();
        if (!isset($tiers[$this->selectedTier])) {
            return;
        }

        $this->currentTier = $this->selectedTier;
        $tierName = $tiers[$this->currentTier]['name'];
        $this->successMessage = "Successfully upgraded to {$tierName} plan on {$this->billingPeriod} billing!";
        
        $this->toast("🎉 Upgraded to {$tierName} plan! Features unlocked.", 'success');
    }

    public function render(): string
    {
        $tiers = Subscription::getTiers();
        return view('components.subscription-upgrade', [
            'currentTier' => $this->currentTier,
            'selectedTier' => $this->selectedTier,
            'billingPeriod' => $this->billingPeriod,
            'seats' => $this->seats,
            'tiers' => $tiers,
            'usage' => $this->usage,
            'successMessage' => $this->successMessage,
        ]);
    }
}
