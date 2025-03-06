import React, { useState } from 'react';
import { useNavigate } from 'react-router-dom';
import { useForm, FormProvider } from 'react-hook-form';
import { TextField, Button, Box, Typography, CircularProgress } from '@mui/material';
import { toast } from 'react-toastify';
import 'react-toastify/dist/ReactToastify.css'; 
import Cleave from 'cleave.js/react'; 
import { fetchAddressByCEP, formatCPF } from '../utils/utils';
import { get, post } from '../services/api';

interface Property {
  owner: Owner;
  city: string;
  number: string;
  amount: string;
  street: string;
  zipCode: string;
}

interface Owner {
  name: string;
  cpf: string;
  email: string;
}

const PropertyForm: React.FC = () => {
  const navigate = useNavigate();
  const methods = useForm<Property>(); 
  const [isLoading, setIsLoading] = useState<any>(false);

  const registerProperty = async (data: Property) => {
    setIsLoading(true);
    if (!data.street || !data.city || !data.owner) {
      toast.error('Por favor, preencha todos os campos obrigatórios!');
      return;
    }

    try {
      data.amount = data.amount.replace(/\D/g, '');

      const result = await post('/properties', data);

      if (result.status) {
        toast.success('Imóvel cadastrado com sucesso!');
        methods.reset();

        navigate('/');
      } else {
        setIsLoading(false);
        toast.error(result.message || 'Erro ao cadastrar imóvel');
      }
    } catch (err) {
      setIsLoading(false);
      toast.error('Erro na comunicação com a API');
    }
  };

  return (
    <FormProvider {...methods}>
      <Box
        component="form"
        onSubmit={methods.handleSubmit(registerProperty)} 
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
          id="zipCode"
          type="text"
          fullWidth
          margin="normal"
          {...methods.register('zipCode', { required: true })}
          placeholder="CEP"
          onChange={(e) => {
            if (e.target.value.length <= 5) return;

            setIsLoading(true);

            fetchAddressByCEP(e.target.value).then((data) => {
              methods.setValue('city', data.localidade);
              methods.setValue('street', data.logradouro);
            }).finally(() => setIsLoading(false));
          }}
        />

      <TextField
          id="street"
          type="text"
          fullWidth
          margin="normal"
          {...methods.register('street', { required: true })}
          placeholder="Endereço do Imóvel"
        />

        <TextField
          id="city"
          type="text"
          fullWidth
          margin="normal"
          {...methods.register('city', { required: true })}
          placeholder="Cidade do Imóvel"
        />

      <TextField
          id="number"
          type="text"
          fullWidth
          margin="normal"
          {...methods.register('number', { required: true })}
          placeholder="Número"
        />

        <TextField
          id="amount"
          type="text"
          fullWidth
          margin="normal"
          placeholder="Valor do Imóvel"
          InputProps={{
            inputComponent: Cleave as any,
            inputProps: {
              options: {
                numeral: true,
                numeralThousandsGroupStyle: 'thousand',
                prefix: 'R$ ',
                delimiter: '.',
                noImmediatePrefix: false,
                rawValueTrimPrefix: true,
                numeralDecimalMark: ',',
                integerNoGroup: false,
                numeralDecimalScale: 2
              },
            },
          }}
          {...methods.register('amount', { required: true })}
        />

      <TextField
          id="owner.email"
          type="text"
          fullWidth
          margin="normal"
          {...methods.register('owner.email', { required: true })}
          placeholder="Email do Proprietário"
          onChange={(e) => {
            if (e.target.value.length <= 5) return;

            setIsLoading(true);
            get(`/properties/check-owner-email?email=${e.target.value}`).then((data) => {
              if (data.response.length === 0) return;

              methods.setValue('owner.name', data.response.name);
              methods.setValue('owner.cpf', formatCPF(data.response.cpf));
          }).finally(() => setIsLoading(false))
        }
        }
        />

        <TextField
          id="owner.name"
          type="text"
          fullWidth
          margin="normal"
          {...methods.register('owner.name', { required: true })}
          placeholder="Nome do Proprietário"
        />

      <TextField
          id="owner.cpf"
          type="text"
          fullWidth
          margin="normal"
          {...methods.register('owner.cpf', { required: true })}
          placeholder="CPF do Proprietário"
        />

        <Button 
          type="submit" 
          fullWidth 
          variant="contained" 
          color="primary" 
          sx={{ mt: 2 }}
        >
          {
            isLoading ? <CircularProgress color='white' /> : 'Cadastrar Imóvel'
          } 
        </Button>

        <Button 
          variant="contained" 
          sx={{ mt: 1 }}
          fullWidth 
          color="primary" 
          onClick={() => navigate('/')}
        >
          Voltar
        </Button>
      </Box>
    </FormProvider>
  );
};

export default PropertyForm;
