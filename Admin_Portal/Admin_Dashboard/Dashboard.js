import variables from "../variables.js"



if(!localStorage.getItem('iuToken')){
  window.location.href = `${variables.domain}/Admin_Login/Login.html`
}

const username = document.getElementById('username')
const username1 = document.getElementById('username1')

const token = JSON.parse(localStorage.getItem('iuToken'))

fetch(`${variables.backendDomain}/admin/get-dashboard`, {
  method: "GET",
  headers: {Authorization: `Bearer ${token}`}

})
.then(data=>data.json())
.then(data=>{
  if(data.status != 200){

  }
  else{
    username.innerText = data.admin_data.username
    username1.innerText = data.admin_data.username
  }
})

/////////////SIGN OUT//////////////////////////////////////
const sign_out = document.getElementById("sign-out")
sign_out.addEventListener('click', function(event) {
  event.preventDefault();
  localStorage.removeItem("token")
  window.location.href = "/Admin_Login/Login.html"
})



function createDropdown(toggleId, contentId) {
  const toggleDropdown = document.getElementById(toggleId);
  const dropdownContent = document.getElementById(contentId);

  toggleDropdown.addEventListener('click', function(event) {
    event.preventDefault();
    dropdownContent.classList.toggle('show');
  });

  document.addEventListener('click', function(event) {
    if (event.target !== toggleDropdown && event.target !== dropdownContent) {
      dropdownContent.classList.remove('show');
    }
  });
}

createDropdown('dropDown', 'std_drop');
//createDropdown('dropDown1', 'ins_show');
createDropdown('dropDown2', 'lecturer_dropDown')

// JavaScript for smooth scrolling and content visibility
document.querySelectorAll('li a').forEach(anchor => {
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
      document.querySelectorAll('.content section').forEach(section => {
        section.style.display = 'none';
      });

      targetSection.style.display = 'block';
    }
  });
});

// Handles the form section
const searchBtn = document.getElementById('search')
const regNoInput = document.getElementById('reg_no')
const details = document.getElementById('details')
let student_data;

searchBtn.addEventListener('click', function(event){

  event.preventDefault()
  const reg_no = regNoInput.value
  fetch(`${variables.backendDomain}/admin/get-student-by-jamb-reg-no?jamb_reg_no=${reg_no}`, {
    method: "GET",
    headers: {Authorization: `Bearer ${token}`}
    
  })
  .then(data=>data.json())
  .then(data=>{
    if(data.status != 200){
      details.innerText = data.msg
    }
    else{
      console.log(data)
      student_data = data.student_data
    
      details.innerText = `${data.student_data.first_name} ${data.student_data.last_name} ${data.student_data.jamb_reg_no}`
    }
  })

})

const inputContainer = document.getElementById('section1')
const formContainer =document.getElementById('form')
const first_name = document.getElementById('firstName')
const middle_name = document.getElementById('middleName')
const last_name = document.getElementById('lastName')
const faculty = document.getElementById('faculty')
const department = document.getElementById('department')
const sex = document.getElementById('sex')


let studentDetails;


details.addEventListener('click', function(e){
  if (details.innerText == 'student not found'){
    formContainer.style.display = 'none';
    return
  }
  else{
    inputContainer.style.display = 'none';
    formContainer.style.display = 'block';
    //first_name.value = admitted_student_data.first_name
    first_name.innerText = student_data.first_name
    middle_name.innerText = student_data.middle_name
    last_name.innerText = student_data.last_name
    faculty.innerText = student_data.faculty
    department.innerText = student_data.department
    sex.innerText = student_data.sex




    

    // studentDetails = {
    //   first_name: admitted_student_data.first_name,
    //   last_name: admitted_student_data.last_name,
    //   middle_name: admitted_student_data.middle_name,
    //   faculty: admitted_student_data.faculty,
    //   department: admitted_student_data.department,
    //   sex: admitted_student_data.sex
    // };

  }
}) 

const btn_submit = document.getElementById("btn_submit")

const session = document.getElementById("session")
const password = document.getElementById("password")
const state = document.getElementById("state_of_origin")
const email = document.getElementById("email")
const phone_number = document.getElementById("phone_no")
const guardian_name = document.getElementById("guardian_name")
const guardian_number = document.getElementById("guardian_phone_no")
const duration = document.getElementById("year")



//console.log(admitted_student_data.id)

const matric_no = document.getElementById("matric_noMSG")

btn_submit.addEventListener('click', function(e){
  e.preventDefault()
  const url = `${variables.backendDomain}/admin/give-matric-no?jamb_reg_no=${student_data.jamb_reg_no}`;
 
  // studentDetails.session= session.value
  // studentDetails.password=password.value
  // studentDetails.email= email.value
  // studentDetails.phone_number= phone_number.value
  // studentDetails.guardian_name= guardian_name.value
  // studentDetails.guardian_number= guardian_number.value
  // studentDetails.state_of_origin= state.value 
  // studentDetails.duration = parseInt(duration.value)
  
  fetch(url, {
    method: "PUT",
    headers: {
      "Authorization": `Bearer ${token}`,
      "Content-Type": "application/json"
    },
    body: JSON.stringify({jamb_reg_no: student_data.jamb_reg_no})
  })
  .then(response => {
    return response.json();
  })
  .then(data => {
    if(data.status != 200){
      matric_no.style.color ="red"
      matric_no.innerText = data.msg
    }
    else{
      matric_no.innerText = `Matric Number: ${data.matric_no}`
      matric_no.style.color = "green"
    }
    // Handle the response data as needed
  })
  // .catch(error => {
  //   console.error("Fetch error:", error);
  //   // Handle errors that occur during the fetch
  // });

})


const searchBtn1 = document.getElementById('search1')
const matricNoInput = document.getElementById('matric_no')
const details1 = document.getElementById('details1')
const inputContainer1 = document.getElementById('section3')
const formContainer1 =document.getElementById('form1')

searchBtn1.addEventListener('click', function(event){

  event.preventDefault()
  const matric_no = matricNoInput.value
  console.log(matric_no)
  fetch(`${variables.backendDomain}/admin/get-student?matric_no=${matric_no}`, {
    method: "GET",
    headers: {Authorization: `Bearer ${token}`}
    
  })

  .then(data=>data.json())
  .then(data=>{
    if(data.status != 200){
      console.log(data)
      details1.innerText = data.msg
      formContainer1.style.display = 'none';
    }
    else{
      console.log(data)
      inputContainer1.style.display = 'none';
     formContainer1.style.display = 'flex';
     const studentData = data.student_data
     delete studentData.id
     const student_data_keys = Object.keys(studentData)
     for(let detail of student_data_keys){
      const detail1 = detail.replace(/_/g, ' ')
      const para = `  
        <p>${detail1}: ${studentData[detail]}</p>
      `
      formContainer1.innerHTML += para
     }


    }
  })

})


//////////////// LECTURER FORM ///////////////////

const searchIDBtn = document.getElementById('lecturer_search')
const IDInput = document.getElementById('ID')
const lecturer_details = document.getElementById('lecturer_details')
let employed_lecturer_data;

searchIDBtn.addEventListener('click', function(event){

  event.preventDefault()
  const id_no = IDInput.value
  fetch(`${variables.backendDomain}/employed-lecturer/get-employed-lecturer?id_no=${id_no}`, {
    method: "GET",
    headers: {Authorization: `Bearer ${token}`}
    
  })
  .then(data=>data.json())
  .then(data=>{
    if(data.status != 200){
      lecturer_details.innerText = data.msg
    }
    else{
      console.log(data)
      employed_lecturer_data = data.employed_lecturer_data
    
      lecturer_details.innerText = `${data.employed_lecturer_data.first_name} ${data.employed_lecturer_data.last_name} ${data.employed_lecturer_data.id_no}`
    }
  })

}) 

const lecturer_input = document.getElementById('section5')
const lecturer_form =document.getElementById('form_lecturer')
const first_name1 = document.getElementById('firstName1')
const middle_name1 = document.getElementById('middleName1')
const last_name1 = document.getElementById('lastName1')
const faculty1 = document.getElementById('faculty1')
const department1 = document.getElementById('department1')
const state_of_origin1 = document.getElementById('state_of_origin1')
const email1 = document.getElementById('email1')
const phone_no1 = document.getElementById('phone_number1')
const sex1 = document.getElementById('sex1')




lecturer_details.addEventListener('click', function(e){
  if (details.innerText == 'lecturer not found'){
    lecturer_form.style.display = 'none';
    return
  }
  else{
    lecturer_input.style.display = 'none';
    lecturer_form.style.display = 'block';
    //first_name.value = admitted_student_data.first_name
    first_name1.innerText = employed_lecturer_data.first_name
    middle_name1.innerText = employed_lecturer_data.middle_name
    last_name1.innerText = employed_lecturer_data.last_name
    faculty1.innerText = employed_lecturer_data.faculty
    department1.innerText = employed_lecturer_data.department
    state_of_origin1.innerText = employed_lecturer_data.state_of_origin
    email1.innerText = employed_lecturer_data.email
    phone_no1.innerText = employed_lecturer_data.phone_number
    sex1.innerText = employed_lecturer_data.sex

  }
})
const password1 = document.getElementById("password1")
const btn_submit1 = document.getElementById("btn_submit1")


const ID_msg = document.getElementById("ID_msg")

btn_submit1.addEventListener('click', function(e){
  e.preventDefault()
  const url1 = `${variables.backendDomain}/admin/add-lecturer?id_no=${employed_lecturer_data.id_no}`;

  
  fetch(url1, {
    method: "POST",
    headers: {
      "Authorization": `Bearer ${token}`,
      "Content-Type": "application/json"
    },
    body: JSON.stringify({password: password1.value})
  })
  
  .then(response => {
    return response.json();
  })
  .then(data => {
    if(data.status != 200){
      ID_msg.style.color ="red"
      ID_msg.innerText = data.msg
    }
    else{
      ID_msg.style.color = "green"
      ID_msg.innerText = `Success`
    }
    // Handle the response data as needed
  })
  // .catch(error => {
  //   console.error("Fetch error:", error);
  //   // Handle errors that occur during the fetch
  // });

})



