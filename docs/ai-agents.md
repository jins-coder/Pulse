# AI Tool-Calling Agents — Pulse Documentation ⚡

- [Introduction](#introduction)
- [Defining AI Tools with Attributes](#defining-ai-tools-with-attributes)
- [Extracting Tool Schemas for LLMs](#extracting-tool-schemas-for-llms)
- [Executing Agent Workflows](#executing-agent-workflows)

---

## Introduction

Pulse features native support for Large Language Model (LLM) tool-calling (also known as function calling) without requiring complex Python bridges or external SDKs.

Using native PHP 8 attributes (`#[AiTool]`), any method in your codebase can be decorated and safely executed by AI agents (OpenAI GPT-4o, Anthropic Claude 3.5, Google Gemini 1.5, DeepSeek).

---

## Defining AI Tools with Attributes

Decorate any method on a service class with `#[AiTool]`:

```php
namespace App\Services;

use Pulse\AI\AiTool;
use App\Models\Product;

class InventoryAgent
{
    #[AiTool(description: 'Checks real-time inventory count and warehouse location')]
    public function checkStock(int $productId): array
    {
        $product = Product::find($productId);

        return [
            'product_id' => $productId,
            'name'       => $product?->name ?? 'Unknown',
            'stock'      => $product?->stock_count ?? 0,
            'available'  => ($product?->stock_count ?? 0) > 0
        ];
    }
}
```

---

## Extracting Tool Schemas for LLMs

Pulse automatically inspects method signatures, parameter types, docblocks, and attribute descriptions to output standardized OpenAI/Anthropic/Gemini compatible JSON schemas:

```php
use Pulse\AI\Agent;
use App\Services\InventoryAgent;

// Generates complete OpenAI-compatible function schema definitions
$tools = Agent::extractTools(InventoryAgent::class);

/*
[
    {
        "type": "function",
        "function": {
            "name": "checkStock",
            "description": "Checks real-time inventory count and warehouse location",
            "parameters": {
                "type": "object",
                "properties": {
                    "productId": { "type": "integer" }
                },
                "required": ["productId"]
            }
        }
    }
]
*/
```
