const BASE_URL = import.meta.env.VITE_API_URL; 

const request = async (endpoint: string, method: string, body?: any) => {
  const headers = {
    'Content-Type': 'application/json'
  };

  const options: RequestInit = {
    method,
    headers,
    body: body ? JSON.stringify(body) : undefined,
  };

  try {
    const response = await fetch(`${BASE_URL}${endpoint}`, options);
    
    if (!response.ok && response.status === 500) {
      throw new Error(`Erro ao realizar requisição: ${response.statusText}`);
    }

    const data = await response.json();
    return data;
  } catch (error) {
    console.error(error);
    throw error;
  }
};

export const get = (endpoint: string) => {
  return request(endpoint, 'GET');
};

export const post = (endpoint: string, body: any) => {
  return request(endpoint, 'POST', body);
};

export const put = (endpoint: string, body: any) => {
  return request(endpoint, 'PUT', body);
};

export const del = (endpoint: string) => {
  return request(endpoint, 'DELETE');
};