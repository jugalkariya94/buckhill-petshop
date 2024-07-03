<script lang="ts">
import {defineComponent} from 'vue'

export default defineComponent({
    data() {
        return {
            itemCount: 0
        }
    }
})
</script>


<template>
    <v-btn
        class="mx-2"
        color="white"
        variant="outlined"
    >
        <v-icon icon="mdi-cart"></v-icon>
        Cart ({{ itemCount }})
    </v-btn>

</template>


<style scoped>

</style>
