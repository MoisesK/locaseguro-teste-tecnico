export function formatCPF(cpf: string): string {
    const cleaned = cpf.replace(/\D/g, '');
    
    if (cleaned.length !== 11) {
      throw new Error('CPF deve ter 11 dígitos');
    }
  
    return cleaned.replace(
      /(\d{3})(\d{3})(\d{3})(\d{2})/,
      '$1.$2.$3-$4'
    );
  
  }
  
  export function formatCurrency(value: number): string {
    const formattedValue = value / 100;

    return new Intl.NumberFormat('pt-BR', {
      style: 'currency',
      currency: 'BRL',
    }).format(formattedValue);
  }
  
  export async function fetchAddressByCEP(cep: string): Promise<any> {
    try {
      const response = await fetch(`https://viacep.com.br/ws/${cep.replace(/\D/g, '')}/json/`);
  
      if (!response.ok) {
        throw new Error('Erro ao buscar o CEP');
      }
  
      const data = await response.json();
  
      if (data.erro) {
        throw new Error('CEP não encontrado');
      }
  
      return data;
    } catch (error) {
      throw new Error(error instanceof Error ? error.message : 'Erro desconhecido');
    }
  }
  