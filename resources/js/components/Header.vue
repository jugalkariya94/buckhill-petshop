<script lang="ts">
import {defineComponent} from 'vue'
import logo from '@/assets/images/Vector.svg';

export default defineComponent({
  data() {
    return {
      logo,
      links: [
        'Products',
        'Promotions',
        'Blog',
      ]
    }
  }

})
</script>


<template>
  <v-app-bar flat color="primary" class="d-flex align-center justify-center">
    <v-container class="mx-auto py-0 fill-height d-flex justify-space-between text-white">
      <v-avatar
          class="d-flex"
          size="32"
      >
        <v-img
            alt="Logo"
            class="shrink mr-2"
            contain
            :src="logo"
            transition="scale-transition"
            width="30"
        />
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
</template>


<style scoped>

</style>
