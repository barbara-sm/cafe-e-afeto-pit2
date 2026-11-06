CAFÉ & AFETO — PROJETO PIT II
Sistema acadêmico de cafeteria e delivery

REQUISITOS
- Windows 10/11
- XAMPP com Apache, MySQL e PHP 8+
- Navegador atualizado

INSTALAÇÃO
1. Instale e abra o XAMPP.
2. Inicie Apache e MySQL.
3. Copie a pasta cafeteria_pit2 para C:\xampp\htdocs\
4. Abra http://localhost/phpmyadmin
5. Importe o arquivo database/cafeteria_pit2.sql (ou abra e execute seu conteúdo).
6. Confira config/config.php. No XAMPP padrão, usuário root e senha vazia.
7. Abra http://localhost/cafeteria_pit2/
8. Para criar o acesso administrativo, abra http://localhost/cafeteria_pit2/criar_admin.php e escolha nome, e-mail e senha.
9. Depois que a conta for criada, APAGUE o arquivo criar_admin.php da pasta do projeto.
10. Entre no sistema usando o e-mail e a senha que você acabou de cadastrar.

USO
- Cliente: crie uma conta, entre, escolha produtos, adicione ao carrinho e finalize.
- No checkout, informe endereço ou “Retirada no balcão”.
- Pagamento é simulado; não há cobrança ou integração com banco.
- Administrador: abra Administração para cadastrar produtos e atualizar status dos pedidos.

ESTRUTURA
index.php                         Roteador principal
config/config.php                 Configurações e funções auxiliares
app/controllers/                  Controladores (regras de fluxo)
app/models/                       Acesso aos dados e operações
app/views/                        Telas do sistema
public/assets/style.css           Estilos responsivos
public/assets/app.js              Confirmação de ações no painel
database/cafeteria_pit2.sql       Banco de dados e dados iniciais
docs/                             Documentação acadêmica e diagramas
tests/                            Roteiro e formulários de testes

OBSERVAÇÕES IMPORTANTES
- Projeto desenvolvido para demonstração acadêmica local.
- Não coleta cartão nem realiza PIX real.
- O projeto inclui proteção CSRF básica nos formulários de alteração. Antes de publicar na internet, ainda seriam necessários HTTPS, revisão de segurança, política de privacidade, validações adicionais e configuração adequada do servidor.
- Os testes com cinco colegas devem ser realizados de verdade. Não invente nomes, datas ou resultados: preencha os formulários depois da execução.
