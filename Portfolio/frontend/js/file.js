
const submitForm = ()=>{
    const input = document.getElementsByClassName('filein');
    
    // document.getElementsByClassName -- returns array of nodes
    // document.getElementById -- returns single nodes with id

    input_file = input[0]
    console.log(input_file.files[0]);
    const serverPath = "../backend/index.php"
    const formData = new FormData();
    formData.append('file',input_file.files[0]);

    fetch(serverPath, {
        method: 'POST',
        // headers: {
        //   'Content-Type': 'application/json'
        // },
        // body: JSON.stringify({ name: "Amit" , file :input_file.files[0]})
        body: formData
      })
      .then(response => response.json())
      .then(data => {
        document.getElementById("response").innerText = "file uploaded successsfully";
      })
      .catch(error => console.error("Error:", error));

}


const dataEntry = ()=>{
  const temp_name = document.getElementById('userinput')
  // const user_name = temp_data[0];
  const temp_email = document.getElementById('userinput1')
  // const user_email = temp_email[0];
  const temp_phone = document.getElementById('userinput2')
  // const user_phone = temp_phone[0];
  const temp_pass = document.getElementById('userinput3')
  // const user_pass = temp_pass[0];
  const serverPath2 = "../backend/userDetails.php"
  let studentinfo = {
    name:temp_name,
    email:temp_email,
    phone:temp_phone,
    password:temp_pass
  }
  fetch(serverPath2, {
    method:"POST",
    headers: {'Content-Type' : 'application/json'},
    body: studentinfo,
  })
  .then(response => response.json())
  .then(data => {document.getElementById('temp_name').innerText = data.message})
  .catch(error => console.log("Error: " , error));
};