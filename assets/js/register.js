document.addEventListener("DOMContentLoaded", (e) => {
  
  document.getElementById("hideLogin").addEventListener("click", () => {
    document.getElementById("loginForm").style.display = "none";
    document.getElementById("RegisterForm").style.display = "block";
  });

  document.getElementById("hideRegister").addEventListener("click", () => {
    document.getElementById("loginForm").style.display = "block";
    document.getElementById("RegisterForm").style.display = "none";
  });
});
