<template>
    <div v-if="visible" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/80 p-4 sm:p-6">
        <div class="absolute inset-0" @click="close"></div>
        <div class="relative w-full max-w-3xl overflow-hidden rounded-3xl border border-slate-800 bg-slate-900 shadow-2xl shadow-slate-950/50">
            <div class="flex items-center justify-between border-b border-slate-800 px-6 py-4">
                <div>
                    <h2 class="text-xl font-semibold text-white">Project Details</h2>
                    <p class="text-sm text-slate-400">Loaded from GitHub project metadata.</p>
                </div>
                <button @click="close" class="rounded-full bg-slate-800 px-3 py-2 text-slate-300 transition hover:bg-slate-700">Close</button>
            </div>

            <div class="p-6">
                <template v-if="loading">
                    <div class="rounded-3xl border border-slate-800 bg-slate-950/80 p-10 text-center text-slate-400">
                        Loading project details…
                    </div>
                </template>

                <template v-else-if="error">
                    <div class="rounded-3xl border border-rose-700 bg-rose-950/60 p-8 text-center text-rose-200">
                        <p class="font-semibold">Unable to load the project.</p>
                        <p class="mt-2 text-sm">{{ error }}</p>
                    </div>
                </template>

                <template v-else-if="project">
                    <div class="space-y-6">
                        <div>
                            <h3 class="text-3xl font-semibold text-white">{{ project.name }}</h3>
                            <p class="mt-3 text-slate-300">{{ project.description || 'No description available.' }}</p>
                        </div>

                        <div class="flex flex-wrap items-center gap-3">
                            <a :href="project.url" target="_blank" rel="noopener noreferrer" class="rounded-lg bg-sky-500 px-4 py-2 text-sm font-semibold text-slate-950 transition hover:bg-sky-400">
                                View on GitHub
                            </a>
                            <span class="text-sm text-slate-400">Repository ID: {{ project.repoId }}</span>
                        </div>

                        <div class="grid gap-4 sm:grid-cols-2">
                            <div class="rounded-3xl border border-slate-800 bg-slate-950/80 p-6">
                                <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Stars</p>
                                <p class="mt-3 text-3xl font-semibold text-white">{{ project.num_stars.toLocaleString() }}</p>
                            </div>
                            <div class="rounded-3xl border border-slate-800 bg-slate-950/80 p-6">
                                <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Created</p>
                                <p class="mt-3 text-3xl font-semibold text-white">{{ project.created_date || 'Unknown' }}</p>
                            </div>
                            <div class="rounded-3xl border border-slate-800 bg-slate-950/80 p-6">
                                <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Last pushed</p>
                                <p class="mt-3 text-3xl font-semibold text-white">{{ project.last_push_date || 'Unknown' }}</p>
                            </div>
                            <div class="rounded-3xl border border-slate-800 bg-slate-950/80 p-6">
                                <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Repository ID</p>
                                <p class="mt-3 text-3xl font-semibold text-white">{{ project.repoId }}</p>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>
</template>

<script setup>
import { reactive } from 'vue';

const state = reactive({
    visible: false,
    loading: false,
    error: null,
    project: null,
});

function close() {
    state.visible = false;
    state.project = null;
    state.error = null;
}

async function open(url) {
    state.visible = true;
    state.loading = true;
    state.error = null;
    state.project = null;

    try {
        const response = await fetch(url, {
            headers: {
                Accept: 'application/json',
            },
        });

        if (!response.ok) {
            throw new Error(`Request failed with status ${response.status}`);
        }

        const data = await response.json();
        state.project = data.project;
    } catch (err) {
        state.error = err instanceof Error ? err.message : String(err);
    } finally {
        state.loading = false;
    }
}

const expose = {
    open,
};

defineExpose(expose);
</script>
