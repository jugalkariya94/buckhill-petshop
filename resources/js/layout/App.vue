<template>
    <v-app>
        <v-app-bar flat color="primary" class="d-flex align-center justify-center">
            <v-container class="mx-auto py-0 fill-height d-flex justify-space-between text-white">
                <v-avatar
                    class="d-flex"
                    size="32"
                >
                </v-avatar>

                <v-row justify="center" no-gutters>
                    <v-btn
                        v-for="link in links"
                        :key="link"
                        class="mx-2"
                        color="white"
                        rounded="xl"
                        variant="text"
                    >
                        {{ link }}
                    </v-btn>
                </v-row>
                <v-spacer></v-spacer>
            </v-container>
        </v-app-bar>

    </v-app>
</template>

<script setup>
const links = [
    'Products',
    'Promotions',
    'Blog',
]
</script>

<script>
export default {
    data: () => ({
        links: [
            'Products',
            'Promotions',
            'Blog',
        ],

    }),

}
</script>
