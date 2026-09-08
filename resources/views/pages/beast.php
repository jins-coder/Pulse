<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <!-- Breadcrumb & Header -->
    <div class="mb-8">
        <nav class="flex items-center gap-2 text-xs font-mono text-slate-400 mb-2">
            <a href="/" class="hover:text-amber-400 transition">Pulse v4.0</a>
            <span>/</span>
            <span class="text-amber-400 font-semibold">Beast Core Engine</span>
        </nav>
        <h1 class="text-3xl font-black text-white tracking-tight">
            ⚡ Beast Core: Hardware SIMD, Rust Bridge & In-Memory Store
        </h1>
        <p class="text-sm text-slate-400 mt-1">
            Zero-syscall async architectures, embedded vector search, FlatPack binary serialization, and Rust C-ABI integration.
        </p>
    </div>

    <?= component(App\Components\BeastVisualizer::class) ?>
</div>
