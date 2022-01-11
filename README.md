# OBSERVAÇÕES

## ENV
- Incluir a variável APP_TIMEZONE com o valor referente a timezone local no arquivo .env
    Ex: APP_TIMEZONE='America/Sao_Paulo'

## APP CONFIG
- Alterar o arquivo config/app.php na configuração da Application Timezone para:
    'timezone' => env('APP_TIMEZONE', 'UTC'),

=========================================
# ATUALIZAÇÕES

## v1.0

### 04/01/2022
- Finalização parcial do menu Usuários
- Criação de views, models, controller e CRUDs dos outros menus
- Trabalhando especificamento no menu Notas. Adicionando items nas notas

### 23/12/2021
- Atualização do menu Usuários

### 22/12/2021
- Atualização do menu Usuários

### 21/12/2021
- Atualização do menu Usuários

### Primeira Atualização - 21/12/2021
- Criação das migrations e seeds
- Criação dos menus


