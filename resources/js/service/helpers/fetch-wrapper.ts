import { useAuthStore } from '@/store/authStore';

export const fetchWrapper = {
    get: request('GET'),
    post: request('POST'),
    put: request('PUT'),
    delete: request('DELETE')
};

function request(method: string) {
    return (url: string, body?: any) => {
        const requestOptions: RequestInit = {
            method,
            headers: authHeader(url)
        };
        if (body) {
            requestOptions.headers = {
                ...requestOptions.headers,
                'Content-Type': 'application/json'
            };
            requestOptions.body = JSON.stringify(body);
        }
        return fetch(url, requestOptions).then(handleResponse);
    }
}

// helper functions

function authHeader(url: string): HeadersInit {
    const { user } = useAuthStore();
    const isLoggedIn = !!user?.token;
    const isApiUrl = url.startsWith(import.meta.env.VITE_API_URL);
    if (isLoggedIn && isApiUrl) {
        return { Authorization: `Bearer ${user.token}` };
    } else {
        return {};
    }
}

async function handleResponse(response: Response) {
    const isJson = response.headers?.get('content-type')?.includes('application/json');
    const data = isJson ? await response.json() : null;

    if (!response.ok) {
        const { user, logout } = useAuthStore();
        if ([401, 403].includes(response.status) && user) {
            logout();
        }

        const error = (data && data.error) || response.status;
        return Promise.reject(error);
    }

    return data;
}
