import React, { useEffect, useState } from 'react';
import { Box, Typography, Table, TableBody, TableCell, TableContainer, TableHead, TableRow, Paper, CircularProgress } from '@mui/material'; // Importando componentes do MUI
import { toast } from 'react-toastify'; // Para exibir mensagens de sucesso/erro
import 'react-toastify/dist/ReactToastify.css'; // Estilos para o Toast

interface Property {
  id: string;
  endereco: string;
  cidade: string;
  preco: string; // Representando preço como string para mostrar com formatação
  proprietario_nome: string; // Nome do proprietário associado
}

const PropertyList: React.FC = () => {
  const [properties, setProperties] = useState<Property[]>([]); // Estado para armazenar as propriedades
  const [loading, setLoading] = useState<boolean>(true); // Estado de carregamento
  const [error, setError] = useState<string>(''); // Para mostrar erros

  // Função para mockar dados de propriedades (simulando a resposta da API)
  useEffect(() => {
    const fetchMockProperties = async () => {
      setLoading(true);

      // Mock de dados de propriedades
      const mockData: Property[] = [
        {
          id: '1',
          endereco: 'Rua ABC, 123',
          cidade: 'São Paulo',
          preco: 'R$ 500.000',
          proprietario_nome: 'João Silva',
        },
        {
          id: '2',
          endereco: 'Avenida Paulista, 5000',
          cidade: 'São Paulo',
          preco: 'R$ 1.200.000',
          proprietario_nome: 'Maria Oliveira',
        },
        {
          id: '3',
          endereco: 'Rua das Flores, 45',
          cidade: 'Rio de Janeiro',
          preco: 'R$ 850.000',
          proprietario_nome: 'Carlos Souza',
        },
        {
          id: '4',
          endereco: 'Rua das Palmeiras, 78',
          cidade: 'Belo Horizonte',
          preco: 'R$ 750.000',
          proprietario_nome: 'Ana Costa',
        },
      ];

      // Simulando um delay para a requisição
      setTimeout(() => {
        setProperties(mockData);
        setLoading(false);
      }, 1500); // Simulando 1.5 segundos de delay
    };

    fetchMockProperties();
  }, []);

  if (loading) {
    return (
      <Box display="flex" justifyContent="center" alignItems="center" height="100vh">
        <CircularProgress />
      </Box>
    );
  }

  if (error) {
    return (
      <Box display="flex" justifyContent="center" alignItems="center" height="100vh">
        <Typography variant="h6" color="error">{error}</Typography>
      </Box>
    );
  }

  return (
    <Box sx={{ p: 4 }}>
      <Typography variant="h4" gutterBottom>
        Lista de Imóveis
      </Typography>

      <TableContainer component={Paper}>
        <Table>
          <TableHead>
            <TableRow>
              <TableCell><strong>ID</strong></TableCell>
              <TableCell><strong>Endereço</strong></TableCell>
              <TableCell><strong>Cidade</strong></TableCell>
              <TableCell><strong>Preço</strong></TableCell>
              <TableCell><strong>Proprietário</strong></TableCell>
            </TableRow>
          </TableHead>
          <TableBody>
            {properties.map((property) => (
              <TableRow key={property.id}>
                <TableCell>{property.id}</TableCell>
                <TableCell>{property.endereco}</TableCell>
                <TableCell>{property.cidade}</TableCell>
                <TableCell>{property.preco}</TableCell>
                <TableCell>{property.proprietario_nome}</TableCell>
              </TableRow>
            ))}
          </TableBody>
        </Table>
      </TableContainer>
    </Box>
  );
};

export default PropertyList;
