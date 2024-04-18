import variables from "../variables.js"

const username = document.getElementById("username")
const password = document.getElementById("password")
const submit_btn = document.getElementById("submit")
const error_paragraph = document.getElementById("error_paragraph")



function submit_function(e){
    
    e.preventDefault();
    const loginData = {username: username.value, password: password.value}
    const serializedData = JSON.stringify(loginData)

    fetch(`${variables.backendDomain}/admin/login`, {
        method: "PUT",
        headers: {"Content-type": "application/json"},
        body: serializedData

    })
    .then(function(data){return data.json()} )
    .then(function(data){
        if(data.status == 200){
            localStorage.setItem('iuToken', JSON.stringify(data.iuToken))
            window.location.href = `${variables.domain}/Admin_Dashboard/Dashboard.html`
            history.replaceState({}, ``, `${variables.domain}/Admin_Dashboard/Dashboard.html`);
        }
        else if(data.status == 400){
            error_paragraph.innerText = data.msg
        }

    })
}

submit_btn.addEventListener('click', submit_function)
