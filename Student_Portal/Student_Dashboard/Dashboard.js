import variables from "../variables.js";



if(!localStorage.getItem('iuToken')){
  window.location.href = `${variables.domain}/Admin_Login/Login.html`
}

const username = document.getElementById('username')

const token = JSON.parse(localStorage.getItem('iuToken'))

fetch(`${variables.backendDomain}/student/get-dashboard`, {
  method: "GET",
  headers: {Authorization: `Bearer ${token}`}

})
.then(data=>data.json())
.then(data=>{
  if(data.status != 200){

  }
  else{
    username.innerText = data.student_data.first_name + ' ' + data.student_data.last_name
  }
})

/////////////SIGN OUT//////////////////////////////////////
const sign_out = document.getElementById("sign-out")
sign_out.addEventListener('click', function(event) {
  event.preventDefault();
  localStorage.removeItem("token")
  window.location.href = "/Student_Login/Login.html"
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

createDropdown('dropDown', 'bio_drop');
createDropdown('dropDown2', 'fees_show');
createDropdown('dropDown3', 'reg_show');
createDropdown('dropDown4', 'result_show');
createDropdown('dropDown5', 'hostel_show');
createDropdown('dropDown6', 'course_show');

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

const first_name = document.getElementById('firstName')
const middle_name = document.getElementById('middleName')
const last_name = document.getElementById('lastName')
const faculty = document.getElementById('faculty')
const department = document.getElementById('department')
const reg_number = document.getElementById('reg_number')
const sex = document.getElementById('sex')
const duration = document.getElementById('duration')
const email = document.getElementById('email')
const phone_number = document.getElementById('phone_no')
const guardian_name = document.getElementById('guardian_name')
const guardian_number = document.getElementById('guardian_phone_no')
const state_of_origin = document.getElementById('state')
const year = document.getElementById('year')
const matric_number =document.getElementById('matric_number')

fetch(`${variables.backendDomain}/student/get-dashboard`, {
  method: "GET",
  headers: {Authorization: `Bearer ${token}`}

})
.then(data=>data.json())
.then(data=>{
  if(data.status != 200){

  }
  else{
    console.log(data)
    first_name.innerText = data.student_data.first_name
    middle_name.innerText = data.student_data.middle_name
    last_name.innerText = data.student_data.last_name
    faculty.innerText = data.student_data.faculty
    department.innerText = data.student_data.department
    reg_number.innerText = data.student_data.jamb_reg_no
    matric_number.innerText = data.student_data.matric_no
    sex.innerText = data.student_data.sex
    duration.innerText = data.student_data.duration 
    email.innerText = data.student_data.email 
    phone_number.innerText = data.student_data.phone_number
    guardian_name.innerText = data.student_data.guardian_name 
    guardian_number.innerText = data.student_data.guardian_number
    state_of_origin.innerText = data.student_data.state_of_origin
    year.innerText = data.student_data.year

  }
})



const email_id = document.getElementById('email_id')
const phone_no_id = document.getElementById('phone_no_id')
const guardian_name_id = document.getElementById('guardian_name_id')
const guardian_number_id = document.getElementById('guardian_phone_no_id')
const state_of_origin_id = document.getElementById('state_id')

fetch(`${variables.backendDomain}/student/get-profile-update-data`, {
  method: "GET",
  headers: {Authorization: `Bearer ${token}`}

})
.then(data=>data.json())
.then(data=>{
  if(data.status != 200){

  }
  else{
    console.log(data)
    // email_id.value = data.data.email 
    phone_no_id.value = data.data.phone_number
    guardian_name_id.value = data.data.guardian_name 
    guardian_number_id.value = data.data.guardian_number
    state_of_origin_id.value = data.data.state_of_origin
  }

})


const update_biodata = document.getElementById("update_biodata")
update_biodata.addEventListener('click', function(event){
  event.preventDefault()

  const inputDetails = {
    // email: email_id.value,
    state_of_origin: state_of_origin_id.value,
    phone_number: phone_no_id.value,
    guardian_name: guardian_name_id.value,
    guardian_number: guardian_number_id.value,
  }
  const inputData = JSON.stringify(inputDetails)
  fetch(`${variables.backendDomain}/student/update-profile`, {
    method: "PUT",
    headers: {Authorization: `Bearer ${token}`},
    body: inputData
  })
  .then(data=>data.json())
  .then(data=>{
    if(data.status !=200){
      data.msg
    }
    else{
      console.log(inputDetails)
    }
  })
})

const new_email = document.getElementById('new_email')
const change_email_password = document.getElementById('change_email_password')
const change_email = document.getElementById('change-email')
change_email.addEventListener('click', function(e){
  e.preventDefault()

  const update_email = {
    email: new_email.value,
    password: change_email_password.value
  }
  const updated_email = JSON.stringify(update_email)
  fetch(`${variables.backendDomain}/student/update-email`, {
    method: "PUT",
    headers: {Authorization: `Bearer ${token}`},
    body: updated_email
  })
  .then(data=>data.json())
  .then(data=>{
    if(data.status != 200){
      data.msg
      change_email.style.color = "red"
      change_email.innerText = "Invalid Password"
    }
    else{
      change_email.style.color = "green"
      change_email.innerText = "Success"
    }
  })
})

const old_password = document.getElementById('old_password')
const new_password = document.getElementById('new_password')
const change_password = document.getElementById('change_password')
change_password.addEventListener('click', function(e){
  e.preventDefault()

  const update_password = {
    old_password: old_password.value,
    new_password: new_password.value
  }
  const updated_password = JSON.stringify(update_password)
  fetch(`${variables.backendDomain}/student/update-password`, {
    method: "PUT",
    headers: {Authorization: `Bearer ${token}`},
    body: updated_password
  })
  .then(data=>data.json())
  .then(data=>{
    if(data.status != 200){
      data.msg
      change_password.style.color = "red"
      change_password.innerText = "Invalid Password"
    }
    else{
      console.log(update_password)
      change_password.style.color = "Green"
      change_password.innerText = "Success"
    }
  })
})

const btn_pay = document.getElementById('btn-pay')
const card_number = document.getElementById('card_number')
const cvv= document.getElementById('cvv')
const expiry_date = document.getElementById('expiry_date')
const pay_session = document.getElementById('payment_session')
const success = document.getElementById("success")


btn_pay.addEventListener('click', function(e){
  e.preventDefault()
  //card.style.display = 'none';
  const paymentDetails = {
    card_number: card_number.value,
    cvv: cvv.value,
    expiry_date,
    session: pay_session.value
  }

  const serializedData = JSON.stringify(paymentDetails)
  const token = JSON.parse(localStorage.getItem('iuToken'))

  fetch(`${variables.backendDomain}/student/pay-school-fees`,{
    method: "PUT",
    headers: {Authorization: `Bearer ${token}`},
    body: serializedData
    
  
  })
  .then(data=>data.json())
  .then(data=>{
    if (data.status != 200){
      data.msg
    }
    else {
      printForm('form')
    }
  })
})
//card.style.display = 'block';

//originalContent.card.style.display = 'block'

const searchBtn = document.getElementById('search')
const Year = document.getElementById('Year')
const details = document.getElementById('details')
let admitted_student_data;
const form = document.getElementById('form')
const section = document.getElementById('section3')

searchBtn.addEventListener('click', function(event){

  event.preventDefault()
  const year = Year.value
  fetch(`${variables.backendDomain}/student/get-school-fees?year=${year}`, {
    method: "GET",
    headers: {Authorization: `Bearer ${token}`}
    
  })
  .then(data=>data.json())
  .then(data=>{
    if(data.status != 200){
      details.innerText = data.msg
      form.style.display = 'none'
      section.style.display = 'block'
    }
    else{
      const table = document.getElementById('fees_table')
      const schoolFeesData = data.school_fees_data
      const total = {TOTAL: schoolFeesData.TOTAL}
      delete schoolFeesData.TOTAL
      delete schoolFeesData.id
      const schoolFeesKeys = Object.keys(schoolFeesData)
      for(let levy of schoolFeesKeys){
        let levy1 = levy.replace(/_/g, ' ')
        levy1[0].toUpperCase()

        const tr = `
          <tr> 
            <td>${levy1}</td>
            <td><span>${schoolFeesData[levy]}</span></td>
          </tr>
        `

        table.innerHTML += tr
      }
      const th = `
          <tr> 
            <th>TOTAL</th>
            <th><span>${total.TOTAL}</span></th>
          </tr>
        `
        table.innerHTML += th


      form.style.display = 'block'
      section.style.display = 'none'

    }
  })

})
const inputContainer = document.getElementById('section3')
const formContainer =document.getElementById('form')

details.addEventListener('click', function(e){
  if (details.innerText == 'student not found'){
    formContainer.style.display = 'none';
    return
  }
  else{
    inputContainer.style.display = 'none';
    formContainer.style.display = 'block';

  }
}) 



//For printing of school fees form
function printForm(sectionId){
  let printContent = document.getElementById(sectionId).innerHTML;
  let originalContent = document.body.innerHTML;

  document.body.innerHTML = printContent;
  window.print();

  document.body.innerHTML = originalContent;
}

document.getElementById('form').addEventListener('submit', function(event){
  event.preventDefault();
  printForm('form');
})

document.getElementById('btn-pay').addEventListener('click', function() {
  
  setTimeout(function() {
    alert('Payment Successful!')
  }, 2000);
  
  setTimeout(function() {
    location.reload();
  }, 2000);
});


//COURSE REG FUNCTIONALITY

const get_course = document.getElementById("get_course")
const course_reg_form = document.getElementById("course_reg_form")
const level = document.getElementById("level")
const semester = document.getElementById("semester")
const course_reg = document.getElementById("course_reg")
const register = document.getElementById("register")
const courseIDs = []
const course_reg_session = document.getElementById("course_reg_session")
//const Level = level.value
//const Semester = semester.value
get_course.addEventListener('click', function(event){
  event.preventDefault()
  fetch(`${variables.backendDomain}/student/get-registration-courses?level=${level.value}&semester=${semester.value}`, {
    method: "GET",
    headers: {Authorization: `Bearer ${token}`}
    
  })
  .then(data=>data.json())
  .then(data=>{
    console.log(data)
    register.style.display = 'none'
    course_reg_form.style.display = 'block'
    for(let course of data.courses){
      const tr = `
      
        <tr class="course_reg_container">
          <td><input class="course_reg_check" id="${course.id}" type="checkbox"></td>
          <td>${course.title}</td>
          <td>${course.code}</td>
          <td>${course.unit}</td>
        </tr>
      `

      course_reg.innerHTML+=tr

    }
    const checkboxes = document.getElementsByClassName('course_reg_check')

    for(let checkbox of checkboxes){
      checkbox.addEventListener("click", function(e){
        if(e.target.checked){
          courseIDs.push(e.target.id)
        }
        else{
          const checkboxIndex = courseIDs.indexOf(e.target.id)
          courseIDs.splice(checkboxIndex, 1)
        }
          
      })
    }

  })
})

const submit_course = document.getElementById("submit_course")

submit_course.addEventListener('click', function(event){
  event.preventDefault()
  console.log(courseIDs)
  
  const serializedData = JSON.stringify(courseIDs)

  fetch(`${variables.backendDomain}/student/register-courses?session=${course_reg_session.value}&semester=${semester.value}`,{
    method: "POST",
    headers: {Authorization: `Bearer ${token}`},
    body: serializedData
  })
  .then(data=>data.json())
  .then(data=>{
    console.log(data)
    if(data.status != 200){
      submit_course.innerText = data.msg
      submit_course.style.color = 'red'
      setTimeout(function() {
        alert('Course Not Registered')
      }, 2000);

      setTimeout(function() {
        location.reload();
      }, 2000);
    }
    else{
      setTimeout(function() {
        alert('Course Registered')
      }, 2000);
      
      setTimeout(function() {
        location.reload();
      }, 2000);
      printForm('course_reg_form')
    }
  })
})

document.getElementById('course_reg_form').addEventListener('submit', function(event){
  event.preventDefault();
  printForm('course_reg_form');
})

const extra_course = document.getElementById('extra_course')
extra_course.addEventListener('click', function(event){


  event.preventDefault()
  fetch(`${variables.backendDomain}/student/get-more-registration-courses?semester=${semester.value}` ,{
    method: "GET",
    headers: {Authorization: `Bearer ${token}`}
    
  })
  
  .then(data=>data.json())
  .then(data=> {
    //console.log(data.status)
    if(data.status != 200){

    }
    else{



      for(let course of data.courses){
        const tr = `
        
          <tr class="course_reg_container">
            <td><input class="course_reg_check1" id="${course.id}" type="checkbox"></td>
            <td>${course.title}</td>
            <td>${course.code}</td>
            <td>${course.unit}</td>
          </tr>
        `
  
        course_reg.innerHTML+=tr
  
      }
    }



    const checkboxes = document.getElementsByClassName('course_reg_check1')

    for(let checkbox of checkboxes){
      checkbox.addEventListener("click", function(e){
        if(e.target.checked){
          courseIDs.push(e.target.id)
        }
        else{
          const checkboxIndex = courseIDs.indexOf(e.target.id)
          courseIDs.splice(checkboxIndex, 1)
        }
          
      })
    }
  })
})

const result_session = document.getElementById('result_session')
const result_semester = document.getElementById('result_semester')
const get_reg_form = document.getElementById('get_reg_form')
const result_student = document.getElementById('result_student')
const get_result = document.getElementById('get_result')
get_result.addEventListener('click', function(e){
  e.preventDefault()
  fetch(`${variables.backendDomain}/student/get-result?session=${result_session.value}&semester=${result_semester.value}`, {
    method: "GET",
    headers: {Authorization: `Bearer ${token}`}
  
  })
  .then(data=>data.json())
  .then(data=>{
    if(data.status != 200){
      data.msg
    }
    else{
      console.log(data)
      get_reg_form.style.display = "block"
      result_student.style.display = "none"
      const getResult = data.result
      const table = document.getElementById('get_reg_student') 

      for(let Results of getResult){
        const tr = `
          <tr> 
            <td><span>${Results.code}</span></td>
            <td><span>${Results.CA}</span></td>
            <td><span>${Results.exam}</span></td>
            <td><span>${Results.total}</span></td>
            <td><span>${Results.grade}</span></td>
          </tr>
        `
        table.innerHTML += tr
      }
    }
  
  })
})

const course_reg_sessions = document.getElementById('course_reg_sessions')
const course_reg_semester = document.getElementById('course_reg_semester')
const reprint_course_form = document.getElementById('reprint_course_form')
const reprint_course_reg = document.getElementById('reprint_course_reg')
const reprint_course = document.getElementById('reprint_course')
reprint_course.addEventListener('click', function(e){
  e.preventDefault()
  fetch(`${variables.backendDomain}/student/get-prev-course-reg?session=${course_reg_sessions.value}&semester=${course_reg_semester.value}`, {
    method: "GET",
    headers: {Authorization: `Bearer ${token}`}
  
  })
  .then(data=>data.json())
  .then(data=>{
    if(data.status != 200){
      data.msg
    }
    else{
      console.log(data)
      reprint_course_form.style.display = "block"
      reprint_course_reg.style.display = "none"
      const viewCourse = data.courses
      const table1 = document.getElementById('reprint_course_student') 

      for(let Results of viewCourse){
        const tr = `
          <tr> 
            <td><span>${Results.title}</span></td>
            <td><span>${Results.code}</span></td>
            <td><span>${Results.unit}</span></td>
            <td><span>${Results.semester}</span></td>
          </tr>
        `
        table1.innerHTML += tr
      }
      printForm('reprint_course_form')
    }
  
  })
})
