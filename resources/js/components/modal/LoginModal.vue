<script lang="ts">
import {defineComponent} from 'vue'
import logo from '@/assets/images/Vector.svg';
import {emailRules} from "@/service/validation/rules/emailRules";
import {passwordRules} from "@/service/validation/rules/passwordRules";
import {useAuthStore} from "@/store/authStore";
export default defineComponent({
    name: "LoginModal",
    data() {
        return {
            valid: false,
            email: '',
            password: '',
            isLoginPopupVisible: false,
            logo,
            emailRules,
            passwordRules,
            error: ''
        };
    },
    methods: {
        login() {
            const store = useAuthStore();

            if (this.valid) {
                store.login(this.email, this.password);
                if (store.error !== null || store.error !== undefined || store.error !== '') {
                    this.error = store.error;
                } else {
                    this.hideLoginPopup();
                }
            }
        },
        hideLoginPopup() {
            this.isLoginPopupVisible = false;
        },
        showLoginPopup() {
            this.isLoginPopupVisible = true;
        }
    }
});
</script>

<template>
    <v-dialog v-model="isLoginPopupVisible" max-width="500px">
        <template v-slot:activator="{ on }">
            <v-btn @click="showLoginPopup"
                   variant="outlined"
                   class="mx-2"
                   color="white">Login
            </v-btn>
        </template>
        <template v-slot:default="{ isActive }">
            <v-card>
                <v-card-title class="align-center">
                    <v-avatar size="100" class="avatar-with-text flex-column mx-auto">
                        <v-img class="d-flex flex-0-0" width="50" :src="logo" :cover="false"></v-img>
                        <div class="d-flex avatar-text text-center">petson.</div>
                    </v-avatar>
                </v-card-title>
                <v-card-text>
                    <v-alert
                        v-if="error"
                        title="Error while logging in"
                        :text="error"
                        type="error"
                        variant="outlined"
                        class="my-5"
                    ></v-alert>
                    <v-spacer></v-spacer>
                    <v-form fast-fail @submit.prevent v-model="valid">


                        <v-text-field
                            label="Email"
                            type="email"
                            variant="outlined"
                            v-model="email"
                            :required="true"
                            :rules="emailRules"
                        ></v-text-field>
                        <v-text-field
                            label="Password"
                            type="password"
                            variant="outlined"
                            v-model="password"
                            :rules="passwordRules"
                        ></v-text-field>
                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn type="submit" class="bg-primary text-white" variant="elevated" @click="login">Login</v-btn>
                            <v-btn variant="text" @click="hideLoginPopup">Cancel</v-btn>
                        </v-card-actions>
                    </v-form>
                </v-card-text>
            </v-card>
        </template>
    </v-dialog>
</template>

<style scoped>
.avatar-with-text {
    position: relative;
    display: flex;
    justify-content: center;
    align-items: center;
    background-color: #4EC690;
    bottom: 10%;
    text-align: center;
}

.avatar-with-text .v-img {
    height: 50% !important;
}

.text-white {
    color: white !important;
}

.avatar-text {
    bottom: 20px;
    text-align: center;
    color: white;
}
</style>
