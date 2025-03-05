import React from 'react';
import { useForm, FormProvider } from 'react-hook-form'; // Importando o React Hook Form
import { TextField, Button, Box, Typography } from '@mui/material'; // Componentes do Material-UI
import { toast } from 'react-toastify'; // Para exibir mensagens de sucesso/erro
import 'react-toastify/dist/ReactToastify.css'; // Estilos para o Toast

interface Proprietario {
  name: string;
  email: string;
}

const OwnerForm: React.FC = () => {
  const methods = useForm<Proprietario>(); 

  const registerOwner = async (data: Proprietario) => {
    if (!data.name || !data.email) {
      toast.error('Por favor, preencha todos os campos!');
      return;
    }

    try {
      const response = await fetch('http://127.0.0.1:8000/api/cadastro-imovel', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({ proprietario: data }),
      });

      const result = await response.json();
      if (response.ok) {
        toast.success('Proprietário cadastrado com sucesso!');
        // Limpar campos após sucesso
        methods.reset();
      } else {
        toast.error(result.message || 'Erro ao cadastrar proprietário');
      }
    } catch (error) {
      toast.error('Erro na comunicação com a API');
    }
  };

  return (
    <FormProvider {...methods}>
      <Box
        component="form"
        onSubmit={methods.handleSubmit(registerOwner)} 
        sx={{
          maxWidth: 400,
          mx: 'auto',
          p: 4,
          backgroundColor: 'white',
          boxShadow: 3,
          borderRadius: 2,
        }}
      >
        <Typography variant="h5" gutterBottom>
          Cadastro de Proprietário
        </Typography>

        <TextField
          id="name"
          label="Nome"
          type="text"
          fullWidth
          margin="normal"
          {...methods.register('name', { required: true })}
          placeholder="Nome do Proprietário"
        />

        <TextField
          id="email"
          label="E-mail"
          type="email"
          fullWidth
          margin="normal"
          {...methods.register('email', { required: true })}
          placeholder="Email do Proprietário"
        />

        <Button 
          type="submit" 
          fullWidth 
          variant="contained" 
          color="primary" 
          sx={{ mt: 2 }}
        >
          Cadastrar Proprietário
        </Button>
      </Box>
    </FormProvider>
  );
};

export default OwnerForm;
