import variables from "./variables.js"

const reg_no = document.getElementById('reg_no')
const password = document.getElementById('password')
const submit_btn = document.getElementById('submit')
const error_msg = document.getElementById('error_msg')
function submit_function(e){
    e.preventDefault();
    const loginData = {jamb_reg_no: reg_no.value, password: password.value}
    const serializedData = JSON.stringify(loginData)
    
    fetch(`${variables.backendDomain}/school/create-student-account`, {
        method: "POST",
        headers: {"Content-type": "application/json"},
        body: serializedData
    })
    .then(function(data){return data.json()})
    .then(function(data){
        if(data.status != 200){
          console.log(data)
            error_msg.innerText = data.msg
        }
        else{
          console.log(data)
            error_msg.style.color = "green"
            error_msg.innerText = data.msg
          window.location.href = `http://127.0.0.1:5504/Student_Login/Login.html`
        }
    })
    
}

submit_btn.addEventListener('click', submit_function)

// JavaScript for smooth scrolling and content visibility
document.querySelectorAll('a').forEach(anchor => {
  anchor.addEventListener('click', function (e) {
    e.preventDefault();

    const targetSection = document.querySelector(this.getAttribute('href'));
    //console.log('targetSection:', targetSection); // Debugging line

    const topOffset = 50;

    if (targetSection) {
      const elementPosition = targetSection.getBoundingClientRect().top;
      const offsetPosition = elementPosition - topOffset;

      window.scrollBy({
        top: offsetPosition,
        behavior: 'smooth'
      });

      // Show the corresponding section and hide others
      document.querySelectorAll('.create section').forEach(section => {
        section.style.display = 'none';
      });

      targetSection.style.display = 'block';
    }
  });
});


document.addEventListener("DOMContentLoaded", function() {
  const dropdownButton = document.getElementById("dropDown");
  const dropdownList = document.getElementById("login_show");

  dropdownButton.addEventListener("click", function() {
      dropdownList.classList.toggle("show");
  });

  // Close the dropdown when clicking outside
  window.addEventListener("click", function(event) {
      if (!dropdownButton.contains(event.target)) {
          dropdownList.classList.remove("show");
      }
  });
});
