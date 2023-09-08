let recoverySection = document.getElementById('recoverySection');
let newPasswordSection = document.getElementById('newPasswordSection');
let usernameControl = document.getElementById('username');
let recoveryCodeControl = document.getElementById('recovery_code');
let passwordControl = document.getElementById('password');
let password2Control = document.getElementById('password2');
let errorControl = document.getElementById('error');
let successControl = document.getElementById('success');
let btnVerificarCodigo = document.getElementById('btnVerificarCodigo');
let btnEnviarCorreo = document.getElementById('btnEnviarCorreo');
let btnActualizarClave = document.getElementById('btnActualizarClave');
let linkLogin = document.getElementById('linkLogin');

function mostrarError(error) {
    errorControl.innerText = error;
    successControl.classList.add('d-none');
    errorControl.classList.remove('d-none');
}

btnEnviarCorreo.addEventListener('click', async (e) => {
    btnEnviarCorreo.disabled = true;
    let data = {
        username: usernameControl.value
    };

    let response = await fetch('../Controlador/send_recovery_email.php', {
        method: "POST", // *GET, POST, PUT, DELETE, etc.
        cache: "no-cache", // *default, no-cache, reload, force-cache, only-if-cached
        headers: {
            "Content-Type": "application/json",
            // 'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: JSON.stringify(data), // body data type must match "Content-Type" header
    });

    let jsonData = await response.json();

    if (jsonData.success) {
        successControl.innerText = jsonData.msg;
        errorControl.classList.add('d-none');
        successControl.classList.remove('d-none');

        usernameControl.disabled = true;

        recoverySection.classList.remove('d-none');
        btnVerificarCodigo.classList.remove('d-none');
        btnEnviarCorreo.classList.add('d-none');
    } else {
        errorControl.innerText = jsonData.msg;
        successControl.classList.add('d-none');
        errorControl.classList.remove('d-none');
    }

    btnEnviarCorreo.disabled = false;
});

btnVerificarCodigo.addEventListener('click', async () => {
    let data = {
        username: usernameControl.value,
        code: recoveryCodeControl.value
    };

    let response = await fetch('../Controlador/verify_recovery_code.php', {
        method: "POST", // *GET, POST, PUT, DELETE, etc.
        cache: "no-cache", // *default, no-cache, reload, force-cache, only-if-cached
        headers: {
            "Content-Type": "application/json",
            // 'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: JSON.stringify(data), // body data type must match "Content-Type" header
    });

    let jsonData = await response.json();

    if (jsonData.success) {
        successControl.innerText = jsonData.msg;
        errorControl.classList.add('d-none');
        successControl.classList.remove('d-none');

        recoverySection.classList.add('d-none');
        btnVerificarCodigo.classList.add('d-none');
        newPasswordSection.classList.remove('d-none');
        btnActualizarClave.classList.remove('d-none');
    } else {
        errorControl.innerText = jsonData.msg;
        successControl.classList.add('d-none');
        errorControl.classList.remove('d-none');
    }
});

btnActualizarClave.addEventListener('click', async () => {

    if (passwordControl.value == '') {
        mostrarError('Debe digitar una nueva clave');
        return;
    }

    if (passwordControl.value.length < 6) {
        mostrarError('La clave debe tener minimo 6 caracteres de longitud');
        return;
    }

    if (password2Control.value != passwordControl.value) {
        mostrarError('Las claves no coinciden');
        return;
    }


    let data = {
        username: usernameControl.value,
        password: passwordControl.value
    };

    let response = await fetch('../Controlador/update_password.php', {
        method: "POST", // *GET, POST, PUT, DELETE, etc.
        cache: "no-cache", // *default, no-cache, reload, force-cache, only-if-cached
        headers: {
            "Content-Type": "application/json",
            // 'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: JSON.stringify(data), // body data type must match "Content-Type" header
    });

    let jsonData = await response.json();

    if (jsonData.success) {
        successControl.innerText = jsonData.msg;
        errorControl.classList.add('d-none');
        successControl.classList.remove('d-none');

        newPasswordSection.classList.add('d-none');
        btnActualizarClave.classList.add('d-none');
        linkLogin.classList.remove('d-none');
    } else {
        mostrarError(jsonData.msg);
    }
});