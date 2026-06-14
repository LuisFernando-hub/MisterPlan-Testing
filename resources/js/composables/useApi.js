import { ref } from 'vue';

export function useApi() {
    const loading = ref(false);
    const error = ref(null);

    const request = async (url, options = {}) => {
        loading.value = true;
        error.value = null;

        try {
            const response = await fetch(url, {
                headers: {
                    Accept: 'application/json',
                    'Content-Type': 'application/json',
                    ...options.headers,
                },
                ...options,
            });

            const payload = await response.json().catch(() => ({}));

            if (!response.ok || payload.success === false) {
                const validationErrors = payload.errors
                    ? Object.values(payload.errors).flat().join(' ')
                    : null;

                throw new Error(validationErrors || payload.error || payload.message || 'Erro ao processar a requisicao.');
            }

            return payload;
        } catch (requestError) {
            error.value = requestError.message || 'Erro inesperado.';
            throw requestError;
        } finally {
            loading.value = false;
        }
    };

    const get = (url, params = {}) => {
        const query = new URLSearchParams();

        Object.entries(params).forEach(([key, value]) => {
            if (value !== null && value !== undefined && value !== '') {
                query.append(key, value);
            }
        });

        const queryString = query.toString();
        return request(queryString ? `${url}?${queryString}` : url);
    };

    const post = (url, data = {}) => request(url, {
        method: 'POST',
        body: JSON.stringify(data),
    });

    const put = (url, data = {}) => request(url, {
        method: 'PUT',
        body: JSON.stringify(data),
    });

    return {
        loading,
        error,
        get,
        post,
        put,
        request,
    };
}
