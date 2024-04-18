import variables from "../variables.js"



if(!localStorage.getItem('iuToken')){
  window.location.href = `${variables.domain}/Lecturer_Login/Login.html`
}

const username = document.getElementById('username')
const username1 = document.getElementById('username1')

const token = JSON.parse(localStorage.getItem('iuToken'))

fetch(`${variables.backendDomain}/lecturer/get-dashboard`, {
  method: "GET",
  headers: {Authorization: `Bearer ${token}`}

})
.then(data=>data.json())
.then(data=>{
  if(data.status != 200){

  }
  else{
    console.log(data)
    username1.innerText = data.lecturer_data.first_name + ' ' + data.lecturer_data.last_name
    // username1.innerText = data.admin_data.username
  }
})

/////////////SIGN OUT//////////////////////////////////////
const sign_out = document.getElementById("sign-out")
sign_out.addEventListener('click', function(event) {
  event.preventDefault();
  localStorage.removeItem("token")
  window.location.href = "/Lecturer_Login/Login.html"
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
//   createDropdown('dropDown1', 'ins_show');
  
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

const error_message = document.getElementById('error-container')
const error_msg = document.getElementById('error_msg')
const error_status = document.getElementsByClassName('error-code')
const get_session = document.getElementById('session')
const department = document.getElementById('department')
const cos_code = document.getElementById('cos_code')
const get_student = document.getElementById('get_student')
const get_reg_form = document.getElementById('get_reg_form')

let results = []

get_student.addEventListener('click', function(e){
  e.preventDefault()

  fetch(`${variables.backendDomain}/lecturer/get-registered-course-students?course_code=${cos_code.value}&session=${get_session.value}&department=${department.value}`, {
    method: "GET",
    headers: {Authorization: `Bearer ${token}`}
  
  })
  .then(data=>data.json())
  .then(data=>{
    console.log(data)
    console.log(cos_code.value)
    console.log(get_session.value)
    console.log(department.value)
    if(data.status != 200){
      error_message.style.display = 'block'
      error_msg.innerText = data.msg
      error_status.innerText = data.status
    }
    else{
      console.log(data)
      get_reg_form.style.display = "block"
      section1.style.display = "none"
      const table = document.getElementById('get_reg_student')
      const getRegData = data.students
      let count = 1
      for(let registeredStudent of getRegData){
        
        const tr = `
          <tr> 
            <td>${registeredStudent.matric_no}</td>
            <td><input class= "input_script CA group${count}" type= "number" value = ${registeredStudent.CA ? registeredStudent.CA : ''}></td>
            <td><input class= "input_script exam group${count}" type= "number" value =${registeredStudent.exam ? registeredStudent.exam : ''}></td>
            <td><input class= "input_script total group${count}" type= "number" value =${registeredStudent.total ? registeredStudent.total : ''}></td>
            <td><input class= "input_scripts grade group${count}" type= "text" value =${registeredStudent.grade ? registeredStudent.grade : ''}></td>
            <td><button id=${registeredStudent.id} class="Addbtn group${count}">Add</button></td>
          </tr>
        `

        table.innerHTML += tr
        count++
      }

      const addBtns = document.getElementsByClassName("Addbtn")
      const resultInputs = document.getElementsByClassName("input_script")
      const resultData ={}

      for(let resultInput of resultInputs){
        resultInput.addEventListener('input', function(e){
          const addBtnArr = []
          const recordClassName = e.target.className.split(' ')[2] 
          for(let addBtn of addBtns){
            addBtnArr.push(addBtn)
          }

          const idAddBtn = addBtnArr.filter(function(btnElement){return btnElement.className.includes(recordClassName) })[0]

          results = results.filter(function(resultData){return resultData.id != parseInt(idAddBtn.id)})
          
          idAddBtn.style.backgroundColor = ""
          
        })
      }


      for(let addBtn of addBtns){
        addBtn.addEventListener('click', function(e){

           
          results = results.filter(function(result){
            return result.id != parseInt(e.target.id)
          })
          
          resultData.id = parseInt(e.target.id)
          const group = addBtn.className.split(' ')[1]

          let inputs = document.getElementsByClassName(group)
          const inputsArr = []
          for(let input of inputs){
            if(input.localName == "input"){
              inputsArr.push(input)
            }
          }

          for(let input of inputsArr){
            if(input.className.includes("CA")){
              resultData.CA = parseInt(input.value)
            }
            if(input.className.includes("exam")){
              resultData.exam = parseInt(input.value)
            }
            if(input.className.includes("total")){
              resultData.total = parseInt(input.value)
            }
            if(input.className.includes("grade")){
              resultData.grade = input.value
            }
          }

          results.push(resultData)
          
          addBtn.style.backgroundColor="green"
          addBtn.innerText = "Success"
          
        })
      }

    }
    
  })
})


const submit_result = document.getElementById('submit_result')
submit_result.addEventListener('click', function(e){
  e.preventDefault()
  fetch(`${variables.backendDomain}/lecturer/upload-results`, {
    method: "PUT",
    headers: {Authorization: `Bearer ${token}`},
    body: JSON.stringify(results)
  })
  .then(data=>data.json())
  .then(data=>{
    if(data.status != 200){
      data.msg
    }
    else{
      console.log(data)
      submit_result.innerText = "Success"
    }
  })
})





