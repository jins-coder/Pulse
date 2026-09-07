<?php

declare(strict_types=1);

namespace App\Components;

use Pulse\Component\Component;

class UserSearch extends Component
{
    public string $query = '';
    public string $selectedCategory = 'All';
    public array $results = [];

    protected static array $database = [
        ['id' => 1, 'name' => 'Sarah Connor', 'role' => 'Principal Engineer', 'category' => 'Engineering', 'avatar' => '👩‍💻'],
        ['id' => 2, 'name' => 'Alex Rivera', 'role' => 'Lead Product Designer', 'category' => 'Design', 'avatar' => '🎨'],
        ['id' => 3, 'name' => 'Michael Chen', 'role' => 'Distributed Systems Architect', 'category' => 'Engineering', 'avatar' => '⚡'],
        ['id' => 4, 'name' => 'Elena Rostova', 'role' => 'DevOps & Cloud Lead', 'category' => 'Operations', 'avatar' => '🚀'],
        ['id' => 5, 'name' => 'Marcus Vance', 'role' => 'VP of Engineering', 'category' => 'Management', 'avatar' => '👔'],
        ['id' => 6, 'name' => 'Aria Montgomery', 'role' => 'Frontend Specialist', 'category' => 'Engineering', 'avatar' => '💻'],
    ];

    public function mount(): void
    {
        $this->filterUsers();
    }

    public function updated(string $property, mixed $value): void
    {
        $this->filterUsers();
    }

    public function setCategory(string $category): void
    {
        $this->selectedCategory = $category;
        $this->filterUsers();
    }

    public function clearSearch(): void
    {
        $this->query = '';
        $this->selectedCategory = 'All';
        $this->filterUsers();
    }

    protected function filterUsers(): void
    {
        $this->results = array_values(array_filter(self::$database, function ($user) {
            $matchesQuery = empty($this->query) || 
                str_contains(strtolower($user['name']), strtolower($this->query)) ||
                str_contains(strtolower($user['role']), strtolower($this->query));

            $matchesCategory = $this->selectedCategory === 'All' || 
                $user['category'] === $this->selectedCategory;

            return $matchesQuery && $matchesCategory;
        }));
    }

    public function render(): string
    {
        return view('components.user-search', [
            'query' => $this->query,
            'selectedCategory' => $this->selectedCategory,
            'results' => $this->results,
            'total' => count($this->results),
        ]);
    }
}
