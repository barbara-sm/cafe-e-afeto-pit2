// Confirmação antes de desativar um produto no painel.
document.addEventListener('submit', function (event) {
    const formulario = event.target;
    const mensagem = formulario.dataset.confirm;

    if (mensagem && !window.confirm(mensagem)) {
        event.preventDefault();
    }
});
