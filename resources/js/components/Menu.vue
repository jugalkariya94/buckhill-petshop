<script lang="ts">
import {defineComponent} from 'vue'

export default defineComponent({
    data() {
        return {
            links: [
                {
                    name: 'Products',
                    route: '/products',
                    icon: 'mdi-chevron-down'
                },
                {
                    name: 'Promotions',
                    route: '/promotions'
                },
                {
                    name: 'Blog',
                    route: '/blog'
                }
            ]
        }
    }

})
</script>


<template>
    <v-row justify="center" no-gutters>
        <v-btn
            v-for="link in links"
            :key="link.name"
            class="mx-2"
            color="white"
            variant="text"
        >
            {{ link.name }} <v-icon :icon="link.icon" v-if="link.icon"></v-icon>
        </v-btn>
    </v-row>
</template>


<style scoped>

</style>
