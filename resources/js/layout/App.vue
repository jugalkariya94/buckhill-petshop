<script setup lang="ts">
import Header from "@/components/Header.vue";

</script>

<template>
    <v-app>
        <v-main>
            <Header></Header>
            <v-container>
                <router-view></router-view>
            </v-container>
        </v-main>
    </v-app>
</template>

<script>

</script>
