import React, { useEffect, useState } from 'react';
import { useForm, FormProvider } from 'react-hook-form'; // Importando o React Hook Form
import { TextField, Button, Box, Typography, MenuItem, Select, InputLabel, FormControl } from '@mui/material'; // Componentes do Material-UI
import { toast } from 'react-toastify'; // Para exibir mensagens de sucesso/erro
import 'react-toastify/dist/ReactToastify.css'; // Estilos para o Toast
import Cleave from 'cleave.js/react'; // Importando o Cleave.js

interface Property {
  endereco: string;
  cidade: string;
  preco?: number;
  proprietario_id: string; // Relacionamento com o proprietário
}

const PropertyForm: React.FC = () => {
  const methods = useForm<Property>(); 
  const [proprietarios, setProprietarios] = useState<any[]>([]); // Estado para armazenar os proprietários

  // Carregar os proprietários da API (supondo que você tenha uma API que retorna os proprietários)
  useEffect(() => {
    const fetchProprietarios = async () => {
      try {
        const response = await fetch('http://127.0.0.1:8000/api/proprietarios');
        const data = await response.json();
        setProprietarios(data); // Supondo que a API retorne um array de proprietários
      } catch (error) {
        toast.error('Erro ao carregar proprietários');
      }
    };
    fetchProprietarios();
  }, []);

  // Função para cadastrar o imóvel
  const cadastrarImovel = async (data: Property) => {
    if (!data.endereco || !data.cidade || !data.proprietario_id) {
      toast.error('Por favor, preencha todos os campos obrigatórios!');
      return;
    }

    try {
      const response = await fetch('http://127.0.0.1:8000/api/cadastro-imovel', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify(data),
      });

      const result = await response.json();
      if (response.ok) {
        toast.success('Imóvel cadastrado com sucesso!');
        methods.reset(); // Limpar campos após sucesso
      } else {
        toast.error(result.message || 'Erro ao cadastrar imóvel');
      }
    } catch (error) {
      toast.error('Erro na comunicação com a API');
    }
  };

  return (
    <FormProvider {...methods}>
      <Box
        component="form"
        onSubmit={methods.handleSubmit(cadastrarImovel)} 
        sx={{
          maxWidth: 500,
          width: '100%',
          mx: 'auto',
          p: 4,
          backgroundColor: 'white',
          boxShadow: 3,
          borderRadius: 2,
        }}
      >
        <Typography variant="h5" gutterBottom>
          Cadastro de Imóvel
        </Typography>

        <TextField
          id="endereco"
          label="Endereço"
          type="text"
          fullWidth
          margin="normal"
          {...methods.register('endereco', { required: true })}
          placeholder="Endereço do Imóvel"
        />

        <TextField
          id="cidade"
          label="Cidade"
          type="text"
          fullWidth
          margin="normal"
          {...methods.register('cidade', { required: true })}
          placeholder="Cidade do Imóvel"
        />

        {/* Campo para preço com a máscara monetária */}
        <TextField
          id="preco"
          label="Preço"
          type="text"
          fullWidth
          margin="normal"
          placeholder="ex:. R$ 1.000,00"
          InputProps={{
            inputComponent: Cleave as any,
            inputProps: {
              options: {
                numeral: true,
                numeralThousandsGroupStyle: 'thousand',
                prefix: 'R$ ',
                delimiter: '.',
                noImmediatePrefix: true,
                rawValueTrimPrefix: false,
              },
            },
          }}
          {...methods.register('preco')}
        />

        <FormControl fullWidth margin="normal">
          <InputLabel id="proprietario-select-label">Proprietário</InputLabel>
          <Select
            labelId="proprietario-select-label"
            id="proprietario_id"
            label="Proprietário"
            {...methods.register('proprietario_id', { required: true })}
          >
            {proprietarios.map((proprietario) => (
              <MenuItem key={proprietario.id} value={proprietario.id}>
                {proprietario.nome}
              </MenuItem>
            ))}
          </Select>
        </FormControl>

        <Button 
          type="submit" 
          fullWidth 
          variant="contained" 
          color="primary" 
          sx={{ mt: 2 }}
        >
          Cadastrar Imóvel
        </Button>
      </Box>
    </FormProvider>
  );
};

export default PropertyForm;
