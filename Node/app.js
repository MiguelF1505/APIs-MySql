// node app.js
// npx nodemon app.js

const express = require('express');
const path = require('path');  // Módulo para manipular caminhos de arquivo
const ProdutoRouter = require('./router/ProdutoRouter');
const CategoriaRouter = require('./router/CategoriaRouter');
const SetorRouter = require('./router/SetorRouter');
const UsuarioRouter = require('./router/UsuarioRouter');
const LoginRouter = require('./router/LoginRouter');
//const LoginRouter = require('./router/LoginRouter');

const app = express();

const portaServico = 8000;

app.use(express.json());

app.use(express.static(path.join(__dirname, 'view'))); // Configura a pasta 'view' como estática

const produtoRoteador = new ProdutoRouter();
const categoriaRoteador = new CategoriaRouter();
const setorRoteador = new SetorRouter();
const usuarioRouter = new UsuarioRouter();
const loginRouter = new LoginRouter();

app.get('/', (req, res) => {
    res.sendFile(path.join(__dirname, 'view', 'login.html')); // Envia o arquivo login.html
});

app.use('/login',
    loginRouter.createRoutes()
);

app.use('/categorias',
    categoriaRoteador.createRoutes()
);

app.use('/setores',
    setorRoteador.createRoutes()
);

app.use('/usuarios',
    usuarioRouter.createRoutes()
);

app.use('/produtos',
    produtoRoteador.createRoutes()
);

// Inicia o servidor, escutando na porta definida, e exibe uma mensagem no console com a URL onde o servidor está rodando.
app.listen(portaServico, () => {
    console.log(`API rodando no endereço: http://localhost:${portaServico}/`);
});