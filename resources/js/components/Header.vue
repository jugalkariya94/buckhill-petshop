<script lang="ts">
import {defineComponent} from 'vue'
import logo from '@/assets/images/Vector.svg';
import brandName from '@/assets/images/petson..svg';
import Menu from "@/components/Menu.vue";
import CartButton from "@/components/CartButton.vue";
import LoginButton from "@/components/LoginButton.vue";

export default defineComponent({
    components: {LoginButton, CartButton, Menu},
    data() {
        return {
            logo,
            brandName,
        }
    }

})
</script>


<template>
    <v-app-bar flat color="primary" class="d-flex align-center justify-center">
        <v-container class="mx-auto py-0 fill-height d-flex justify-space-between text-white">
            <v-row justify="center" no-gutters>
                <v-app-bar-nav-icon class="mx-20">
                    <v-img
                        alt="Logo"
                        class="d-flex shrink mr-2 fill-height"
                        contain
                        :src="logo"
                        transition="scale-transition"
                        width="25"
                    />
                    <span class="text-lowercase">petson.</span>
                </v-app-bar-nav-icon>
            </v-row>
            <v-spacer></v-spacer>
            <Menu></Menu>
            <v-spacer></v-spacer>
            <v-row justify="end" no-gutters>
                <cart-button></cart-button>
                <login-button></login-button>
                <v-avatar class="d-flex" size="32">
                    <v-img src="https://api.dicebear.com/7.x/pixel-art/svg"></v-img>
                </v-avatar>
            </v-row>
        </v-container>
    </v-app-bar>
</template>


<style scoped>

</style>
