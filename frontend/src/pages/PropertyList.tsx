import React, { useEffect, useState } from 'react';
import { useNavigate } from 'react-router-dom';
import { Box, Typography, Table, TableBody, TableCell, TableContainer, TableHead, TableRow, Paper, CircularProgress, Button } from '@mui/material';
import { toast } from 'react-toastify'; 
import 'react-toastify/dist/ReactToastify.css';
import { get } from '../services/api';
import {formatCPF, formatCurrency} from '../utils/utils';

interface Property {
  id: BigInt;
  owner: Owner;
  city: string;
  number: string;
  amount: string;
  street: string;
  zipCode: string;
}

interface Owner {
  id: BigInt;
  name: string;
  cpf: string;
  email: string;
}

const PropertyList: React.FC = () => {
  const navigate = useNavigate();
  const [properties, setProperties] = useState<Property[]>([]); // Estado para armazenar as propriedades
  const [loading, setLoading] = useState<boolean>(true); // Estado de carregamento
  const [error, setError] = useState<string>(''); // Para mostrar erros

  useEffect(() => {
    const fetchProperties = async () => {
      setLoading(true);

    get('/properties').then((function (data) {
        const properties = data.response.map((property: Property) => ({
          id: property.id,
          owner: property.owner,
          city: property.city,
          number: property.number,
          amount: property.amount,
          street: property.street,
          zipCode: property.zipCode
        }));

        setProperties(properties);

      setLoading(false);
      }));
    };

    fetchProperties();
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
      {properties.length === 0 ? (
        <Typography variant="body1" color="textSecondary">
          Nenhum imóvel cadastrado
        </Typography>
      ) : (
        <>
          <Typography variant="h4" gutterBottom>
            Lista de Imóveis
          </Typography>

          <Button 
            variant="contained" 
            sx={{ mb: 2 }}
            color="primary" 
            onClick={() => navigate('/property-form')}
          >
            Cadastrar Imóvel
          </Button>

          <TableContainer component={Paper}>
          <Table>
            <TableHead>
              <TableRow>
                <TableCell><strong>ID</strong></TableCell>
                <TableCell><strong>Cidade</strong></TableCell>
                <TableCell><strong>Endereço</strong></TableCell>
                <TableCell><strong>Preço</strong></TableCell>
                <TableCell><strong>Proprietário</strong></TableCell>
              </TableRow>
            </TableHead>
            <TableBody>
              {properties.map((property) => (
                <TableRow key={property.id}>
                  <TableCell>{property.id}</TableCell>
                  <TableCell>{property.city}</TableCell>
                  <TableCell>{property.street} - {property.number}</TableCell>
                  <TableCell>{formatCurrency(property.amount)}</TableCell>
                  <TableCell>{property.owner.name}  | {formatCPF(property.owner.cpf)} | {property.owner.email}</TableCell>
                </TableRow>
              ))}
            </TableBody>
          </Table>
        </TableContainer>
        </>
      )}
    </Box>
  );
};

export default PropertyList;
