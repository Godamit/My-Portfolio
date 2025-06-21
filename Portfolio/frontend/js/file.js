// Cursor 
const cursor = document.getElementById("cursor");

window.addEventListener("mousemove", (e) => {
  cursor.style.left = `${e.clientX}px`;
  cursor.style.top = `${e.clientY}px`;
});

document.querySelectorAll(".btn, .card").forEach((item) => {
  item.addEventListener("mouseenter", () => {
    cursor.style.transform = "translate(-50%, -50%) scale(1.3)";
  });
  item.addEventListener("mouseleave", () => {
    cursor.style.transform = "translate(-50%, -50%) scale(1.5)";
  });
});



const submitForm = () => {
  const input = document.getElementsByClassName("filein");
  const input_file = input[0];
  const uploadedFile = input_file.files[0];

  if (!uploadedFile) return alert("Please choose a file.");

  const serverPath = "../backend/file.php";
  const formData = new FormData();
  
  formData.append("file", uploadedFile);
  formData.append("choice", '1');

  const queueList = document.getElementById("queueList");
  const queueItem = document.createElement("li");
  const queueIndex = queueList.children.length + 1;

  queueItem.innerHTML = `<strong>#${queueIndex}</strong> - ${uploadedFile.name} - <span class="status">Uploading...</span>`;
  queueList.appendChild(queueItem);

  fetch(serverPath, {
    method: "POST",    
    body: formData,
  })
    .then((res) => res.json())
    .then((data) => {
      const statusSpan = queueItem.querySelector(".status");
      loadUploadQueue();

    })
    .catch((err) => {
      console.error("Upload or dump failed", err);
      queueItem.querySelector(".status").innerText = "Error";
    });
    
};






function loadUploadQueue() {
  const formData2 = new FormData();
  formData2.append("choice", '2');
  fetch("../backend/file.php",{ 
    method: "POST",
    body : formData2
  }
  )
    .then((res) => res.json())
    .then((data) => {
      const queueList = document.getElementById("queueList");
      queueList.innerHTML = ""; // Clear list

      if (data.uploads && data.uploads.length > 0) {
        data.uploads.forEach((upload, index) => {
          const li = document.createElement("li");
          let statusText = "Unknown";
          let statusColor = "gray";

          switch (parseInt(upload.status)) {
            case 1:
              statusText = "Pending";
              statusColor = "orange";
              break;
            case 2:
              statusText = "Uploaded";
              statusColor = "lightgreen";
              break;
            case 3:
              statusText = "Error";
              statusColor = "red";
              break;
            default:
              statusText = "Unknown";
              statusColor = "gray";
          }

          li.innerHTML = `<strong>#${index + 1}</strong> - ${upload.filename} - <span class="status" style="color: ${statusColor}">${statusText}</span>`;
          queueList.appendChild(li);
        });
      } else {
        queueList.innerHTML = "<li>No uploads yet.</li>";
      }
    })
    .catch((err) => {
      console.error("Failed to load upload queue", err);
    });
}
window.onload = loadUploadQueue;



// const dataEntry = ()=>{
//   const temp_name = document.getElementById('userinput')
//   // const user_name = temp_data[0];
//   const temp_email = document.getElementById('userinput1')
//   // const user_email = temp_email[0];
//   const temp_phone = document.getElementById('userinput2')
//   // const user_phone = temp_phone[0];
//   const temp_pass = document.getElementById('userinput3')
//   // const user_pass = temp_pass[0];
//   const serverPath2 = "../backend/userDetails.php"
//   let studentinfo = {
//     name:temp_name,
//     email:temp_email,
//     phone:temp_phone,
//     password:temp_pass
//   }
//   fetch(serverPath2, {
//     method:"POST",
//     headers: {'Content-Type' : 'application/json'},
//     body: studentinfo,
//   })
//   .then(response => response.json())
//   .then(data => {document.getElementById('temp_name').innerText = data.message})
//   .catch(error => console.log("Error: " , error));
// };