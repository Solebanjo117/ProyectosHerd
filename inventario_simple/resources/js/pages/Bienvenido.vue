<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
</script>
<template>
    <div class="min-h-screen flex flex-col items-center justify-center bg-gradient-to-br from-[#fdf6e3] to-[#f5f7fa] dark:from-[#18181b] dark:to-[#23272f] p-6 text-[#1b1b18] dark:text-[#EDEDEC]">
        
        <div class="w-full max-w-xl bg-white/90 rounded-2xl shadow-lg p-10 flex flex-col items-center dark:bg-[#23272f]">
            <h1 class="text-3xl font-bold mb-4 text-center">Bienvenido al <span class="text-blue-600 dark:text-blue-400">Sistema de Inventario</span></h1>
            <p class="text-lg text-center mb-6">Gestiona tus inventarios de manera <span class="font-semibold text-green-600 dark:text-green-400">eficiente</span> y <span class="font-semibold text-purple-600 dark:text-purple-400">sencilla</span>.</p>
             <Link
                    v-if="$page.props.auth.user"
                    :href="route('dashboard')"
                    class="inline-block rounded-full border border-[#19140035] px-6 py-2 text-base font-semibold shadow-sm bg-white/80 hover:bg-[#f5f5f5] transition dark:border-[#3E3E3A] dark:bg-[#23272f] dark:hover:bg-[#18181b]"
                >
                    Ir al inventario
                </Link>
                <template v-else>
                    <Link
                        :href="route('login')"
                        class="inline-block rounded-full border border-transparent px-6 py-2 text-base font-semibold shadow-sm bg-[#f5f5f5] hover:bg-white/80 transition dark:bg-[#23272f] dark:hover:bg-[#18181b]"
                    >
                        Iniciar sesión
                    </Link>
                    <Link
                        :href="route('register')"
                        class="inline-block rounded-full border border-[#19140035] px-6 py-2 text-base font-semibold shadow-sm bg-white/80 hover:bg-[#f5f5f5] transition dark:border-[#3E3E3A] dark:bg-[#23272f] dark:hover:bg-[#18181b]"
                    >
                        Registrarse
                    </Link>
                </template>
        </div>
    </div>
</template>
