LocaSeguro

Rotas API:

Listar todos os registros
```
GET /properties
```

Criar um novo registro
```
POST /properties

Request:

{
    "owner": {
        "name": "Teste 123",
        "email": "teste@email.com", // Deve ser único, caso já esteja registrado no sistema será utilizado o cadastro existente para criação do registro
        "cpf": "01234567890" // Deve ser único
    },
    "city": "Fortaleza",
    "street": "Rua Beija-flor",
    "number": "119",
    "zipCode": "60820110",
    "amount": "100000" // Deve ser em centavos
}
```

Checar se um email já está em uso:
```
GET /properties/check-owner-email?email=teste@email.com
```


Testes automatizados:

```bash
composer run test:integration
```