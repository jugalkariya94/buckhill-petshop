import { defineStore } from 'pinia';
import { fetchWrapper } from '@/service/helpers';
import { useRouter } from 'vue-router';

const baseUrl = `${import.meta.env.VITE_API_URL}/user`;

interface User {
    first_name: string;
    last_name: string;
    email: string;
    token: string;
    [key: string]: any;
}

interface AuthState {
    user: User | null;
    returnUrl?: string;
    error: string;
}


export const useAuthStore = defineStore('authStore', {
    state: (): AuthState => ({
        user: JSON.parse(localStorage.getItem('user') || 'null'),
        error: '',
    }),
    actions: {
        async login(email: string, password: string) {
            const router = useRouter();
            try {
                const response = await fetchWrapper.post(`${baseUrl}/login`, { email, password });

                console.log(response);
                // update pinia state
                this.user = {
                    'first_name': response.data.first_name,
                    'last_name': response.data.last_name,
                    'email': response.data.email,
                    'token': response.access_token
                };

                // store user details and jwt in local storage to keep user logged in between page refreshes
                localStorage.setItem('user', JSON.stringify(this.user));

                // redirect to previous url or default to home page
                // router.push(this.returnUrl || '/');
            } catch (error) {
                this.error = error as string;
            }
        },
        async logout() {
            const router = useRouter();
            const response = await fetchWrapper.get(`${baseUrl}/logout`);
            if (!response.ok) {
                console.log('Error logging out');
            }
            this.user = null;
            localStorage.removeItem('user');
            // router.push('/');
        }
    }
});
