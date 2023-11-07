'use strict';

const loginContainer = document.querySelector("#login-container");
const btnSignup = document.querySelector("#btn-signup");
const btnLogin = document.querySelector("#btn-login");
const btnSignupMb = document.querySelector("#signup-mobile");
const btnLoginMb = document.querySelector("#login-mobile");

const moveOverlay = () => loginContainer.classList.toggle("move");

btnSignup.addEventListener("click", moveOverlay);
btnLogin.addEventListener("click", moveOverlay);
btnSignupMb.addEventListener("click", moveOverlay);
btnLoginMb.addEventListener("click", moveOverlay);
